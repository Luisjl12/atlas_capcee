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

class ImportarDatosController extends Controller
{
    public function index()
    {
        return view('importar_datos.index');
    }

    public function store(Request $request)
    {
        // Validación del archivo
        $request->validate([
            'archivo' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        // Datos del archivo
        $archivo = $request->file('archivo');
        $nombreOriginal = $archivo->getClientOriginalName();
        $nombreSistema = uniqid() . '_' . $nombreOriginal;
        $ruta = $archivo->storeAs('public/archivos_plantel', $nombreSistema);
        $mimeType = $archivo->getClientMimeType();
        $tamano = $archivo->getSize();

        // Leer contenido CSV
        $contenido = file_get_contents($archivo->getRealPath());
        if (substr($contenido, 0, 3) === "\xEF\xBB\xBF") {
            $contenido = substr($contenido, 3);
        }
        $contenido = mb_convert_encoding($contenido, 'UTF-8', 'auto');
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

        // Valores válidos y equivalencias
        $nivelesValidos = ['preescolar', 'primaria', 'secundaria', 'media superior', 'superior'];
        $turnosValidos = ['matutino', 'vespertino'];
        $sostenimientosValidos = ['federal', 'municipal', 'local', 'particular'];

        $nivelMap = [
            'preescolar' => 'preescolar',
            'pre escolar' => 'preescolar',
            'primaria' => 'primaria',
            'secundaria' => 'secundaria',
            'media superior' => 'media superior',
            'bachillerato' => 'media superior',
            'superior' => 'superior',
        ];

        $turnoMap = [
            'matutino' => 'matutino',
            'matutina' => 'matutino',
            'vespertino' => 'vespertino',
            'vespertina' => 'vespertino',
        ];

        $sostenimientoMap = [
            'federal' => 'federal',
            'municipal' => 'municipal',
            'local' => 'local',
            'particular' => 'particular',
            'privado' => 'particular',
        ];

        // Procesar filas
        $errores = [];
        $nuevos = [];
        $actualizados = [];

        foreach ($csv as $index => $fila) {
            if ($index === 0) continue;

            $datos = array_combine($encabezados, $fila);
            $cct = trim($datos['CCT'] ?? '');
            $nombreEscuela = trim($datos['NOMBRE_ESCUELA'] ?? '');
            $nombreMunicipio = ucwords(strtolower(trim($datos['NOMBRE_MUNICIPIO'] ?? '')));
            $nombreCorde = ucwords(strtolower(trim($datos['NOMBRE_CORDE'] ?? '')));

            // Normalizar campos opcionales
            $nivelRaw = strtolower(trim($datos['NIVEL_EDUCATIVO'] ?? ''));
            $turnoRaw = strtolower(trim($datos['TURNO'] ?? ''));
            $sostenimientoRaw = strtolower(trim($datos['SOSTENIMIENTO'] ?? ''));

            $nivelEductivo = $nivelMap[$nivelRaw] ?? null;
            $turno = $turnoMap[$turnoRaw] ?? null;
            $sostenimiento = $sostenimientoMap[$sostenimientoRaw] ?? null;

            $domicilioCalleNumero = trim($datos['DOMICILIO_CALLE_NUMERO'] ?? '');
            $domicilioColonia = trim($datos['DOMICILIO_COLONIA'] ?? '');
            $domicilioCp = trim($datos['DOMICILIO_CP'] ?? '');
            $latitud = trim($datos['LATITUD'] ?? '');
            $longitud = trim($datos['LONGITUD'] ?? '');

            //Contacto
            $telefonoPlantel = trim($datos['TELEFONO_PLANTEL'] ?? '');
            $correoInstitucional = trim($datos['CORREO_INSTITUCIONAL'] ?? '');
            $directorAsignado = trim($datos['NOMBRE_DIRECTOR_REGISTRADO'] ?? '');

            //Numero de alumnos, docentes y administrativos
            $totalAlumnos = trim($datos['TOTAL_ALUMNOS'] ?? '');
            $totalDocentes = trim($datos['TOTAL_DOCENTES'] ?? '');
            $totalAdministrativos = trim($datos['TOTAL_ADMINISTRATIVOS'] ?? '');



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

            // Validar campos opcionales
            if ($nivelRaw && !in_array($nivelEductivo, $nivelesValidos)) {
                $errores[] = "Línea " . ($index + 1) . ": nivel educativo inválido: '$nivelRaw'.";
                continue;
            }

            if ($turnoRaw && !in_array($turno, $turnosValidos)) {
                $errores[] = "Línea " . ($index + 1) . ": turno inválido: '$turnoRaw'.";
                continue;
            }

            if ($sostenimientoRaw && !in_array($sostenimiento, $sostenimientosValidos)) {
                $errores[] = "Línea " . ($index + 1) . ": sostenimiento inválido: '$sostenimientoRaw'.";
                continue;
            }

            $corde = Corde::where('nombre_corde', $nombreCorde)->first();
            if (!$corde) {
                $errores[] = "Linea" . ($index + 1) . ": el corde '$nombreCorde' no esta registrado";
                continue;
            }

            if ($totalAlumnos !== '' && !is_numeric($totalAlumnos)) {
                $errores[] = "Línea " . ($index + 1) . ": total de alumnos inválido: '$totalAlumnos'.";
                continue;
            }


            // Buscar o crear municipio y corde
            $municipio = Municipio::firstOrCreate(['nombre_municipio' => $nombreMunicipio]);


            // Crear o actualizar plantel
            $plantel = Plantel::updateOrCreate(
                ['cct' => $cct],
                [
                    'nombre_escuela' => $nombreEscuela,
                    'id_municipio' => $municipio->id,
                    'id_corde' => $corde->id,
                    'nivel_educativo' => $nivelEductivo,
                    'turno' => $turno,
                    'sostenimiento' => $sostenimiento,
                    'domicilio_calle_numero' => $domicilioCalleNumero,
                    'domicilio_colonia' => $domicilioColonia,
                    'domicilio_cp' => $domicilioCp,
                    'latitud' => is_numeric($latitud) ? $latitud : null,
                    'longitud' => is_numeric($longitud) ? $longitud : null,
                    'telefono_plantel' => $telefonoPlantel,
                    'correo_institucional' => $correoInstitucional,
                    'nombre_director_registrado' => $directorAsignado,
                    'total_alumnos' => is_numeric($totalAlumnos) ? intval($totalAlumnos) : 0,
                    'total_docentes' => is_numeric($totalDocentes) ? intval($totalDocentes) : 0,
                    'total_administrativos' => is_numeric($totalAdministrativos) ? intval($totalAdministrativos) : 0,

                ]
            );

            if ($plantel->wasRecentlyCreated) {
                $nuevos[] = $cct;
            } else {
                $actualizados[] = $cct;
            }
        }

        // Guardar archivo en base de datos
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

        // Resumen final
        return redirect()->back()->with([
            'success' => "Importación completada. Se crearon " . count($nuevos) . " planteles y se actualizaron " . count($actualizados) . ".",
            'errores_csv' => $errores,
        ]);
    }
}
