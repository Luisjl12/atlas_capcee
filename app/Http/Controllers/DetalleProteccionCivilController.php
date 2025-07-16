<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetalleProteccionCivil;
use App\Models\Plantel;

class DetalleProteccionCivilController extends Controller
{

    public function show($cct)
    {
        $detalle = DetalleProteccionCivil::with('plantel')->where('cct', $cct)->firstOrFail();
        return view('detalle_proteccion_civil.show', compact('detalle'));
    }
    public function edit($cct)
    {
        $detalle = DetalleProteccionCivil::where('cct', $cct)->firstOrfail();
        return view('detalle_proteccion_civil.edit', compact('detalle'));
    }
    public function update(Request $request, $cct)
    {
        $detalle = DetalleProteccionCivil::where('cct', $cct)->fisrtOrFail();
        $detalle->update($request->validate([
            'programa_interno_pc' => 'required|boolean',
            'programa_interno_pc_fecha' => 'nullable|date',
            'alarma_sismica' => 'required|boolean',
            'alarma_sismica_funcional' => 'required|boolean',
            'señaletica_estado' => 'required',
            'extintores_cantidad' => 'required|string|max:255',
            'extintores_vigente' => 'required|boolean',
            'brigadas_conformadas' => 'required|boolean',
        ]));
        return redirect()->route('planteles.show', $detalle->cct)->with('success', 'Informacion protección civil actualizada correctamente');
    }
}
