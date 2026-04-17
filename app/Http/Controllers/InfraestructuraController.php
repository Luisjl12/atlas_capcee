<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetalleServicio;
use App\Models\DetalleHidrosanitario;
use App\Models\Plantel;

class InfraestructuraController extends Controller
{
    public function mostrarInfraestructura($cct)
    {
        $plantel = Plantel::with(['detalleHidrosanitario', 'detalleServicio'])->where('cct', $cct)->first();
        $hidrosanitario = $plantel->detalleHidrosanitario;
        $servicio = $plantel->detalleServicio;

        return view('planteles.show', compact('plantel', 'hidrosanitario', 'servicio'));
    }

    public function editServicio($cct)
    {
        $plantel = Plantel::with(['detalleHidrosanitario', 'detalleServicio'])->where('cct', $cct)->first();
        $servicio = DetalleServicio::where('cct', $cct)->first();

        return view('planteles.editar_servicios', compact('plantel', 'servicio'));
    }

    public function updateServicios(Request $request, $cct)
    {
        $validated = $request->validate([
            'electricidad_contrato' => 'required|boolean',
            'telefonia_fija' => 'required|boolean',
            'internet_acceso' => 'required|boolean',
            'gas_tipo' => 'required|string|max:255',
            'internet_tipo' => 'required|string|max:255',
            'observaciones' => 'required|string|max:255',
        ]);

        $servicio = DetalleServicio::firstOrNew(['cct' => $cct]);
        $servicio->electricidad_contrato = $validated['electricidad_contrato'] ?? 0;
        $servicio->telefonia_fija = $validated['telefonia_fija'] ?? 0;
        $servicio->internet_acceso = $validated['internet_acceso'] ?? 0;
        $servicio->gas_tipo = $validated['gas_tipo'] ?? null;
        $servicio->internet_tipo = $validated['internet_tipo'] ?? null;
        $servicio->observaciones = $validated['observaciones'] ?? null;



        $servicio->save();

        $plantel = Plantel::where('cct', $cct)->firstOrFail();

        return redirect()->route('planteles.show', $plantel->id)
            ->with('success', 'Servicio actualizado correctamente');
    }

    public function editHidrosanitario($cct)
    {
        $plantel = Plantel::where('cct', $cct)->first();
        $hidrosanitario = DetalleHidrosanitario::where('cct', $cct)->first();

        return view('planteles.editar_hidrosanitario', compact('plantel', 'hidrosanitario'));
    }

    public function updateHidrosanitario(Request $request, $cct)
    {
        $validated = $request->validate([
            'fuente_agua' => 'required|string|max:255',
            'tipo_drenaje' => 'required|string|max:255',
            'almacenamiento_agua' => 'required|string|max:255',
            'sanitarios_hombres_wc' =>  'required|integer|min:0',
            'sanitarios_hombres_lavabos' => 'required|integer|min:0',
            'sanitarios_mujeres_wc' =>  'required|integer|min:0',
            'sanitarios_mujeres_lavabos' =>  'required|integer|min:0',
            'observaciones' => 'nullable|string|max:255',

        ]);

        $hidrosanitario = DetalleHidrosanitario::firstOrNew(['cct' => $cct]);
        $hidrosanitario->fill($validated);
        $hidrosanitario->save();

        $plantel = Plantel::where('cct', $cct)->firstOrFail();
        return redirect()->route('planteles.show', $plantel->id)
            ->with('success', 'Información hidrosanitaria actualizada correctamente');
    }

    public function editarInfraestructuraCompleta($cct)
    {
        $plantel = Plantel::where('cct', $cct)->first();
        $servicio = DetalleServicio::where('cct', $cct)->first();
        $hidrosanitario = DetalleHidrosanitario::where('cct', $cct)->first();

        return view('planteles.editar_infraestructura', compact('plantel', 'servicio', 'hidrosanitario'));
    }
}
