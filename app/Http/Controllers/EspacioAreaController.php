<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EspacioArea;
use App\Models\Plantel;

class EspacioAreaController extends Controller
{
    public function destroy($id)
    {
        $espacio = EspacioArea::findOrFail($id);

        $plantel = Plantel::where('cct', $espacio->cct)->firstOrFail();

        $espacio->delete();

        return redirect()->route('planteles.show', ['id' => $plantel->id, 'tab' => 1])
            ->with('success', 'Espacio eliminado correctamente.');
    }
    public function store(Request $request)
    {

        $request->validate([
            'cct' => 'required|exists:planteles,cct',
            'nombre_espacio' => 'required|string|max:255',
            'cantidad' => 'required|integer|min:1',
            'estado_conservacion' => 'required|in:BUENO,REGULAR,MALO,NO_APLICA,EN_PROCESO'

        ]);
        $plantel = Plantel::where('cct', $request->cct)->firstOrFail();

        EspacioArea::create([
            'cct' => $request->cct,
            'nombre_espacio' => $request->nombre_espacio,
            'cantidad' => $request->cantidad,
            'estado_conservacion' => $request->estado_conservacion,
        ]);

        return redirect()->route('planteles.show', ['id' => $plantel->id, 'tab' => 1])
            ->with('success', 'Espacio agregado correctamente.');
    }
}
