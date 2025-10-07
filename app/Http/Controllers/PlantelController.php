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
use App\Models\InmuebleNivel;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use OwenIt\Auditing\Models\Audit;


class PlantelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //Funcion para el index
    public function index()
    {
        $planteles = Plantel::with(['municipio', 'director'])->paginate(50);
        return view('planteles.index', compact('planteles'));
    }

    /**
     * Show the form for creating a new resource.
     */

    //Funcion para crear planteles
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
            ->with('success', 'Plantel guardado correctamente.');
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
        return back()->with('success', 'Datos de ubicacion creados correctamente');
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
        return back()->with('success', 'Datos de contacto actualizados correctamente');
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
        $niveles = InmuebleNivel::where('cct', $plantel->cct)->get();
        $hidrosanitario = $plantel->detalleHidrosanitario;
        $servicio = $plantel->detalleServicio;
        $estadosConservacion = ['BUENO', 'REGULAR', 'MALO', 'NO_APLICA', 'EN_PROCESO'];
        $archivos = ArchivosPlantel::where('cct', $plantel->cct)->get();
        $fotos = GaleriaFotos::with(['usuario', 'plantel'])->where('cct', $plantel->cct)->get();
        $nivelesEducativos = Plantel::select('nivel_educativo')
            ->distinct()
            ->pluck('nivel_educativo')
            ->sort();

        $mapData = [
            'lat' => $plantel->latitud,
            'lng' => $plantel->longitud,
            'nombre' => $plantel->nombre_escuela,
            'cct' => $plantel->cct
        ];

        // Agregamos los planteles para el mapa
        $planteles = Plantel::select('nombre_escuela', 'latitud', 'longitud')
            ->whereNotNull('latitud')->whereNotNull('longitud')
            ->get();

        return view('planteles.show', compact(
            'plantel',
            'hidrosanitario',
            'servicio',
            'estadosConservacion',
            'archivos',
            'fotos',
            'planteles', // lo mandamos a la vista
            'mapData',
            'niveles',
            'nivelesEducativos'
        ));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $plantel = Plantel::findOrFail($id);
        $niveles = InmuebleNivel::where('cct', $plantel->cct)->get();
        $municipios = Municipio::all();
        $localidades = Localidad::all();
        $cordes = Corde::all();
        $directores = Usuario::whereHas('rol', function ($q) {
            $q->where('nombre_rol', 'DIRECTOR');
        })->get();

        return view('planteles.edit', compact('plantel', 'municipios', 'localidades', 'cordes', 'directores', 'niveles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $plantel = Plantel::findOrFail($id);

        $rol = session('rol'); // Recupera el rol desde la sesión

        // Validación base
        $rules = [
            'nombre_escuela' => 'required|string|max:255',
            'nivel_educativo' => 'required|string|max:255',
            'turno' => 'required|string|max:100',
            'sostenimiento' => 'required|string|max:100',
        ];

        // Solo si el rol es 'director' se permite modificar el CCT
        if (strtoupper($rol) === 'DIRECTOR') {
            $rules['cct'] = 'required|string|max:20|unique:planteles,cct,' . $plantel->id . ',id';
        }

        $validated = $request->validate($rules);

        // Campos que se pueden actualizar
        $camposActualizables = [
            'nombre_escuela',
            'nivel_educativo',
            'turno',
            'sostenimiento',
        ];

        if (strtoupper($rol) === 'DIRECTOR') {
            $camposActualizables[] = 'cct';
        }

        $plantel->update($request->only($camposActualizables));
        //  Asignar usuario manualmente a la auditoría
        $usuarioId = session('id');
        $usuario = $usuarioId ? Usuario::find($usuarioId) : null;

        if ($usuario) {
            $auditoria = Audit::where('auditable_type', Plantel::class)
                ->where('auditable_id', $plantel->id)
                ->latest()
                ->first();

            if ($auditoria) {
                $auditoria->user_id = $usuario->id;
                $auditoria->user_type = Usuario::class;
                $auditoria->save();
            }
        }

        return redirect()->back()->with('success', 'Ficha base actualizada correctamente.');
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

    //Editar proteccion civil
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
    //Guardar cambios en proteccion civil
    public function guardarProteccionCivil(Request $request, $cct)
    {
        $plantel = Plantel::where('cct', $cct)->firstOrFail();
        $detalle = $plantel->detalleProteccionCivil;
        $seccion = $request->input('seccion');

        $validaciones = [];
        $campos = [];

        switch ($seccion) {
            case 'seguridad':
                $validaciones = [
                    'programa_interno_pc' => 'nullable|boolean',
                    'alarma_sismica' => 'nullable|boolean',
                    'alarma_sismica_funcional' => 'nullable|boolean',
                    'senaletica_estado' => 'required|string',
                    'extintores_cantidad' => 'required|integer|min:0',
                    'extintores_vigentes' => 'required|integer|min:0',
                    'extintores_ultima_recarga' => 'nullable|date',
                    'programa_interno_pc_fecha' => 'nullable|date',
                ];
                break;

            case 'brigadas':
                $validaciones = [
                    'brigadas_conformadas' => 'nullable|boolean',
                    'botiquin_existencia' => 'nullable|boolean',
                    'botiquin_estado' => 'nullable|string',
                    'simulacros_ultimo_anio' => 'required|integer|min:0',
                ];
                break;

            case 'observaciones':
                $validaciones = [
                    'observaciones' => 'nullable|string|max:2000',
                ];
                break;
        }

        $data = $request->validate($validaciones);

        if (!$detalle) {
            $data['cct'] = $cct;
            $detalle = new \App\Models\DetalleProteccionCivil($data);
            $detalle->save();
        } else {
            $detalle->fill($data)->save();
        }

        return back()->with('success', 'Sección de protección civil guardada correctamente');
    }


    //Controlador para buscador dinamico para planteles 
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

        $planteles = $query->limit(20)->get();

        return response()->json([
            'html' => view('partials.lista', compact('planteles'))->render()
        ]);
    }

    //Filtro de planteles segun el estatus 
    public function filtrarEstatus(Request $request)
    {
        $estatus = $request->input('estatus');

        //Validar si el estado es valido
        if (!in_array($estatus, ['ACTIVO', 'INACTIVO', 'EN_REVISION'])) {
            return redirect()->route('planteles.index')
                ->with('error', 'Estatus no valido');
        }

        $planteles = Plantel::with('municipio', 'director')
            ->where('estatus_plantel', $estatus)
            ->get();

        return view('planteles.index', compact('planteles', 'estatus'));
    }

    //historial de cambios
    public function mostrarAuditorias($id)
    {
        $plantel = Plantel::with('auditorias.user')->findOrFail($id);
        return view('planteles.auditorias', compact('plantel'));
    }
}
