<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plantel;
use App\Models\Municipio;
use App\Models\Corde;
use App\Models\Localidad;
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
        $camposEsperados = ['CCT', 'NOMBRE_ESCUELA', 'ID_MUNICIPIO', 'ID_CORDE'];
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
        ];
        $atributosEnergia = [
            'energia_red_contrato' => 'EL INMUEBLE CUENTA CON SUMINISTRO DE ENERGIA ELECTRICA RED PUBLICA CON CONTRATO',
            'energia_red_sin_contrato' => 'EL INMUEBLE CUENTA CON SUMINISTRO DE ENERGIA ELECTRICA RED PUBLICA SIN CONTRATO',
            'energia_planta' => 'EL INMUEBLE CUENTA CON SUMINISTRO DE ENERGIA ELECTRICA PLANTA GENERADORA DE LUZ',
            'energia_panales_solares' => 'EL INMUEBLE CUENTA CON PANELES SOLARES CON BATERIA (PSB)',
            'sin_energia' => 'EL INMUEBLE NO CUENTA CON SUMINISTRO DE ENERGIA ELECTRICA',
            'gas_natural' => 'EL INMUEBLE CUENTA CON SUMINISTRO DE GAS NATURAL',
            'gas_estacionario' => 'EL INMUEBLE CUENTA CON SUMINISTRO DE GAS ESTACIONARIO',
            'gas_cilindro' => 'EL INMUEBLE CUENTA CON SUMINISTRO DE GAS EN CILINDROS',
            'sin_gas' => 'EL INMUEBLE NO CUENTA CON SUMINISTRO DE GAS',
        ];

        $atributosDrenaje = [
            'drenaje_publico' => 'EL INMUEBLE CUENTA CON DRENAJE O COLECTOR PUBLICO',
            'fosa_septica' => 'EL INMUEBLE CUENTA CON FOSA SEPTICA',
            'planta_tratamiento' => 'EL INMUEBLE CUENTA CON PLANTA DE TRATAMIENTO',
            'descarga_otro' => 'OTRO TIPO DE DESCARGA',
            'separacion_aguas' =>  'EL INMUEBLE CUENTA CON SEPARACION DE AGUAS NEGRAS Y PLUVIALES ',
            'sin_separacion_aguas' => 'EL INMUEBLE NO CUENTA CON SEPARACION DE AGUAS NEGRAS Y PLUVIALES ',
        ];

        $sanitarios = [
            'banos_hombres' => 'NUMERO DE CUARTOS DE BAÑO PARA HOMBRES CON QUE CUENTA EL INMUEBLE',
            'banos_mujeres' => 'NUMERO DE CUARTOS DE BAÑO PARA MUJERES CON QUE CUENTA EL INMUEBLE',
            'banos_mixtos' => 'NUMERO DE CUARTOS DE BAÑO MIXTOS CON QUE CUENTA EL INMUEBLE',
            'total_sanitarios' => 'TOTAL DE TAZAS SANITARIAS, MIGITORIOS Y LETRINAS CON QUE CUENTA EL INMUEBLE',
            'sanitarios_ambos' => 'TOTAL DE TAZAS SANITARIAS MIGITORIOS Y LETRINAS PARA USO DE AMBOS',
            'lavamanos' => 'TOTAL DE LAVAMANOS QUE EXISTEN EN LA ESCUELA',
            'tomas_bebederos' => 'TOTAL DE TOMAS DE AGUA DE BEBEDEROS QUE EXISTEN EN EL INMUEBLE',
            'banos_discapacitados' => 'TOTAL DE CUARTOS DE BAÑO ACCESIBLES PARA DISCAPACITADOS',
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
            'barda_incompleta' => 'EL INMUEBLE CUENTA CON BARDA O CERCA PERIMETRAL INCOMPLETO',
            'infraestructura_discapacidad' => 'EL INMUEBLE CUENTA CON INFRAESTRUCTURA (CAJONES, RAMPAS, SEÑALAMIENTOS, ETC) SOFTWARE, COMPUTADORAS PARA DISCAPACITADOS',
            'sin_infraestructura_discapacidad' => 'EL INMUEBLE NO CUENTA CON INFRAESTRUCTURA (CAJONES, RAMPAS, SEÑALAMIENTOS, ETC) SOFTWARE, COMPUTADORAS PARA DISCAPACITADOS',
        ];


        $campoEquipoDiscapacidad = 'EQUIPO O MOBILIARIO CON QUE CUENTA LA ESCUELA PARA PERSONAS CON DISCAPACIDAD (TOTAL TOTAL)';


        foreach (array_slice($data, 1) as $fila) {
            $fila = array_map('trim', $fila);

            $cct = strtoupper($fila[array_search('CCT', $encabezados)]);
            $nombreEscuela = $fila[array_search('NOMBRE_ESCUELA', $encabezados)];
            $nombreMunicipio = $fila[array_search('ID_MUNICIPIO', $encabezados)];
            $nombreCorde = $fila[array_search('ID_CORDE', $encabezados)];


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

            // Solo actualizar localidad si el encabezado existe y viene con valor
            if (in_array('LOCALIDAD', $encabezados)) {
                $nombreLocalidad = $fila[array_search('LOCALIDAD', $encabezados)] ?? null;

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


            Plantel::updateOrCreate(
                ['cct' => $cct],
                $campos
            );

            $plantel = Plantel::where('cct', $cct)->first();

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


            $datosAgua = ['cct' => $cct];

            foreach ($atributosAgua as $campo => $encabezado) {
                if (in_array($encabezado, $encabezados)) {
                    $valor = $fila[array_search($encabezado, $encabezados)] ?? null;

                    // Normalizamos: si viene 1 o "si" => true, en cualquier otro caso => false
                    $datosAgua[$campo] = ($valor == 1 || strtolower(trim((string)$valor)) === 'si');
                }
            }

            // Solo guarda si hay al menos un campo evaluado además de 'cct'
            if (count($datosAgua) > 1) {
                \App\Models\InmuebleAgua::updateOrCreate(
                    ['cct' => $cct],
                    $datosAgua
                );
            }


            $datosEnergia = ['cct' => $cct];

            foreach ($atributosEnergia as $campo => $encabezado) {
                if (in_array($encabezado, $encabezados)) {
                    $valor = $fila[array_search($encabezado, $encabezados)] ?? null;

                    // Normalizamos: si viene 1 o "si" => true, en cualquier otro caso => false
                    $datosEnergia[$campo] = ($valor == 1 || strtolower(trim((string)$valor)) === 'si');
                }
            }

            // Solo guarda si hay al menos un campo evaluado además de 'cct'
            if (count($datosEnergia) > 1) {
                \App\Models\InmuebleEnergia::updateOrCreate(
                    ['cct' => $cct],
                    $datosEnergia
                );
            }

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


            $datosSanitarios = ['cct' => $cct];

            foreach ($sanitarios as $campo => $encabezado) {
                if (in_array($encabezado, $encabezados)) {
                    $valor = $fila[array_search($encabezado, $encabezados)] ?? null;

                    if (is_numeric($valor)) {
                        $datosSanitarios[$campo] = intval($valor);
                    }
                }
            }

            // Si hay al menos un campo numérico, actualiza o crea
            if (count($datosSanitarios) > 1) {
                \App\Models\InmuebleSanitarios::updateOrCreate(
                    ['cct' => $cct],
                    $datosSanitarios
                );
            }

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


            $datosSeguridad = ['cct' => $cct];

            // Booleanos
            foreach ($atributosSeguridad as $campo => $encabezado) {
                if (in_array($encabezado, $encabezados)) {
                    $valor = $fila[array_search($encabezado, $encabezados)] ?? null;

                    // Normalizamos: 1 o "si" => true, cualquier otro valor => false
                    $datosSeguridad[$campo] = ($valor == 1 || strtolower(trim((string)$valor)) === 'si');
                }
            }

            // Entero
            if (in_array($campoEquipoDiscapacidad, $encabezados)) {
                $valor = $fila[array_search($campoEquipoDiscapacidad, $encabezados)] ?? null;

                // Si viene vacío o 0 lo guardamos como 0
                $datosSeguridad['equipo_discapacidad_total'] = is_numeric($valor) ? intval($valor) : 0;
            }

            // Actualiza o crea siempre que tenga algún dato
            \App\Models\InmuebleSeguridad::updateOrCreate(
                ['cct' => $cct],
                $datosSeguridad
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
