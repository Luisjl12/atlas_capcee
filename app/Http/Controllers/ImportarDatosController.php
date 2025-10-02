<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plantel;
use App\Models\Municipio;
use App\Models\Corde;
use App\Models\Localidad;
use App\Models\Macroregion;
use App\Models\Microregion;
use Maatwebsite\Excel\Facades\Excel;

class ImportarDatosController extends Controller
{
    public function index()
    {
        return view('importar_datos.index');
    }

    public function store(Request $request)
    {
        set_time_limit(300);

        $request->validate([
            'archivo' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
        ]);

        $archivo = $request->file('archivo');
        $nombreOriginal = $archivo->getClientOriginalName();
        $nombreSistema = uniqid() . '_' . $nombreOriginal;
        $extension = $archivo->getClientOriginalExtension();

        if (in_array($extension, ['xlsx', 'xls'])) {
            $data = Excel::toArray([], $archivo)[0];
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
            return redirect()->back()->withErrors(['archivo' => 'El archivo está vacío o mal formado.']);
        }

        $encabezados = array_map('trim', $data[0]);
        $camposEsperados = ['CV_CCT', 'NOMBRECT', 'C_NOM_MUN', 'NOM_CORDE'];
        $faltantes = array_diff($camposEsperados, $encabezados);

        if (!empty($faltantes)) {
            return redirect()->back()->withErrors([
                'archivo' => 'Faltan encabezados requeridos: ' . implode(', ', $faltantes)
            ]);
        }

        $ruta = $archivo->storeAs('archivos_plantel', $nombreSistema, 'public');

        $errores = [];
        $procesados = 0;

        $nivelesAcademicos = [
            'inicial' => 'INMUEBLE IMPARTE EDUCACION INICIAL',
            'preescolar' => 'INMUEBLE IMPARTE EDUCACION PREESCOLAR',
            'primaria' => 'INMUEBLE IMPARTE EDUCACION PRIMARIA',
            'secundaria' => 'INMUEBLE IMPARTE EDUCACION SECUNDARIA',
            'formacion_trabajo' => 'INMUEBLE IMPARTE EDUCACION FORMACION PARA EL TRABAJO',
            'bachillerato_general' => 'INMUEBLE IMPARTE EDUCACION BACHILLERATO GENERAL',
            'bachillerato_tecnologico' => 'INMUEBLE IMPARTE EDUCACION BACHILLERATO TECNOLOGICO O QUIVALENTE',
            'tecnico_profesional' => 'INMUEBLE IMPARTE EDUCACION TECNICO PROFESIONAL',
            'tecnico_licenciatura' => 'INMUEBLE IMPARTE EDUCACION TECNICO LICENCIATURA',
            'tecnico_posgrado' => 'INMUEBLE IMPARTE EDUCACION TECNICO POSGRADO',
        ];

        $rangosSuperficie = [
            'menos_de_50' => 'MENOS DE 50 M2',
            'de_50_a_499' => 'DE 50 A 499 M2',
            'de_500_a_999' => 'DE 500 A 999 M2',
            'de_1000_a_9999' => 'DE 1000 A 9999 M2',
            'de_10000_o_mas' => 'DE 10,000  M2 O MAS',
        ];

        $atributosAgua = [
            'agua_red_publica' => 'EL INMUEBLE CUENTA CON SUMINISTRO DE AGUA EN RED PUBLICA',
            'agua_pozo' => 'EL INMUEBLE CUENTA CON SUMINISTRO DE AGUA EN POZO',
            'agua_cuerpo' => 'EL INMUEBLE CUENTA CON SUMINISTRO DE AGUA EN CUERPOS DE AGUA',
            'agua_pipas' => 'EL INMUEBLE CUENTA CON SUMINISTRO DE AGUA EN PIPAS',
            'agua_otro' => 'EL INMUEBLE CUENTA CON SUMINISTRO DE AGUA EN OTRO TIPO',
            'cisterna' => 'EL INMUEBLE CUENTA CON CISTERNA PARA ALMACENAMIENTO DE AGUA',
            'tinacos' => 'EL INMUEBLE CUENTA CON TINACOS PARA ALMACENAMIENTO DE AGUA',
            'tanque' => 'EL INMUEBLE CUENTA CON TANQUE PARA ALMACENAMIENTO DE AGUA',
            'almacenamiento_otro' => 'EL INMUEBLE CUENTA CON OTRO TIPO DE ALMACENAMIENTO PARA AGUA',
            'estado_red_hidraulica' => 'ESTADO DE RED HIDRAULICA', //String 
        ];
        $atributosEnergia = [
            'energia_planta' => 'EL INMUEBLE CUENTA CON SUMINISTRO DE ENERGIA ELECTRICA PLANTA GENERADORA DE LUZ',
            'energia_panales_solares' => 'EL INMUEBLE CUENTA CON PANELES SOLARES CON BATERIA (PSB)',
            'suministro_energia' => 'EL INMUEBLE CUENTA CON SUMINISTRO DE ENERGIA ELECTRICA', //
            'estado_instalacion_electrica' => 'ESTADO DE INSTALACION ELECTRICA', //String 
        ];

        $atributosDrenaje = [
            'drenaje_publico' => 'EL INMUEBLE CUENTA CON DRENAJE O COLECTOR PUBLICO',
            'fosa_septica' => 'EL INMUEBLE CUENTA CON FOSA SEPTICA',
            'planta_tratamiento' => 'EL INMUEBLE CUENTA CON PLANTA DE TRATAMIENTO',
            'descarga_otro' => 'OTRO TIPO DE DESCARGA',
            'separacion_aguas' =>  'EL INMUEBLE CUENTA CON SEPARACION DE AGUAS NEGRAS Y PLUVIALES ',
        ];

        $sanitarios = [
            'banos_hombres' => 'NUMERO DE CUARTOS DE BAÑO PARA HOMBRES CON QUE CUENTA EL INMUEBLE',
            'banos_mujeres' => 'NUMERO DE CUARTOS DE BAÑO PARA MUJERES CON QUE CUENTA EL INMUEBLE',
            'estado_banos' => 'ESTADO DE BAÑOS', //String
            'total_sanitarios' => 'TOTAL DE TAZAS SANITARIAS, MIGITORIOS Y LETRINAS CON QUE CUENTA EL INMUEBLE',
            'estado_minigitorios' => 'ESTADO MINGITORIOS', //String
            'lavamanos' => 'TOTAL DE LAVAMANOS QUE EXISTEN EN LA ESCUELA',
            'estado_lavamanos' => 'ESTADO DE LAVAMANOS', //String
            'tomas_bebederos' => 'TOTAL DE TOMAS DE AGUA DE BEBEDEROS QUE EXISTEN EN EL INMUEBLE',
            'estado_bebederos' => 'ESTADO DE BEBEDEROS', //String
            'banos_discapacitados' => 'TOTAL DE CUARTOS DE BAÑO ACCESIBLES PARA DISCAPACITADOS',
            'estado_instalacion_sanitaria' => 'ESTADO DE INSTALACION SANITARIA', //String
        ];

        $atributosObras = [
            'rehabilitacion_realizada' => 'EN LOS ULTIMOS 5 AÑOS EN EL INMUEBLE SE REALIZARON OBRAS DE REAHABILITACION O DE MANTENIMEINTO MAYOR',
            'rehabilitacion_impermeabilizacion' => 'OBRA DE REAHABILITACION QUE SE REALIZO (IMPERMEABILIZACION)',
            'rehabilitacion_albanileria' => 'OBRA DE REAHABILITACION QUE SE REALIZO (ALBAÑILERIA)',
            'rehabilitacion_pintura' => 'OBRA DE REAHABILITACION QUE SE REALIZO (PINTURA GENERAL)',
            'rehabilitacion_red_hidraulica' => 'OBRA DE REAHABILITACION QUE SE REALIZO (RESTITUCION DE LA RED HIDRAULICA)',
            'rehabilitacion_red_sanitaria' => 'OBRA DE REAHABILITACION QUE SE REALIZO (RESTITUCION DE LA RED SANITARIA)',
            'rehabilitacion_estructural' => 'OBRA DE REAHABILITACION QUE SE REALIZO (RESTITUCION ESTRUCTURAL)',
            'obras_nuevas' => 'DURANTES LOS ULTIMOS 5 AÑOS SE REALIZARON OBRAS NUEVAS',
            'construccion_educativa' => 'CONSTRUCCION EN ESPACIOS ACADEMICOS O EDUCATIVOS',
            'construccion_deportiva' => 'CONSTRUCCION EN ESPACIOS DEPORTIVOS O RECREATIVOS',
            'construccion_sanitaria' => 'CONSTRUCCION EN SANITARIOS',
            'construccion_complementos' => 'CONSTRUCCION EN COMPLEMENTOS DE INSTALACIONES',
            'construccion_total' => 'CONSTRUCCION EN TODOS LOS ESPACIOS DEL INMUEBLE',
            'construccion_otro' => 'CONSTRUCCION EN OTRO TIPO DE ESPACIO',
        ];

        $atributosSeguridad = [
            'proteccion_civil' => 'LA ESCUELA CUENTA CON PROGRAMA DE PROTECCION CIVIL',
            'barda_completa' => 'EL INMUEBLE CUENTA CON BARDA O CERCA PERIMETRAL COMPLETO',
            'estado_barda' => 'ESTADO DE BARDA PERIMETRAL', //string
            'estado_cerco' => 'ESTADO DE CERCO PERIMETRAL', //string
            'infraestructura_discapacidad' => 'EL INMUEBLE CUENTA CON INFRAESTRUCTURA (CAJONES, RAMPAS, SEÑALAMIENTOS, ETC) SOFTWARE, COMPUTADORAS PARA DISCAPACITADOS',
        ];

        $campoEquipoDiscapacidad = 'EQUIPO O MOBILIARIO CON QUE CUENTA LA ESCUELA PARA PERSONAS CON DISCAPACIDAD (TOTAL TOTAL)';

        //Atributos adaptados
        $atributosProteccionCivil = [
            'programa_interno_pc' => 'PROGRAMA DE PROTECCION CIVIL',
        ];

        //Atributos para detalles o atributos a tabla detalle hidrosanitario
        $atributosHidrosanitario = [
            'sanitarios_hombres_wc' => 'NUMERO DE CUARTOS DE BAÑO PARA HOMBRES',
            'sanitarios_mujeres_wc' => 'NUMERO DE CUARTOS DE BAÑO PARA MUJERES',

        ];

        //Atributos de electricidad adapdatos 
        $atributosElectricidad = [
            'electricidad_contrato' => 'EL INMUEBLE CUENTA CON SUMINISTRO DE ENERGIA ELECTRICA RED PUBLICA CON CONTRATO',
        ];

        foreach (array_slice($data, 1) as $fila) {
            $fila = array_map('trim', $fila);

            $cct = strtoupper($fila[array_search('CV_CCT', $encabezados)]);
            $nombreEscuela = $fila[array_search('NOMBRECT', $encabezados)];
            $nombreMunicipio = $fila[array_search('C_NOM_MUN', $encabezados)];
            $nombreCorde = $fila[array_search('NOM_CORDE', $encabezados)];


            if (!$cct || !$nombreMunicipio || !$nombreCorde) {
                $errores[] = ['cct' => $cct, 'error' => 'Faltan campos obligatorios'];
                continue;
            }

            $municipio = Municipio::firstOrCreate(
                ['nombre_municipio' => $nombreMunicipio],
                ['nombre_municipio' => $nombreMunicipio]
            );
            $nombreCorde = $this->normalizarNombreCorde($nombreCorde);

            $corde = Corde::whereRaw('LOWER(nombre_corde) = ?', [strtolower($nombreCorde)])->first();

            if (!$corde) {
                $errores[] = ['cct' => $cct, 'error' => "Corde '$nombreCorde' no encontrado"];
                continue;
            }


            // Preparar campos base
            $campos = [
                'id_municipio' => $municipio->id,
                'id_corde' => $corde->id,
                'nombre_escuela' => $nombreEscuela,
            ];

            //Latitud y Longitud 
            $latitud = $fila[array_search('LATITUD', $encabezados)] ?? null;
            $longitud = $fila[array_search('LONGITUD', $encabezados)] ?? null;

            if (!empty($latitud)) {
                $campos['latitud'] = is_numeric($latitud) ? floatval($latitud) : null;
            }

            if (!empty($longitud)) {
                $campos['longitud'] = is_numeric($longitud) ? floatval($longitud) : null;
            }

            // Solo actualizar localidad si el encabezado existe y viene con valor
            if (in_array('C_NOM_LOC', $encabezados)) {
                $nombreLocalidad = $fila[array_search('C_NOM_LOC', $encabezados)] ?? null;

                if (!empty($nombreLocalidad)) {
                    $localidad = Localidad::firstOrCreate(
                        [
                            'nombre_localidad' => $nombreLocalidad,
                            'municipio_id' => $municipio->id,
                        ],
                        [
                            'nombre_localidad' => $nombreLocalidad,
                            'municipio_id' => $municipio->id,
                        ]
                    );

                    $campos['id_localidad'] = $localidad->id;
                }
            }

            // Número de edificios (opcional)
            if (in_array('EDIFICIOS QUE SON UTILIZADOS POR LA ESCUELA', $encabezados)) {
                $numeroEdificios = $fila[array_search('EDIFICIOS QUE SON UTILIZADOS POR LA ESCUELA', $encabezados)] ?? null;

                if (!empty($numeroEdificios)) {
                    $campos['numero_edificios'] = is_numeric($numeroEdificios) ? intval($numeroEdificios) : null;
                }
            }

            //Nivel educativo en string

            if (in_array('TIPO EDUCATIVO', $encabezados)) {
                $tipoEducativo = $fila[array_search('TIPO EDUCATIVO', $encabezados)] ?? null;

                if (!empty($tipoEducativo)) {
                    $campos['nivel_educativo'] = trim($tipoEducativo);
                }
            }

            //Syministro de agua en string  

            if (in_array('SUMINISTRO DE AGUA', $encabezados)) {
                $suministroAgua = $fila[array_search('SUMINISTRO DE AGUA', $encabezados)] ?? null;

                if (!empty($suministroAgua)) {
                    // Guardar en la tabla detalle_hidrosanitario
                    \App\Models\DetalleHidrosanitario::updateOrCreate(
                        ['cct' => $cct], // condición
                        ['fuente_agua' => $suministroAgua] // valores a insertar/actualizar
                    );
                }
            }

            //Almacenamiento de agua en string
            if (in_array('ALMACENAMIENTO DE AGUA', $encabezados)) {
                $suministroAgua = $fila[array_search('ALMACENAMIENTO DE AGUA', $encabezados)] ?? null;

                if (!empty($suministroAgua)) {
                    // Guardar en la tabla detalle_hidrosanitario
                    \App\Models\DetalleHidrosanitario::updateOrCreate(
                        ['cct' => $cct], // condición
                        ['almacenamiento_agua' => $suministroAgua] // valores a insertar/actualizar
                    );
                }
            }

            //Tipo de descarga string 
            if (in_array('TIPO DE DRENAJE', $encabezados)) {
                $suministroDrenaje = $fila[array_search('TIPO DE DRENAJE', $encabezados)] ?? null;

                if (!empty($suministroDrenaje)) {
                    // Guardar en la tabla detalle_hidrosanitario
                    \App\Models\DetalleHidrosanitario::updateOrCreate(
                        ['cct' => $cct], // condición
                        ['tipo_drenaje' => $suministroDrenaje] // valores a insertar/actualizar
                    );
                }
            }

            //Tipo de gas 
            if (in_array('TIPO DE GAS', $encabezados)) {
                $suministroGas = $fila[array_search('TIPO DE GAS', $encabezados)] ?? null;

                if (!empty($suministroGas)) {
                    // Guardar en la tabla detalle_hidrosanitario
                    \App\Models\DetalleServicio::updateOrCreate(
                        ['cct' => $cct], // condición
                        ['gas_tipo' => $suministroGas] // valores a insertar/actualizar
                    );
                }
            }

            //Crear o editar plantel
            $plantel = Plantel::updateOrCreate(
                ['cct' => $cct],
                $campos
            );

            // Asignar macroregión si existe en el Excel
            if (in_array('MACRORREGION', $encabezados)) {
                $nombreMacroregion = strtoupper($fila[array_search('MACRORREGION', $encabezados)] ?? '');

                if (!empty($nombreMacroregion)) {
                    $macroregion = Macroregion::firstOrCreate([
                        'nombre_macroregion' => $nombreMacroregion
                    ]);

                    $plantel->macroregion()->associate($macroregion);
                    $plantel->save();
                }
            }

            //Asignar microregion si existe en el Excel
            if (in_array('MICRORREGION', $encabezados)) {
                $nombreMicroregion = strtoupper($fila[array_search('MICRORREGION', $encabezados)] ?? '');

                if (!empty($nombreMicroregion)) {
                    $microregion = Microregion::firstOrCreate([
                        'nombre_microregiones' => $nombreMicroregion
                    ]);

                    $plantel->microregion()->associate($microregion);
                    $plantel->save();
                }
            }


            //Leer nivel academico del archivo excel
            foreach ($nivelesAcademicos as $clave => $encabezado) {
                if (in_array($encabezado, $encabezados)) {
                    $valor = $fila[array_search($encabezado, $encabezados)] ?? null;

                    if ($valor !== null && $valor !== '') {
                        if ((int)$valor === 1) {
                            // Si viene 1 => aseguramos que exista
                            \App\Models\InmuebleNivel::updateOrCreate(
                                ['cct' => $cct, 'nivel' => $clave],
                                [] // no necesitas más atributos
                            );
                        } else {
                            // Si viene 0 => borramos el registro
                            \App\Models\InmuebleNivel::where('cct', $cct)
                                ->where('nivel', $clave)
                                ->delete();
                        }
                    }
                }
            }
            //Leer rango de superficie 
            foreach ($rangosSuperficie as $clave => $encabezado) {
                if (in_array($encabezado, $encabezados)) {
                    $valor = $fila[array_search($encabezado, $encabezados)] ?? null;

                    // Normalizamos el valor
                    $aplica = ($valor == 1 || strtolower(trim($valor)) === 'si');

                    // Buscamos o creamos el registro
                    $registro = \App\Models\InmuebleSuperficie::firstOrNew([
                        'cct' => $cct,
                        'rango' => $clave,
                    ]);

                    // Actualizamos el valor de "aplica" (incluso si es 0)
                    $registro->aplica = $aplica;
                    $registro->save();
                }
            }

            //Leer datos hidraulicos 
            $datosAgua = ['cct' => $cct];

            foreach ($atributosAgua as $campo => $encabezado) {
                if (in_array($encabezado, $encabezados)) {
                    $valor = $fila[array_search($encabezado, $encabezados)] ?? null;

                    if ($campo === 'estado_red_hidraulica') {
                        // Se guarda tal cual como string 
                        $datosAgua[$campo] = trim((string)$valor);
                    } else {
                        // Normalización booleana
                        $datosAgua[$campo] = ($valor == 1 || strtolower(trim((string)$valor)) === 'si');
                    }
                }
            }


            // Solo guarda si hay al menos un campo evaluado además de 'cct'
            if (count($datosAgua) > 1) {
                \App\Models\InmuebleAgua::updateOrCreate(
                    ['cct' => $cct],
                    $datosAgua
                );
            }

            //Leer datos de energia
            $datosEnergia = ['cct' => $cct];


            foreach ($atributosEnergia as $campo => $encabezado) {
                if (in_array($encabezado, $encabezados)) {
                    $valor = $fila[array_search($encabezado, $encabezados)] ?? null;

                    if ($campo === 'estado_instalacion_electrica') {
                        // Se guarda como string limpio
                        $datosEnergia[$campo] = trim((string)$valor);
                    } else {
                        // Normalización booleana
                        $datosEnergia[$campo] = ($valor == 1 || strtolower(trim((string)$valor)) === 'si');
                    }
                }
            }

            if (count($datosEnergia) > 1) {
                \App\Models\InmuebleEnergia::updateOrCreate(
                    ['cct' => $cct],
                    $datosEnergia
                );
            }

            // Solo guarda si hay al menos un campo evaluado además de 'cct'
            if (count($datosEnergia) > 1) {
                \App\Models\InmuebleEnergia::updateOrCreate(
                    ['cct' => $cct],
                    $datosEnergia
                );
            }

            //Leer datos de drenaje 
            $datosDrenaje = ['cct' => $cct];

            foreach ($atributosDrenaje as $campo => $encabezado) {
                if (in_array($encabezado, $encabezados)) {
                    $valor = $fila[array_search($encabezado, $encabezados)] ?? null;

                    // Normalizamos: 1 o "si" => true, en cualquier otro caso => false
                    $datosDrenaje[$campo] = ($valor == 1 || strtolower(trim((string)$valor)) === 'si');
                }
            }

            // Solo guarda si hay al menos un campo además de 'cct'
            if (count($datosDrenaje) > 1) {
                \App\Models\InmuebleDrenaje::updateOrCreate(
                    ['cct' => $cct],
                    $datosDrenaje
                );
            }

            //Leer datos sanitarios 
            $datosSanitarios = ['cct' => $cct];

            foreach ($sanitarios as $campo => $encabezado) {
                if (in_array($encabezado, $encabezados)) {
                    $valor = $fila[array_search($encabezado, $encabezados)] ?? null;

                    // Campos de estado que deben guardarse como string
                    if (str_starts_with($campo, 'estado_')) {
                        $datosSanitarios[$campo] = trim((string)$valor);
                    }
                    // Campos numéricos
                    elseif (is_numeric($valor)) {
                        $datosSanitarios[$campo] = intval($valor);
                    }
                }
            }

            if (count($datosSanitarios) > 1) {
                \App\Models\InmuebleSanitarios::updateOrCreate(
                    ['cct' => $cct],
                    $datosSanitarios
                );
            }


            //Leer datos de obras del excel 
            $datosObras = ['cct' => $cct];

            foreach ($atributosObras as $campo => $encabezado) {
                if (in_array($encabezado, $encabezados)) {
                    $valor = $fila[array_search($encabezado, $encabezados)] ?? null;

                    if ($valor !== null && $valor !== '') {
                        $datosObras[$campo] = (bool) $valor;
                    }
                }
            }

            if (count($datosObras) > 1) {
                \App\Models\InmuebleObras::updateOrCreate(
                    ['cct' => $cct],
                    $datosObras
                );
            }

            //Leer datos de seguridad 
            $datosSeguridad = ['cct' => $cct];

            foreach ($atributosSeguridad as $campo => $encabezado) {
                if (in_array($encabezado, $encabezados)) {
                    $valor = $fila[array_search($encabezado, $encabezados)] ?? null;

                    if (in_array($campo, ['estado_barda', 'estado_cerco'])) {
                        // Guardamos como string limpio
                        $datosSeguridad[$campo] = trim((string)$valor);
                    } else {
                        // Normalización booleana
                        $datosSeguridad[$campo] = ($valor == 1 || strtolower(trim((string)$valor)) === 'si');
                    }
                }
            }

            // Campo entero adicional
            if (in_array($campoEquipoDiscapacidad, $encabezados)) {
                $valor = $fila[array_search($campoEquipoDiscapacidad, $encabezados)] ?? null;
                $datosSeguridad['equipo_discapacidad_total'] = is_numeric($valor) ? intval($valor) : 0;
            }

            \App\Models\InmuebleSeguridad::updateOrCreate(
                ['cct' => $cct],
                $datosSeguridad
            );

            //Datos adaptados
            $datosProteccionCivil = ['cct' => $cct];

            foreach ($atributosProteccionCivil as $campo => $encabezado) {
                if (in_array($encabezado, $encabezados)) {
                    $valor = $fila[array_search($encabezado, $encabezados)] ?? null;

                    // Normalizamos: 1 o "si" => true, en cualquier otro caso => false
                    $datosProteccionCivil[$campo] = ($valor == 1 || strtolower(trim((string)$valor)) === 'si');
                }
            }

            \App\Models\DetalleProteccionCivil::updateOrCreate(
                ['cct' => $cct],
                $datosProteccionCivil
            );

            //Datos adaptados a detalle hidrosanitario
            $datosHidrosanitario = ['cct' => $cct];
            $camposEnteros = ['sanitarios_hombres_wc', 'sanitarios_mujeres_wc'];

            foreach ($atributosHidrosanitario as $campo => $encabezado) {
                if (in_array($encabezado, $encabezados)) {
                    $valor = $fila[array_search($encabezado, $encabezados)] ?? null;

                    if ($valor !== null && $valor !== '') {
                        // Si el campo está en la lista de enteros, lo convertimos
                        $datosHidrosanitario[$campo] = in_array($campo, $camposEnteros)
                            ? (int) $valor
                            : $valor;
                    }
                }
            }

            \App\Models\DetalleHidrosanitario::updateOrCreate(
                ['cct' => $cct],
                $datosHidrosanitario
            );

            // Datos adaptados a detalle servicios de electricidad
            $datosElectricidad = ['cct' => $cct];

            foreach ($atributosElectricidad as $campo => $encabezado) {
                if (in_array($encabezado, $encabezados)) {
                    $valor = $fila[array_search($encabezado, $encabezados)] ?? null;

                    // Normalizamos a booleano: 1 o "si" => true, en cualquier otro caso => false
                    $datosElectricidad[$campo] = ($valor == 1 || strtolower(trim((string) $valor)) === 'si');
                }
            }

            \App\Models\DetalleServicio::updateOrCreate(
                ['cct' => $cct],
                $datosElectricidad
            );


            $procesados++;
        }

        return redirect()->back()->with([
            'mensaje' => "Importación completada. Registros procesados: $procesados",
            'errores' => $errores,
        ]);
    }
    // Normalizar nombres de cordes
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
            'Tepexi De RodrÍguez' => 'Tepexi',
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
