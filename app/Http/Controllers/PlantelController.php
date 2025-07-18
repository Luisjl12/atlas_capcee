<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use Illuminate\Http\Request;
use App\Models\Plantel;
use App\Models\Localidad;
use App\Models\Corde;
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
        $planteles = Plantel::with(['municipio', 'director'])->get();
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
        $request->merge([
            'accesibilidad_rampas' => $request->has('accesibilidad_rampas') ? 1 : 0,
            'accesibildad_banos_adaptados' => $request->has('accesibilidad_banos_adpatados') ? 1 : 0,
            'accesibilidad_sanaletica_braille' => $request->has('accesibilidad_sanaletica_braille') ? 1 : 0,
        ]);

        // Crear o usar municipio
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

        // Validación final con los ids definitivos
        $request->validate([
            'cct' => 'required|unique:planteles',
            'nombre_escuela' => 'required',
            'nivel_educativo' => 'required',
            'turno' => 'required',
            'sostenimiento' => 'required',
            'domicilio_calle_numero' => 'required',
            'domicilio_colonia' => 'required',
            'domicilio_cp' => 'required',
            'latitud' => 'required',
            'longitud' => 'required',
            'telefono_plantel' => 'required',
            'correo_institucional' => 'required',
            'nombre_director_registrado' => 'required',
            'id_director_asignado' => 'required|exists:usuarios,id',
            'accesibilidad_otros' => 'nullable|string|max:255',
            'total_alumnos' => 'required',
            'total_docentes' => 'required',
            'total_administrativos' => 'required',
            'estatus_plantel' => 'required|in:ACTIVO,INACTIVO,EN_REVISION',
            'id_municipio' => 'required|exists:municipios,id',
            'id_localidad' => 'required|exists:localidades,id',
            'id_corde' => 'required|exists:cordes,id',
        ]);

        // Crear Plantel
        Plantel::create($request->all());

        return redirect()->route('planteles.index')->with('success', 'Datos agregados correctamente.');
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
        return view('planteles.show', compact('plantel', 'hidrosanitario', 'servicio', 'estadosConservacion'));
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

        $request->merge([
            'accesibilidad_rampas' => $request->has('accesibilidad_rampas') ? 1 : 0,
            'accesibilidad_banos_adaptados' => $request->has('accesibilidad_banos_adpatados') ? 1 : 0,
            'accesibilidad_sanaletica_braille' => $request->has('accesibilidad_sanaletica_braille') ? 1 : 0,
        ]);

        $request->validate([
            'cct' => 'required|unique:planteles,cct,' . $plantel->id,
            'nombre_escuela' => 'required',
            'nivel_educativo' => 'required',
            'turno' => 'required',
            'sostenimiento' => 'required',
            'domicilio_calle_numero' => 'required',
            'domicilio_colonia' => 'required',
            'domicilio_cp' => 'required',
            'latitud' => 'required',
            'longitud' => 'required',
            'telefono_plantel' => 'required',
            'correo_institucional' => 'required',
            'nombre_director_registrado' => 'required',
            'id_director_asignado' => 'required|exists:usuarios,id',
            'accesibilidad_otros' => 'nullable|string|max:255',
            'total_alumnos' => 'required',
            'total_docentes' => 'required',
            'total_administrativos' => 'required',
            'estatus_plantel' => 'required|in:ACTIVO,INACTIVO,EN_REVISION',

            'id_municipio' => 'required_without:nuevo_municipio|exists:municipios,id',
            'nuevo_municipio' => 'required_without:id_municipio|string|max:255',

            'id_localidad' => 'required_without:nuevo_localidad|exists:localidades,id',
            'nuevo_localidad' => 'required_without:id_localidad|string|max:255',

            'id_corde' => 'required_without:nuevo_corde|exists:cordes,id',
            'nuevo_corde' => 'required_without:id_corde|string|max:255',
        ]);

        if ($request->filled('nuevo_municipio')) {
            $nombreMunicipio = trim(Str::title($request->nuevo_municipio));
            $municipio = Municipio::firstOrCreate(['nombre_municipio' => $nombreMunicipio]);
            $municipio_id = $municipio->id;
        } else {
            $municipio_id = $request->id_municipio;
        }

        if ($request->filled('nuevo_localidad')) {
            $nombreLocalidad = trim(Str::title($request->nuevo_localidad));
            $localidad = Localidad::firstOrCreate([
                'nombre_localidad' => $nombreLocalidad,
                'municipio_id' => $municipio_id,
            ]);
            $localidad_id = $localidad->id;
        } else {
            $localidad_id = $request->id_localidad;
        }

        if ($request->filled('nuevo_corde')) {
            $nombreCorde = trim(Str::title($request->nuevo_corde));
            $corde = Corde::firstOrCreate(['nombre_corde' => $nombreCorde]);
            $corde_id = $corde->id;
        } else {
            $corde_id = $request->id_corde;
        }

        $request->merge([
            'id_municipio' => $municipio_id,
            'id_localidad' => $localidad_id,
            'id_corde' => $corde_id,
        ]);

        $plantel->update($request->all());

        return redirect()->route('planteles.index')->with('success', 'Plantel actualizado correctamente.');
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
}
