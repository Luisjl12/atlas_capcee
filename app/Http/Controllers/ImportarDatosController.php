<?php

namespace App\Http\Controllers;

use App\Models\ArchivosPlantel;
use App\Models\Plantel;
use App\Models\Municipio;
use App\Models\Corde;
use App\Models\Localidad;
use Illuminate\Http\Request;
use App\Models\InmuebleNivel;
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


            //Nivel educativo
            $nivelesEducativos = [
                'inicial' => 'INMUEBLE IMPARTE EDUCACION INICIAL',
                'preescolar' => 'INMUEBLE IMPARTE EDUCACION PREESCOLAR',
                'primaria' => 'INMUEBLE IMPARTE EDUCACION PRIMARIA',
                'secundaria' => 'IMPARTE_EDUCACION_SECUNDARIA',
                'formacion_trabajo' => 'INMUEBLE IMPARTE EDUCACION FORMACION PARA EL TRABAJO',
                'bachillerato_general' => 'INMUEBLE IMPARTE EDUCACION BACHILLERATO GENERAL',
                'bachillerato_tecnologico' => 'INMUEBLE IMPARTE EDUCACION BACHILLERATO TECNOLOGICO O QUIVALENTE',
                'tecnico_profesional' => 'INMUEBLE IMPARTE EDUCACION TECNICO LICENCIATURA',
                'tecnico_licenciatura' => 'INMUEBLE IMPARTE EDUCACION TECNICO LICENCIATURA',
                'tecnico_posgrado' => 'INMUEBLE IMPARTE EDUCACION TECNICO POSGRADO',
            ];




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
            //////
            $nombreCordeNormalizado = $this->normalizarNombreCorde($nombreCorde);
            $corde = Corde::where('nombre_corde', $nombreCordeNormalizado)->first();
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

            $nivelesImpartidos = [];

            foreach ($nivelesEducativos as $nivel => $encabezado) {
                $valorRaw = strtolower(trim($datos[$encabezado] ?? ''));
                $imparte = in_array($valorRaw, ['sí', 'si', 'true', '1']) ? true : false;

                $nivelesImpartidos[] = [
                    'cct' => $datos['CCT'] ?? null,
                    'nivel' => $nivel,
                    'imparte' => $imparte,
                ];
            }

            // Buscar o crear municipio y corde
            $municipio = Municipio::firstOrCreate(['nombre_municipio' => $nombreMunicipio]);

            //Buscar o crear localidad 
            $localidad = Localidad::firstOrCreate([
                'nombre_localidad' => $nombreLocalidad,
                'municipio_id' => $municipio->id,
            ]);




            // Datos obligatorios (que siempre deben existir en tu archivo)
            $datosPlantel = [
                'cct' => $cct,
                'nombre_escuela' => $nombreEscuela,
                'id_municipio' => $municipio->id,
                'id_corde' => $corde->id,
            ];


            // Lista de campos opcionales que solo se actualizan si hay encabezado y valor
            $camposOpcionales = [
                'nivel_educativo' => $nivelEductivo ?? null,
                'turno' => $turno ?? null,
                'sostenimiento' => $sostenimiento ?? null,
                'domicilio_calle_numero' => $domicilioCalleNumero ?? null,
                'domicilio_colonia' => $domicilioColonia ?? null,
                'domicilio_cp' => $domicilioCp ?? null,
                'telefono_plantel' => $telefonoPlantel ?? null,
                'correo_institucional' => $correoInstitucional ?? null,
                'nombre_director_registrado' => $directorAsignado ?? null,
                'total_alumnos' => is_numeric($totalAlumnos) ? intval($totalAlumnos) : null,
                'total_docentes' => is_numeric($totalDocentes) ? intval($totalDocentes) : null,
                'total_administrativos' => is_numeric($totalAdministrativos) ? intval($totalAdministrativos) : null,
                'id_localidad' => $localidad->id ?? null,
                'accesibilidad_rampas' => $accesibilidadRampas ?? null,
                'accesibilidad_banos_adaptados' => $accesibilidadBanosAdaptados ?? null,
                'accesibilidad_sanaletica_braille' => $accesibilidadSenaleticaBraile ?? null,
                'accesibilidad_otros' => $AccesibilidadOtros ?? null,
                'estatus_plantel' => $estatusPlantel ?? null,
                'latitud' => (is_numeric($latitud) ? $latitud : null),
                'longitud' => (is_numeric($longitud) ? $longitud : null),
            ];

            // Solo agregar los campos que vienen con valor
            foreach ($camposOpcionales as $campo => $valor) {
                if (!is_null($valor) && $valor !== '') {
                    $datosPlantel[$campo] = $valor;
                }
            }

            $plantel = Plantel::updateOrCreate(
                ['cct' => $cct],
                $datosPlantel
            );

            // Crear o actualizar
            if ($plantel->wasRecentlyCreated) {
                $nuevos[] = $cct;
            } else {
                $actualizados[] = $cct;
            }

            foreach ($nivelesImpartidos as $nivelData) {
                if (!$nivelData['cct']) {
                    continue; // Evitar registros sin CCT
                }

                InmuebleNivel::updateOrCreate(
                    [
                        'cct' => $nivelData['cct'],
                        'nivel' => $nivelData['nivel'],
                    ],
                    [
                        'imparte' => $nivelData['imparte'],
                    ]
                );
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


    // Normalizar nombres
    private function normalizarNombreCorde($nombreCorde)
    {
        $nombre = ucwords(strtolower(trim($nombreCorde)));

        // Diccionario de equivalencias
        $equivalencias = [
            'Acatatlan' => 'Acatlán de Osorio',
            'Acatatlán' => 'Acatlán de Osorio',
            'San Pedro' => 'Cholula',
            'Cholula' => 'Cholula',
            'Tepexi de Rodríguez' => 'Tepexi',
            'Tepexi De Rodríguez' => 'Tepexi',
            'Tepexi De RodrÍguez' => 'Tepexi', // con tilde raro de Excel
            'Tepexi De RodríGuez' => 'Tepexi',


        ];

        // Si existe en equivalencias, usar el oficial
        if (isset($equivalencias[$nombre])) {
            return $equivalencias[$nombre];
        }

        // Si no hay equivalencia exacta, intenta buscar coincidencias parciales
        $corde = Corde::where('nombre_corde', 'LIKE', "%$nombre%")->first();
        if ($corde) {
            return $corde->nombre_corde;
        }

        return $nombre; // fallback: regresa el mismo
    }
}
