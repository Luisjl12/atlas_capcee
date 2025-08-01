<?php

namespace App\Http\Controllers;

use App\Models\ArchivosPlantel;
use App\Models\Municipio;
use Illuminate\Http\Request;
use App\Models\Plantel;
use App\Models\Localidad;
use App\Models\Corde;
use App\Models\GaleriaFotos;
use App\Models\Usuario;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


class PlantelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $planteles = Plantel::with(['municipio', 'director'])->paginate(10);
        return view('planteles.index', compact('planteles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $municipios = Municipio::all();
        $localidades = Localidad::all();
        $cordes = Corde::all();
        $directores = Usuario::whereHas('rol', function ($q) {
            $q->where('nombre_rol', 'DIRECTOR');
        })->get();

        return view('planteles.create', compact('municipios', 'localidades', 'cordes', 'directores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar solo la sección I (Identificación)
        $validated = $request->validate([
            'cct' => 'required|unique:planteles',
            'nombre_escuela' => 'required',
            'nivel_educativo' => 'required',
            'turno' => 'required',
            'sostenimiento' => 'required',
        ]);

        // Crear el plantel solo con los campos iniciales
        $plantel = Plantel::create($validated);

        // Redirigir al formulario de edición para continuar llenando
        return redirect()->route('planteles.edit', $plantel->id)
            ->with('success', 'Sección I guardada. Ahora puedes llenar el resto del formulario.');
    }

    public function updateUbicacion(Request $request, $id)
    {
        $plantel = Plantel::findOrFail($id);


        if ($request->filled('nuevo_municipio')) {
            $nombreMunicipio = trim(Str::title($request->nuevo_municipio));
            $municipio = Municipio::firstOrCreate(['nombre_municipio' => $nombreMunicipio]);
            $municipio_id = $municipio->id;
        } else {
            $municipio_id = $request->id_municipio;
        }

        // Crear o usar localidad
        if ($request->filled('nuevo_localidad')) {
            $nombreLocalidad = trim(Str::title($request->nuevo_localidad));
            $localidad = Localidad::firstOrCreate(
                [
                    'nombre_localidad' => $nombreLocalidad,
                    'municipio_id' => $municipio_id,
                ]
            );

            $localidad_id = $localidad->id;
        } else {
            $localidad_id = $request->id_localidad;
        }

        // Crear o usar corde
        if ($request->filled('nuevo_corde')) {
            $nombreCorde = trim(Str::title($request->nuevo_corde));
            $corde = Corde::firstOrCreate(['nombre_corde' => $nombreCorde]);
            $corde_id = $corde->id;
        } else {
            $corde_id = $request->id_corde;
        }

        // Ya tenemos los ids correctos, ahora fusionamos en el request
        $request->merge([
            'id_municipio' => $municipio_id,
            'id_localidad' => $localidad_id,
            'id_corde' => $corde_id,
        ]);

        // Validar y procesar localidad, municipio, corde (incluyendo si es nuevo)
        $plantel->update([
            'id_municipio' => $request->input('id_municipio'),
            'id_localidad' => $request->input('id_localidad'),
            'id_corde' => $request->input('id_corde'),
            'domicilio_calle_numero' => $request->input('domicilio_calle_numero'),
            'domicilio_colonia' => $request->input('domicilio_colonia'),
            'domicilio_cp' => $request->input('domicilio_cp'),
            'latitud' => $request->input('latitud'),
            'longitud' => $request->input('longitud'),
        ]);

        return back()->with('success', 'Sección II guardada correctamente.');
    }

    public function updateContacto(Request $request, $id)
    {
        $plantel = Plantel::findOrFail($id);

        $plantel->update([
            'telefono_plantel' => $request->input('telefono_plantel'),
            'correo_institucional' => $request->input('correo_institucional'),
            'nombre_director_registrado' => $request->input('nombre_director_registrado'),
            'id_director_asignado' => $request->input('id_director_asignado'),
        ]);

        return back()->with('success', 'Sección III guardada correctamente.');
    }
    public function updateAccesibilidad(Request $request, $id)
    {
        $plantel = Plantel::findOrFail($id);

        $request->merge([
            'accesibilidad_rampas' => $request->has('accesibilidad_rampas') ? 1 : 0,
            'accesibilidad_banos_adaptados' => $request->has('accesibilidad_banos_adaptados') ? 1 : 0,
            'accesibilidad_sanaletica_braille' => $request->has('accesibilidad_sanaletica_braille') ? 1 : 0,
        ]);

        $request->validate([
            'accesibilidad_otros' => 'nullable|string|max:255',
        ]);

        $plantel->update([
            'accesibilidad_rampas' => $request->accesibilidad_rampas,
            'accesibilidad_banos_adaptados' => $request->accesibilidad_banos_adaptados,
            'accesibilidad_sanaletica_braille' => $request->accesibilidad_sanaletica_braille,
            'accesibilidad_otros' => $request->accesibilidad_otros,
        ]);

        return redirect()->back()->with('success', 'Datos de accesibilidad actualizados correctamente.');
    }
    public function updateTotalUsuariosPlanteles(Request $request, $id)
    {
        $plantel = Plantel::findOrFail($id);

        $request->validate([
            'total_alumnos' => 'required|integer|min:0',
            'total_docentes' => 'required|integer|min:0',
            'total_administrativos' => 'required|integer|min:0',
        ]);

        $plantel->update([
            'total_alumnos' => $request->total_alumnos,
            'total_docentes' => $request->total_docentes,
            'total_administrativos' => $request->total_administrativos,
        ]);

        return redirect()->back()->with('success', 'Totales de usuarios actualizados correctamente.');
    }

    public function updateEstatus(Request $request, $id)
    {
        $plantel = Plantel::findOrFail($id);

        $request->validate([
            'estatus_plantel' => 'required|in:ACTIVO,INACTIVO,EN_REVISION',
        ]);

        $plantel->update([
            'estatus_plantel' => $request->estatus_plantel,
        ]);

        return redirect()->back()->with('success', 'Estatus del plantel actualizado correctamente.');
    }


    public function asignar(Usuario $usuario)
    {
        if ($usuario->tieneRol('DIRECTOR')) {
            // lógica para asignar el usuario como director a un plantel
        } else {
            abort(403, 'Este usuario no es un director');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $plantel = Plantel::with(['espacios', 'detalleHidrosanitario', 'detalleServicio', 'detalleProteccionCivil'])->findOrFail($id);
        $hidrosanitario = $plantel->detalleHidrosanitario;
        $servicio = $plantel->detalleServicio;
        $estadosConservacion = ['BUENO', 'REGULAR', 'MALO', 'NO_APLICA', 'EN_PROCESO'];
        $archivos = ArchivosPlantel::where('cct', $plantel->cct)->get();
        $fotos = GaleriaFotos::with(['usuario', 'plantel'])->where('cct', $plantel->cct)->get();
        return view('planteles.show', compact('plantel', 'hidrosanitario', 'servicio', 'estadosConservacion', 'archivos', 'fotos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $plantel = Plantel::findOrFail($id);
        $municipios = Municipio::all();
        $localidades = Localidad::all();
        $cordes = Corde::all();
        $directores = Usuario::whereHas('rol', function ($q) {
            $q->where('nombre_rol', 'DIRECTOR');
        })->get();

        return view('planteles.edit', compact('plantel', 'municipios', 'localidades', 'cordes', 'directores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $plantel = Plantel::findOrFail($id);

        // Validación con campos alternativos
        $request->validate([
            'cct' => 'required|string|max:20|unique:planteles,cct,' . $plantel->id . ',id',
            'nombre_escuela' => 'required|string|max:255',
            'nivel_educativo' => 'required|string|max:255',
            'turno' => 'required|string|max:100',
            'sostenimiento' => 'required|string|max:100',
        ]);

        $plantel->update($request->only([
            'nombre_escuela',
            'cct',
            'nivel_educativo',
            'turno',
            'sostenimiento'
        ]));
        return redirect()->back()->with('success', 'Sección I actualizada correctamente.');
    }


    public function getLocalidades($municipioId)
    {
        $localidades = Localidad::where('municipio_id', $municipioId)->orderBy('nombre_localidad')->get();
        return response()->json($localidades);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $planteles = Plantel::findOrFail($id);
        $planteles->delete();

        return redirect()->route('planteles.index')->with('success', 'Plantel eliminado correctamente');
    }

    public function editarProteccionCivil($id)
    {
        $plantel = Plantel::with('detalleProteccionCivil')->findOrFail($id);
        $estadosProteccionCivil = [
            'COMPLETA' => 'Completa',
            'PARCIAL' => 'Parcial',
            'INEXISTENTE' => 'Inexistente',
            'NO_APLICA' => 'NO aplica'
        ];
        return view('planteles.edit_proteccionCivil', compact('plantel', 'estadosProteccionCivil'));
    }

    public function guardarProteccionCivil(Request $request, $cct)
    {

        $plantel = Plantel::where('cct', $cct)->firstOrFail();

        $detalle = $plantel->detalleProteccionCivil;

        $data = $request->validate([
            'programa_interno_pc' => 'required|boolean',
            'programa_interno_pc_fecha' => 'nullable|date',
            'alarma_sismica' => 'required|boolean',
            'alarma_sismica_funcional' => 'required|boolean',
            'senaletica_estado' => 'required',
            'extintores_cantidad' => 'required|string|max:255',
            'extintores_vigente' => 'required|boolean',
            'brigadas_conformadas' => 'required|boolean',
        ]);

        if (!$detalle) {
            $data['cct'] = $cct;
            $detalle = new \App\Models\DetalleProteccionCivil($data);
            $detalle->save();
        } else {
            $detalle->update($data);
        }
        return redirect()->route('planteles.show', $plantel->id)->with('success', 'Proteccion civil guardada correctamente');
    }
    public function buscar(Request $request)
    {
        $query = Plantel::with(['municipio', 'director']);

        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre_escuela', 'LIKE', "%{$buscar}%")
                    ->orWhere('cct', 'LIKE', "%{$buscar}%");
            });
        }

        $planteles = $query->get();

        return response()->json([
            'html' => view('partials.lista', compact('planteles'))->render()
        ]);
    }
}
