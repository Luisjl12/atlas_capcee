<?php

namespace App\Http\Controllers;

use App\Models\ArchivosPlantel;
use App\Models\Plantel;
use App\Models\Municipio;
use App\Models\Corde;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ImportarDatosController extends Controller
{
    public function index()
    {
        return view('importar_datos.index');
    }

    public function store(Request $request)
    {
        //  Validación del archivo
        $request->validate([
            'archivo' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        //  Datos del archivo
        $archivo = $request->file('archivo');
        $nombreOriginal = $archivo->getClientOriginalName();
        $nombreSistema = uniqid() . '_' . $nombreOriginal;
        $ruta = $archivo->storeAs('public/archivos_plantel', $nombreSistema);
        $mimeType = $archivo->getClientMimeType();
        $tamano = $archivo->getSize();

        // Leer contenido CSV
        $contenido = file_get_contents($archivo->getRealPath());
        $lineas = array_filter(explode(PHP_EOL, $contenido), fn($linea) => trim($linea) !== '');
        $csv = array_map('str_getcsv', $lineas);

        if (empty($csv) || count($csv) < 2) {
            return redirect()->back()->withErrors(['archivo' => 'El archivo CSV está vacío o mal formado.']);
        }

        // Validar encabezados
        $encabezados = $csv[0];
        $camposEsperados = ['CCT', 'NOMBRE_ESCUELA', 'NOMBRE_MUNICIPIO', 'NOMBRE_CORDE'];
        $faltantes = array_diff($camposEsperados, $encabezados);

        if (!empty($faltantes)) {
            return redirect()->back()->withErrors([
                'archivo' => 'Faltan encabezados requeridos: ' . implode(', ', $faltantes)
            ]);
        }

        //  Procesar filas
        $errores = [];
        $nuevos = [];
        $actualizados = [];

        foreach ($csv as $index => $fila) {
            if ($index === 0) continue; // Saltar encabezados

            $datos = array_combine($encabezados, $fila);
            $cct = trim($datos['CCT'] ?? '');
            $nombreEscuela = trim($datos['NOMBRE_ESCUELA'] ?? '');
            $nombreMunicipio = ucwords(strtolower(trim($datos['NOMBRE_MUNICIPIO'] ?? '')));
            $nombreCorde = ucwords(strtolower(trim($datos['NOMBRE_CORDE'] ?? '')));

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

            //  Buscar o crear municipio
            $municipio = Municipio::firstOrCreate(
                ['nombre_municipio' => $nombreMunicipio],
            );
            $idMunicipio = $municipio->id;

            //  Buscar o crear corde
            $corde = Corde::firstOrCreate(['nombre_corde' => $nombreCorde]);
            $idCorde = $corde->id;

            //  Crear o actualizar plantel
            $plantel = Plantel::updateOrCreate(
                ['cct' => $cct],
                [
                    'nombre_escuela' => $nombreEscuela,
                    'id_municipio' => $idMunicipio,
                    'id_corde' => $idCorde,
                ]
            );

            if ($plantel->wasRecentlyCreated) {
                $nuevos[] = $cct;
            } else {
                $actualizados[] = $cct;
            }
        }

        //  Guardar archivo en base de datos
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

        //  Resumen final
        return redirect()->back()->with([
            'success' => "Importación completada. Se crearon " . count($nuevos) . " planteles y se actualizaron " . count($actualizados) . ".",
            'errores_csv' => $errores,
        ]);
    }
}
