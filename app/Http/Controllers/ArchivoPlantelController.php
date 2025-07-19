<?php

namespace App\Http\Controllers;

use App\Models\ArchivosPlantel;
use Illuminate\Http\Request;

class ArchivoPlantelController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|max:10240',
            'tipo_documento' => 'required|string',
            'descripcion' => 'nullable|string',
            'cct' => 'required|string|exists:planteles,cct',
        ]);

        $archivo = $request->file('archivo');

        $nombreOriginal = $archivo->getClientOriginalName();
        $mimeType = $archivo->getClientMimeType();
        $tamano = $archivo->getSize();
        $nombreSistema = uniqid() . '_' . $nombreOriginal;

        $ruta = $archivo->storeAs('public/archivos_plantel', $nombreSistema);

        ArchivosPlantel::create([
            'cct' => $request->cct,
            'nombre_archivo_original' => $nombreOriginal,
            'nombre_archivo_sistema' => $nombreSistema,
            'ruta_archivo' => $ruta,
            'tipo_documento' => $request->tipo_documento,
            'descripcion' => $request->descripcion,
            'fecha_subido' => now(),
            'mime_type' => $mimeType,
            'tamano_byte' => $tamano,
            'id_usuario_subio' => session('id'),
            'fecha_actualizacion_seccion' => now(),
        ]);
        return back()->with('success', 'Archivo subido correctamente');
    }
}
