<?php

namespace App\Http\Controllers;

use App\Models\ArchivosPlantel;
use App\Models\Plantel;
use App\Models\Municipio;
use App\Models\Corde;
use App\Models\Localidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

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
            'archivo' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
        ]);

        // Datos del archivo
        $archivo = $request->file('archivo');
        $nombreOriginal = $archivo->getClientOriginalName();
        $nombreSistema = uniqid() . '_' . $nombreOriginal;
        $ruta = $archivo->storeAs('archivos_plantel', $nombreSistema, 'public');
        $mimeType = $archivo->getClientMimeType();
        $tamano = $archivo->getSize();

        // Leer contenido CSV
        $extension = $archivo->getClientOriginalExtension();

        if (in_array($extension, ['xlsx', 'xls'])) {
            $data = Excel::toArray([], $archivo)[0]; // Primera hoja
        } else {
            $contenido = file_get_contents($archivo->getRealPath());
            if (substr($contenido, 0, 3) === "\xEF\xBB\xBF") {
                $contenido = substr($contenido, 3);
            }
            $contenido = mb_convert_encoding($contenido, 'UTF-8', 'auto');
            $lineas = array_filter(explode(PHP_EOL, $contenido), fn($linea) => trim($linea) !== '');
            $data = array_map('str_getcsv', $lineas);
        }


        if (empty($data) || count($data) < 2) {
            return redirect()->back()->withErrors(['archivo' => 'El archivo CSV está vacío o mal formado.']);
        }

        // Validar encabezados
        $encabezados = $data[0];
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
        $estatusValidos = ['activo', 'inactividad', 'en_revision'];
        $estatusPorDefecto = 'en_revision';

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

        foreach ($data as $index => $fila) {
            if ($index === 0) continue;

            $datos = array_combine($encabezados, $fila);
            $cct = trim($datos['CCT'] ?? '');
            $nombreEscuela = trim($datos['NOMBRE_ESCUELA'] ?? '');
            $nombreMunicipio = ucwords(strtolower(trim($datos['NOMBRE_MUNICIPIO'] ?? '')));
            $nombreCorde = ucwords(strtolower(trim($datos['NOMBRE_CORDE'] ?? '')));

            //Localidad/no obligatorio 
            $nombreLocalidad = ucwords(strtolower(trim($datos['NOMBRE_LOCALIDAD'] ?? '')));

            // Normalizar campos opcionales
            $nivelRaw = strtolower(trim($datos['NIVEL_EDUCATIVO'] ?? ''));
            $turnoRaw = strtolower(trim($datos['TURNO'] ?? ''));
            $sostenimientoRaw = strtolower(trim($datos['SOSTENIMIENTO'] ?? ''));

            $rampasRaw = strtolower(trim($datos['RAMPAS'] ?? ''));
            $accesibilidadRampas = match ($rampasRaw) {
                'si' => 1,
                'no' => 0,
                default => 0,
            };

            $banosAdaptadosRaw = strtolower(trim($datos['BANOS_ADAPTADOS'] ?? ''));
            $accesibilidadBanosAdaptados = match ($banosAdaptadosRaw) {
                'si' => 1,
                'no' => 0,
                default => 0,
            };

            $senaleticaBraileRaw = strtolower(trim($datos['SANALETICA_BRAILE'] ?? ''));
            $accesibilidadSenaleticaBraile = match ($senaleticaBraileRaw) {
                'si' => 1,
                'no' => 0,
                default => 0,
            };


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

            //Comentarios en accesibilidad 
            $AccesibilidadOtros = trim($datos['OTROS_ACCESIBILIDAD'] ?? '');

            //Estatus
            $estatusRaw = strtolower(trim($datos['ESTATUS'] ?? ''));
            $estatusPlantel = in_array($estatusRaw, $estatusValidos) ? $estatusRaw : $estatusPorDefecto;



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

            if ($rampasRaw && !in_array($rampasRaw, ['si', 'no'])) {
                $errores[] = "Línea " . ($index + 1) . ": valor inválido en RAMPAS: '$rampasRaw'. Solo se permite 'si' o 'no'.";
                continue;
            }

            if ($banosAdaptadosRaw && !in_array($banosAdaptadosRaw, ['si', 'no'])) {
                $errores[] = "Linea " . ($index + 1) . ": valor invalido en baños adaptados '$banosAdaptadosRaw'. Solo se permite 'si' o 'no'.";
                continue;
            }

            if ($senaleticaBraileRaw && !in_array($senaleticaBraileRaw, ['si', 'no'])) {
                $errores[] = "Linea " . ($index + 1) . ": valor invalido en señaletica braile '$senaleticaBraileRaw'. Solo se permite 'si' o 'no'.";
                continue;
            }

            if ($estatusRaw && !in_array($estatusRaw, $estatusValidos)) {
                $errores[] = "Linea " . ($index + 1) . ": estatus inválido: '$estatusRaw'. Solo se permite: activo, inactivo, en_revision.";
                continue;
            }



            // Buscar o crear municipio y corde
            $municipio = Municipio::firstOrCreate(['nombre_municipio' => $nombreMunicipio]);

            //Buscar o crear localidad 
            $localidad = Localidad::firstOrCreate([
                'nombre_localidad' => $nombreLocalidad,
                'municipio_id' => $municipio->id,
            ]);


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
                    'id_localidad' => $localidad->id,
                    'accesibilidad_rampas' => $accesibilidadRampas,
                    'accesibilidad_banos_adaptados' => $accesibilidadBanosAdaptados,
                    'accesibilidad_sanaletica_braille' => $accesibilidadSenaleticaBraile,
                    'accesibilidad_otros' => $AccesibilidadOtros,
                    'estatus_plantel' => $estatusPlantel,
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

        $cctArchivo = trim($data[1][array_search('CCT', $encabezados)] ?? '');

        if (empty($cctArchivo)) {
            return redirect()->back()->withErrors([
                'archivo' => 'No se puede registrar el archivo porque el campo CCT está vacío en la primera fila.'
            ]);
        }

        ArchivosPlantel::create([
            'cct' => $data[1][array_search('CCT', $encabezados)],
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
