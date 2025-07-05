<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use Illuminate\Http\Request;
use App\Models\Plantel;
use App\Models\Localidad;
use App\Models\Corde;

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

        return view('planteles.create', compact('municipios', 'localidades', 'cordes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
        ]);

        Plantel::create($request->all());

        return redirect()->route('planteles.index')->with('success', 'Datos agregados correctamente.');
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
