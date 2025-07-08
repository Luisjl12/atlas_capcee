<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use Illuminate\Http\Request;
use App\Models\Plantel;
use App\Models\Localidad;
use App\Models\Corde;
use App\Models\Usuario;

class PlantelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $planteles = Plantel::all();
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
            'id_municipio' => 'required|exists:municipios,id',
            'id_localidad' => 'required|exists:localidades,id',
            'id_corde' => 'required|exists:cordes,id',
            'telefono_plantel' => 'required',
            'correo_institucional' => 'required',
            'nombre_director_registrado' => 'required',
            'id_director_asignado' => 'required|exists:usuarios,id',
            'accesibilidad_otros' => 'nullable|string|max:255',
        ]);

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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
