<?php

namespace App\Http\Controllers;

use App\Models\GaleriaFotos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Plantel;

class GaleriaFotoController extends Controller
{
    public function create($cct)
    {
        return view('galeria.create', compact('cct'));
    }

    public function store(Request $request, $cct)
    {
        $request->validate([
            'foto' => 'required|mimetypes:image/jpeg,image/png,image/webp|max:6500', // 6500 KB = 5 MB
            'descripcion' => 'nullable|string|max:255'
        ]);
        $foto = $request->file('foto');
        $nombreOriginal = $foto->getClientOriginalName();
        $nombreSistema = uniqid() . '_' . $nombreOriginal;
        $ruta = $foto->storeAs("galeria/$cct", $nombreSistema, 'public');

        GaleriaFotos::create([
            'cct' => $cct,
            'id_espacios' => null,
            'nombre_foto_original' => $nombreOriginal,
            'nombre_foto_sistema' => $nombreSistema,
            'ruta_foto' => $ruta,
            'descripcion_foto' => $request->descripcion,
            'id_usuario_subio' => session('id'),
        ]);

        $plantel = Plantel::where('cct', $cct)->firstOrFail();
        return redirect()->route('planteles.show', $plantel->id)->with('foto_subida', $ruta);
    }
    public function destroy(GaleriaFotos $foto)
    {
        if (Storage::disk('public')->exists($foto->ruta_foto)) {
            Storage::disk('public')->delete($foto->ruta_foto);
        }

        $foto->delete();

        return back()->with('success', 'Foto eliminada correctamente. ');
    }

    public function eliminarSeleccionadas(Request $request)
    {
        $ids = $request->input('ids');

        if (!is_array($ids) || empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No se recibieron IDs válidos']);
        }

        $fotos = GaleriaFotos::whereIn('id', $ids)->get();

        foreach ($fotos as $foto) {
            if (Storage::disk('public')->exists($foto->ruta_foto)) {
                Storage::disk('public')->delete($foto->ruta_foto);
            }
            $foto->delete();
        }

        return response()->json(['success' => true, 'message' => 'Fotos eliminadas correctamente']);
    }
}
