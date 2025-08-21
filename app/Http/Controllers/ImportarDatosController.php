<?php

namespace App\Http\Controllers;

use App\Models\ArchivosPlantel;
use App\Models\Plantel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ImportarDatosController extends Controller
{
    public function index()
    {
        return view('importar_datos.index');
    }

    public function store(Request $request)
    {
        // 📌 Validación del archivo
        $request->validate([
            'archivo' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        // 📥 Datos del archivo
        $archivo = $request->file('archivo');
        $nombreOriginal = $archivo->getClientOriginalName();
        $extension = $archivo->getClientOriginalExtension();
        $mimeType = $archivo->getClientMimeType();
        $tamano = $archivo->getSize();
        $nombreSistema = uniqid() . '_' . $nombreOriginal;
        $ruta = $archivo->storeAs('public/archivos_plantel', $nombreSistema);

        // 📄 Leer contenido CSV
        $contenido = file_get_contents($archivo->getRealPath());
        $lineas = array_filter(explode(PHP_EOL, $contenido), fn($linea) => trim($linea) !== '');
        $csv = array_map('str_getcsv', $lineas);

        if (empty($csv) || count($csv) < 2) {
            return redirect()->back()->withErrors(['archivo' => 'El archivo CSV está vacío o mal formado.']);
        }

        // ✅ Validar encabezados
        $encabezados = $csv[0];
        $camposEsperados = ['CCT', 'NOMBRE_ESCUELA', 'ID_MUNICIPIO', 'ID_CORDE'];
        $faltantes = array_diff($camposEsperados, $encabezados);

        if (!empty($faltantes)) {
            return redirect()->back()->withErrors([
                'archivo' => 'Faltan encabezados requeridos: ' . implode(', ', $faltantes)
            ]);
        }

        // ⚠️ Validar y procesar filas
        $errores = [];
        foreach ($csv as $index => $fila) {
            if ($index === 0) continue; // Saltar encabezados

            $datos = array_combine($encabezados, $fila);
            $cct = trim($datos['CCT'] ?? '');
            $nombreEscuela = trim($datos['NOMBRE_ESCUELA'] ?? '');
            $idMunicipio = $datos['ID_MUNICIPIO'] ?? null;
            $idCorde = $datos['ID_CORDE'] ?? null;

            // Validar campos obligatorios
            $camposFaltantes = [];
            foreach ($camposEsperados as $campo) {
                if (empty($datos[$campo])) {
                    $camposFaltantes[] = $campo;
                }
            }

            if (!empty($camposFaltantes)) {
                $errores[] = "Línea " . ($index + 1) . ": faltan los campos " . implode(', ', $camposFaltantes);
                continue;
            }

            // 🏫 Crear plantel si no existe
            Plantel::firstOrCreate(
                ['cct' => $cct],
                [
                    'nombre_escuela' => trim($datos['NOMBRE_ESCUELA']),
                    'id_municipio' => $datos['ID_MUNICIPIO'],
                    'id_corde' => $datos['ID_CORDE'],
                ]
            );
        }

        // 🗂️ Guardar archivo en base de datos
        $tipoDocumento = $request->tipo_documento === 'Otro'
            ? $request->otro_tipo
            : $request->tipo_documento;

        ArchivosPlantel::create([
            'cct' => $csv[1][array_search('CCT', $encabezados)],
            'nombre_archivo_original' => $nombreOriginal,
            'nombre_archivo_sistema' => $nombreSistema,
            'ruta_archivo' => $ruta,
            'tipo_documento' => $tipoDocumento,
            'descripcion' => $request->descripcion,
            'fecha_subido' => Carbon::now(),
            'mime_type' => $mimeType,
            'tamano_byte' => $tamano,
            'id_usuario_subio' => session('id'),
            'fecha_actualizacion_seccion' => Carbon::now(),
        ]);

        return redirect()->back()->with([
            'success' => 'Archivo CSV subido correctamente.' . ($errores ? ' Se detectaron ' . count($errores) . ' advertencias.' : ''),
            'errores_csv' => $errores,
        ]);
    }
}
