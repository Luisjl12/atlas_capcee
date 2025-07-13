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
            'electricidad_contrato' => 'nullable|string|in:Sí,No',
            'telefonia_fija' => 'nullable|string|in:Sí,No',
            'internet_acceso' => 'nullable|string|in:Sí,No',
        ]);

        $servicio = DetalleServicio::firstOrNew(['cct' => $cct]);
        $servicio->electricidad_contrato = $validated['electricidad_contrato'] === 'Sí' ? 1 : 0;
        $servicio->telefonia_fija = $validated['telefonia_fija'] === 'Sí' ? 1 : 0;
        $servicio->internet_acceso = $validated['internet_acceso'] === 'Sí' ? 1 : 0;

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
            'fuente_agua' => 'nullable|string|max:255',
            'tipo_drenaje' => 'nullable|string|max:255',
            'hidrosanitaria' => 'nullable|string|max:255',
            'almacenamiento_agua' => 'nullable|string|max:255',
            'tipo_drena' => 'nullable|string|max:255',
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
