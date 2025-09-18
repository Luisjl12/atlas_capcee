<?php

namespace App\Http\Controllers;

use App\Models\ArchivosPlantel;
use App\Models\Plantel;
use App\Models\Municipio;
use App\Models\Corde;
use App\Models\Localidad;
use App\Models\InmuebleSuperficie;
use App\Models\InmuebleAgua;
use App\Models\InmuebleDrenaje;
use App\Models\InmuebleEnergia;
use App\Models\InmuebleSanitarios;
use App\Models\InmuebleSeguridad;
use Illuminate\Http\Request;
use App\Models\InmuebleNivel;
use App\Models\InmuebleObras;
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

            $rangosSuperficie = [
                'menos_de_50' => 'MENOS DE 50 M2',
                'de_50_a_499' => 'DE 50 A 499 M2',
                'de_500_a_999' => 'DE 500 A 999 M2',
                'de_1000_a_9999' => 'DE 1000 A 9999 M2',
                'de_10000_o_mas' => 'DE 10000 M2 O MÁS',
            ];
            $agua = InmuebleAgua::updateOrCreate(
                ['cct' => $datos['CCT']],
                [
                    'agua_red_publica' => strtolower($datos['EL INMUEBLE CUENTA CON SUMINISTRO DE AGUA EN RED PUBLICA'] ?? '') === '1',
                    'agua_pozo' => strtolower($datos['EL INMUEBLE CUENTA CON SUMINISTRO DE AGUA EN POZO'] ?? '') === '1',
                    'agua_cuerpo' => strtolower($datos['EL INMUEBLE CUENTA CON SUMINISTRO DE AGUA EN CUERPOS DE AGUA'] ?? '') === '1',
                    'agua_pipas' => strtolower($datos['EL INMUEBLE CUENTA CON SUMINISTRO DE AGUA EN PIPAS'] ?? '') === '1',
                    'agua_otro' => strtolower($datos['EL INMUEBLE CUENTA CON SUMINISTRO DE AGUA EN OTRO TIPO'] ?? '') === '1',
                    'cisterna' => strtolower($datos['EL INMUEBLE CUENTA CON CISTERNA PARA ALMACENAMIENTO DE AGUA'] ?? '') === '1',
                    'tinacos' => strtolower($datos['EL INMUEBLE CUENTA CON TINACOS PARA ALMACENAMIENTO DE AGUA'] ?? '') === '1',
                    'tanque' => strtolower($datos['EL INMUEBLE CUENTA CON TANQUE PARA ALMACENAMIENTO DE AGUA'] ?? '') === '1',
                    'almacenamiento_otro' => strtolower($datos['EL INMUEBLE CUENTA CON OTRO TIPO DE ALMACENAMIENTO PARA AGUA'] ?? '') === '1',
                ]
            );

            $energia = InmuebleEnergia::updateOrCreate(
                ['cct' => $datos['CCT']],
                [
                    'energia_red_contrato' => strtolower($datos['EL INMUEBLE CUENTA CON SUMINISTRO DE ENERGIA ELECTRICA RED PUBLICA CON CONTRATO'] ?? '') === '1',
                    'energia_red_sin_contrato' => strtolower($datos['EL INMUEBLE CUENTA CON SUMINISTRO DE ENERGIA ELECTRICA RED PUBLICA SIN CONTRATO'] ?? '') === '1',
                    'energia_planta' => strtolower($datos['EL INMUEBLE CUENTA CON SUMINISTRO DE ENERGIA ELECTRICA PLANTA GENERADORA DE LUZ'] ?? '') === '1',
                    'energia_paneles_solares' => strtolower($datos['EL INMUEBLE CUENTA CON PANELES SOLARES CON BATERIA (PSB)'] ?? '') === '1',
                    'sin_energia' => strtolower($datos['EL INMUEBLE NO CUENTA CON SUMINISTRO DE ENERGIA ELECTRICA'] ?? '') === '1',
                    'gas_natural' => strtolower($datos['EL INMUEBLE CUENTA CON SUMINISTRO DE GAS NATURAL'] ?? '') === '1',
                    'gas_estacionario' => strtolower($datos['EL INMUEBLE CUENTA CON SUMINISTRO DE GAS ESTACIONARIO'] ?? '') === '1',
                    'gas_cilindro' => strtolower($datos['EL INMUEBLE CUENTA CON SUMINISTRO DE GAS EN CILINDROS'] ?? '') === '1',
                    'sin_gas' => strtolower($datos['EL INMUEBLE NO CUENTA CON SUMINISTRO DE GAS'] ?? '') === '1',

                ]
            );


            $drenaje = InmuebleDrenaje::updateOrCreate(
                ['cct' => $datos['CCT']],
                [
                    'drenaje_publico' => strtolower($datos['EL INMUEBLE CUENTA CON DRENAJE O COLECTOR PUBLICO'] ?? '') === '1',
                    'fosa_septica' => strtolower($datos['EL INMUEBLE CUENTA CON FOSA SEPTICA'] ?? '') === '1',
                    'planta_tratamiento' => strtolower($datos['EL INMUEBLE CUENTA CON PLANTA DE TRATAMIENTO'] ?? '') === '1',
                    'descarga_otro' => strtolower($datos['OTRO TIPO DE DESCARGA'] ?? '') === '1',
                    'separacion_aguas' => strtolower($datos['EL INMUEBLE CUENTA CON SEPARACION DE AGUAS NEGRAS Y PLUVIALES '] ?? '') === '1',
                    'sin_separacion_de_agua' => strtolower($datos['EL INMUEBLE NO CUENTA CON SEPARACION DE AGUAS NEGRAS Y PLUVIALES '] ?? '') === '1',

                ]
            );
            $sanitarios = InmuebleSanitarios::updateOrCreate(
                ['cct' => $datos['CCT']],
                [
                    'banos_hombres' => is_numeric($datos['NUMERO DE CUARTOS DE BAÑO PARA HOMBRES CON QUE CUENTA EL INMUEBLE'] ?? '')
                        ? intval($datos['NUMERO DE CUARTOS DE BAÑO PARA HOMBRES CON QUE CUENTA EL INMUEBLE'])
                        : 0,

                    'banos_mujeres' => is_numeric($datos['NUMERO DE CUARTOS DE BAÑO PARA MUJERES CON QUE CUENTA EL INMUEBLE'] ?? '')
                        ? intval($datos['NUMERO DE CUARTOS DE BAÑO PARA MUJERES CON QUE CUENTA EL INMUEBLE'])
                        : 0,

                    'banos_mixtos' => is_numeric($datos['NUMERO DE CUARTOS DE BAÑO MIXTOS CON QUE CUENTA EL INMUEBLE'] ?? '')
                        ? intval($datos['NUMERO DE CUARTOS DE BAÑO MIXTOS CON QUE CUENTA EL INMUEBLE'])
                        : 0,

                    'total_sanitarios' => is_numeric($datos['TOTAL DE TAZAS SANITARIAS, MIGITORIOS Y LETRINAS CON QUE CUENTA EL INMUEBLE'] ?? '')
                        ? intval($datos['TOTAL DE TAZAS SANITARIAS, MIGITORIOS Y LETRINAS CON QUE CUENTA EL INMUEBLE'])
                        : 0,

                    'sanitarios_ambos' => is_numeric($datos['TOTAL DE TAZAS SANITARIAS MIGITORIOS Y LETRINAS PARA USO DE AMBOS'] ?? '')
                        ? intval($datos['TOTAL DE TAZAS SANITARIAS MIGITORIOS Y LETRINAS PARA USO DE AMBOS'])
                        : 0,

                    'lavamanos' => is_numeric($datos['TOTAL DE LAVAMANOS QUE EXISTEN EN LA ESCUELA'] ?? '')
                        ? intval($datos['TOTAL DE LAVAMANOS QUE EXISTEN EN LA ESCUELA'])
                        : 0,

                    'tomas_bebederos' => is_numeric($datos['TOTAL DE TOMAS DE AGUA DE BEBEDEROS QUE EXISTEN EN EL INMUEBLE'] ?? '')
                        ? intval($datos['TOTAL DE TOMAS DE AGUA DE BEBEDEROS QUE EXISTEN EN EL INMUEBLE'])
                        : 0,

                    'banos_discapacitados' => is_numeric($datos['TOTAL DE CUARTOS DE BAÑO ACCESIBLES PARA DISCAPACITADOS'] ?? '')
                        ? intval($datos['TOTAL DE CUARTOS DE BAÑO ACCESIBLES PARA DISCAPACITADOS'])
                        : 0,
                ]
            );

            $obras = InmuebleObras::updateOrCreate(
                ['cct' => $datos['CCT']],
                [
                    'rehabilitacion_realizada' => strtolower($datos['EN LOS ULTIMOS 5 AÑOS EN EL INMUEBLE SE REALIZARON OBRAS DE REAHABILITACION O DE MANTENIMEINTO MAYOR'] ?? '') === '1',
                    'rehabilitacion_impermeabilizacion' => strtolower($datos['OBRA DE REAHABILITACION QUE SE REALIZO (IMPERMEABILIZACION)'] ?? '') === '1',
                    'rehabilitacion_albanileria' => strtolower($datos['OBRA DE REAHABILITACION QUE SE REALIZO (ALBAÑILERIA)'] ?? '') === '1',
                    'rehabilitacion_pintura' => strtolower($datos['OBRA DE REAHABILITACION QUE SE REALIZO (PINTURA GENERAL)'] ?? '') === '1',
                    'rehabilitacion_red_hidraulica' => strtolower($datos['OBRA DE REAHABILITACION QUE SE REALIZO (RESTITUCION DE LA RED HIDRAULICA)'] ?? '') === '1',
                    'rehabilitacion_red_sanitaria' => strtolower($datos['OBRA DE REAHABILITACION QUE SE REALIZO (RESTITUCION DE LA RED SANITARIA)'] ?? '') === '1',
                    'rehabilitacion_esctructural' => strtolower($datos['OBRA DE REAHABILITACION QUE SE REALIZO (RESTITUCION ESTRUCTURAL)'] ?? '') === '1',
                    'obras_nuevas' => strtolower($datos['DURANTES LOS ULTIMOS 5 AÑOS SE REALIZARON OBRAS NUEVAS'] ?? '') === '1',
                    'construccion_educativa' => strtolower($datos['CONSTRUCCION EN ESPACIOS ACADEMICOS O EDUCATIVOS'] ?? '') === '1',
                    'construccion_deportiva' => strtolower($datos['CONSTRUCCION EN ESPACIOS DEPORTIVOS O RECREATIVOS'] ?? '') === '1',
                    'construccion_sanitaria' => strtolower($datos['CONSTRUCCION EN SANITARIOS'] ?? '') === '1',
                    'construccion_complementos' => strtolower($datos['CONSTRUCCION EN COMPLEMENTOS DE INSTALACIONES'] ?? '') === '1',
                    'construccion_total' => strtolower($datos['CONSTRUCCION EN TODOS LOS ESPACIOS DEL INMUEBLE'] ?? '') === '1',
                    'construccion_otro' => strtolower($datos['CONSTRUCCION EN OTRO TIPO DE ESPACIO'] ?? '') === '1',

                ]
            );

            $seguridad = InmuebleSeguridad::updateOrCreate(
                ['cct' => $datos['CCT']],
                [
                    'proteccion_civil' => strtolower($datos['LA ESCUELA CUENTA CON PROGRAMA DE PROTECCION CIVIL'] ?? '') === '1',
                    'barda_completa' => strtolower($datos['EL INMUEBLE CUENTA CON BARDA O CERCA PERIMETRAL COMPLETO'] ?? '') === '1',
                    'barda_incompleta' => strtolower($datos['EL INMUEBLE CUENTA CON BARDA O CERCA PERIMETRAL INCOMPLETO'] ?? '') === '1',
                    'infraestructura_discapacidad' => strtolower($datos['EL INMUEBLE CUENTA CON INFRAESTRUCTURA (CAJONES, RAMPAS, SEÑALAMIENTOS, ETC) SOFTWARE, COMPUTADORAS PARA DISCAPACITADOS'] ?? '') === '1',
                    'sin_infraestructura_discapacidad' => strtolower($datos['EL INMUEBLE NO CUENTA CON INFRAESTRUCTURA (CAJONES, RAMPAS, SEÑALAMIENTOS, ETC) SOFTWARE, COMPUTADORAS PARA DISCAPACITADOS'] ?? '') === '1',
                    'equipo_discapacidad_total' => is_numeric($datos['EQUIPO O MOBILIARIO CON QUE CUENTA LA ESCUELA PARA PERSONAS CON DISCAPACIDAD (TOTAL TOTAL)'] ?? '')
                        ? intval($datos['EQUIPO O MOBILIARIO CON QUE CUENTA LA ESCUELA PARA PERSONAS CON DISCAPACIDAD (TOTAL TOTAL)'])
                        : 0,

                ]
            );

            $numeroEdificios = is_numeric(trim($datos['EDIFICIOS QUE SON UTILIZADOS POR LA ESCUELA'] ?? ''))
                ? intval(trim($datos['EDIFICIOS QUE SON UTILIZADOS POR LA ESCUELA']))
                : ($plantelExistente->numero_edificios ?? null);


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

            foreach ($rangosSuperficie as $clave => $encabezado) {
                $valor = strtolower(trim($datos[$encabezado] ?? ''));
                $aplica = in_array($valor, ['sí', 'si', '1', 'true']);

                InmuebleSuperficie::updateOrCreate(
                    ['cct' => $datos['CCT'], 'rango' => $clave],
                    ['aplica' => $aplica]
                );
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
                'numero_edificios' => is_numeric($numeroEdificios) ? intval($numeroEdificios) : null,
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
