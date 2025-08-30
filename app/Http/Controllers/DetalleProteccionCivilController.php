
<?php
/* 

CONTROLADOR SIN USO, SE MOVIO DENTRO DE PlantelController la gestion de proteccion civil
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetalleProteccionCivil;

class DetalleProteccionCivilController extends Controller
{
    public function show($cct)
    {
        $detalle = DetalleProteccionCivil::with('plantel')->where('cct', $cct)->firstOrFail();
        return view('detalle_proteccion_civil.show', compact('detalle'));
    }

    public function edit($cct)
    {
        $detalle = DetalleProteccionCivil::where('cct', $cct)->firstOrFail();
        return view('detalle_proteccion_civil.edit', compact('detalle'));
    }

    public function update(Request $request, $cct)
    {
        $detalle = DetalleProteccionCivil::where('cct', $cct)->firstOrFail();


        // Validación
        $validated = $request->validate([
            'programa_interno_pc' => 'required|boolean',
            'alarma_sismica' => 'required|boolean',
            'alarma_sismica_funcional' => 'required|boolean',
            'extintores_vigentes' => 'required|boolean',
            'botiquin_existencia'=> 'required|boolean', 
            'programa_interno_pc_fecha' => 'nullable|date',
            'senaletica_estado' => 'required|string',
            'extintores_cantidad' => 'required|integer|min:0',
            'extintores_vigentes' => 'required|integer|min:0',   // ← corregido
            'extintores_ultima_recarga' => 'nullable|date',
            'programa_interno_pc_fecha' => 'nullable|date',
            'botiquin_estado' => 'nullable|string',
            'simulacros_ultimo_anio' => 'nullable|integer|min:0',
            'observaciones' => 'nullable|string|max:2000',
        ]);

        // Manejo de checkboxes (si no están marcados, asignar 0)
        $validated['programa_interno_pc'] = $request->has('programa_interno_pc') ? 1 : 0;
        $validated['alarma_sismica'] = $request->has('alarma_sismica') ? 1 : 0;
        $validated['alarma_sismica_funcional'] = $request->has('alarma_sismica_funcional') ? 1 : 0;
        $validated['brigadas_conformadas'] = $request->has('brigadas_conformadas') ? 1 : 0;
        $validated['botiquin_existencia'] = $request->has('botiquin_existencia') ? 1 : 0;

        // Actualizar
        $detalle->update($validated);

        return redirect()->route('planteles.show', $detalle->cct)
            ->with('success', 'Información de protección civil actualizada correctamente');
    }
}
