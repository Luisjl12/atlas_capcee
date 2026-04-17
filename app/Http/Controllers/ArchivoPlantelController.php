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
        $mimeType = $archivo->getClientMimeType();

        $nombreOriginal = $archivo->getClientOriginalName();
        $tipoDocumento = $request->tipo_documento === 'Otro'
            ? $request->otro_tipo
            : $request->tipo_documento;
        $mimeType = $archivo->getClientMimeType();
        $tamano = $archivo->getSize();
        $nombreSistema = uniqid() . '_' . $nombreOriginal;

        $ruta = $archivo->storeAs($request->cct, $nombreSistema, 'archivos_directo');


        ArchivosPlantel::create([
            'cct' => $request->cct,
            'nombre_archivo_original' => $nombreOriginal,
            'nombre_archivo_sistema' => $nombreSistema,
            'ruta_archivo' => $request->cct . '/' . $nombreSistema,
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

    public function visualizar($id)
    {

        $archivo = ArchivosPlantel::findOrFail($id);
        $ruta = Storage::disk('archivos_directo')->path($archivo->ruta_archivo);
        $visualizables = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif'];
        $mime = mime_content_type($ruta);
        $disposicion = in_array($mime, $visualizables) ? 'inline' : 'attachment';


        if (!file_exists($ruta)) {
            return redirect()->back()->with('error', 'Archivo no encontrado en el sistema');
        }

        return response()->file($ruta, [
            'Content-Type' => $mime,
            'Content-Disposition' => $disposicion . '; filename="' . $archivo->nombre_archivo_original . '"'
        ]);
    }
    public function destroy($id)
    {
        $archivo = ArchivosPlantel::findOrFail($id);

        if (Storage::exists($archivo->ruta_archivo)) {
            Storage::delete($archivo->ruta_archivo);
        }

        $archivo->delete();

        return redirect()->back()->with('success', 'Archivo eliminado correctamente');
    }
}
