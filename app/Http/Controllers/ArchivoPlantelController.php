<?php

namespace App\Http\Controllers;

use App\Models\ArchivosPlantel;
use App\Models\Plantel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ArchivoPlantelController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|max:10240',
            'tipo_documento' => 'required|string',
            'otro_tipo' => 'nullable|required_if:tipo_documento,Otro|string',
            'descripcion' => 'nullable|string',
            'cct' => 'required|string|exists:planteles,cct',
        ]);

        $archivo = $request->file('archivo');

        $nombreOriginal = $archivo->getClientOriginalName();
        $tipoDocumento = $request->tipo_documento === 'Otro'
            ? $request->otro_tipo
            : $request->tipo_documento;
        $mimeType = $archivo->getClientMimeType();
        $tamano = $archivo->getSize();
        $nombreSistema = uniqid() . '_' . $nombreOriginal;

        $ruta = $archivo->storeAs('public/archivos_plantel', $nombreSistema);

        ArchivosPlantel::create([
            'cct' => $request->cct,
            'nombre_archivo_original' => $nombreOriginal,
            'nombre_archivo_sistema' => $nombreSistema,
            'ruta_archivo' => $ruta,
            'tipo_documento' => $tipoDocumento,
            'descripcion' => $request->descripcion,
            'fecha_subido' => now(),
            'mime_type' => $mimeType,
            'tamano_byte' => $tamano,
            'id_usuario_subio' => session('id'),
            'fecha_actualizacion_seccion' => now(),
        ]);

        $plantel = Plantel::where('cct', $request->cct)->first();
        return redirect()->route('planteles.show', ['id' => $request->id_plantel])
            ->with('success', 'Archivo subido correctamente.');
    }
    public function show($id)
    {
        $plantel = Plantel::findOrFail($id);
        $archivos = ArchivosPlantel::where('cct', $plantel->cct)->get();

        return view('planteles.show', compact('plantel', 'archivos'));
    }
    public function descargar($id)
    {
        $archivo = ArchivosPlantel::findOrfail($id);

        if (!Storage::exists($archivo->ruta_archivo)) {
            return redirect()->back()->with('Error', 'Archivo no encontrado');
        }

        return Storage::download($archivo->ruta_archivo, $archivo->nombre_archivo_original);
    }
    public function destroy($id)
    {
        $archivo = ArchivosPlantel::findOrFail($id);

        if (Storage::exists($archivo->ruta_archivo)) {
            Storage::delete($archivo->ruta_archivo);
        }

        $archivo->delete();

        return redirect()->back()->with('success', 'Archivo eliminador correctamente');
    }
}
