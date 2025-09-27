<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plantel;
use App\Models\Localidad;
use App\Models\Macroregion;
use App\Models\InmuebleAgua;
use Illuminate\Support\Facades\Log;


class MapaController extends Controller
{
    public function mapa(Request $request)
    {
        // Construye el query sin ejecutarlo aún
        $query = Plantel::with(['municipio', 'localidad'])
            ->select(
                'id',
                'nombre_escuela as nombre',
                'cct',
                'latitud as lat',
                'longitud as lng',
                'estatus_plantel',
                'id_municipio',
                'id_localidad'
            )
            ->whereNotNull('latitud')
            ->whereNotNull('longitud');

        // Filtro por localidad
        if ($request->filled('localidad')) {
            $query->where('id_localidad', $request->localidad);
        }

        // Ejecuta el query con paginación
        $planteles = $query->paginate(500);

        return response()->json($planteles);
    }

    public function vistaMapa()
    {
        $localidades = Localidad::orderBy('nombre_localidad')->get();
        $macroregiones = Macroregion::orderBy('nombre_macroregion')->get();
        $microregiones = \App\Models\Microregion::orderBy('nombre_microregiones')->get();
        $municipios = \App\Models\Municipio::orderBy('nombre_municipio')->get();
        $niveles = \App\Models\InmuebleNivel::select('nivel')->distinct()->orderBy('nivel')->get();
        $rangosSuperficie = \App\Models\InmuebleSuperficie::select('rango')->distinct()->orderBy('rango')->get();

        //Vista para los filtros de agua
        $tiposAgua = [
            'agua_red_publica'   => 'Agua de red pública',
            'agua_pozo'          => 'Agua de pozo',
            'agua_cuerpo'        => 'Cuerpo de agua cercano',
            'agua_pipas'         => 'Agua por pipas',
            'agua_otro'          => 'Otro tipo de agua',
            'cisterna'           => 'Cisterna',
            'tinacos'            => 'Tinacos',
            'tanque'             => 'Tanque elevado',
            'almacenamiento_otro' => 'Otro tipo de almacenamiento'
        ];

        return view('planteles.mapa', compact(
            'localidades',
            'macroregiones',
            'microregiones',
            'municipios',
            'niveles',
            'rangosSuperficie',
            'tiposAgua' // ya disponible en la vista
        ));

        //Filtro para energia
        $tiposEnergia = [
            'suministro_energia'      => 'Suministro eléctrico',
            'energia_paneles_solares' => 'Paneles solares',
            'energia_planta'          => 'Planta eléctrica'
        ];

        return view('planteles.mapa', compact('localidades', 'macroregiones', 'microregiones', 'municipios',  'niveles', 'rangosSuperficie', 'tiposEnergia'));
    }

    //Filtro para superficies

    public function filtrar(Request $request)
    {
        try {
            // Para ver qué llega del front
            Log::info('Filtros recibidos:', $request->all());

            $query = Plantel::query();

            if ($request->filled('macroregion')) {
                Log::info('Macroregion filtro:', [$request->macroregion]);
                $query->where('macroregion_id', $request->macroregion);
            }

            if ($request->filled('microregion')) {
                Log::info('Microregión filtro:', [$request->microregion]);
                $query->where('microregion_id', $request->microregion);
            }

            if ($request->filled('municipio')) {
                Log::info('Municipio filtro:', [$request->municipio]);
                $query->where('id_municipio', $request->municipio);
            }

            if ($request->filled('nivel')) {
                Log::info('Nivel filtro:', [$request->nivel]);
                $query->whereHas('niveles', function ($q) use ($request) {
                    $q->where('nivel', $request->nivel);
                });
            }

            if ($request->filled('superficie')) {
                Log::info('Entró a filtro superficie', [$request->superficie]);

                $query->whereHas('superficies', function ($q) use ($request) {
                    Log::info('Comparando contra rango', [$request->superficie]);
                    $q->where('rango', $request->superficie)
                        ->where('aplica', 1); // Asegura que ese rango sí aplica
                });
            }



            $query->whereNotNull('latitud')->whereNotNull('longitud');

            $planteles = $query->with(['niveles', 'superficies', 'municipio', 'localidad'])->get();

            return response()->json(['data' => $planteles]);
        } catch (\Exception $e) {
            Log::error('Error en filtro de planteles: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error interno al aplicar filtros',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }

    //Filtro para agua

    public function filtrarAgua(Request $request)
    {
        try {
            $query = InmuebleAgua::query()
                ->whereHas('plantel', function ($q) use ($request) {
                    if ($request->filled('macroregion')) {
                        $q->where('macroregion_id', $request->macroregion);
                    }
                    if ($request->filled('microregion')) {
                        $q->where('microregion_id', $request->microregion);
                    }
                    if ($request->filled('municipio')) {
                        $q->where('id_municipio', $request->municipio);
                    }
                    if ($request->filled('nivel')) {
                        $q->whereHas('niveles', function ($nivelQ) use ($request) {
                            $nivelQ->where('nivel', $request->nivel);
                        });
                    }
                });

            foreach (
                [
                    'agua_red_publica',
                    'agua_pozo',
                    'agua_cuerpo',
                    'agua_pipas',
                    'agua_otro',
                    'cisterna',
                    'tinacos',
                    'tanque',
                    'almacenamiento_otro'
                ] as $campo
            ) {
                if ($request->input($campo) == 1) {
                    $query->where($campo, 1);
                }
            }

            $resultados = $query->with([
                'plantel.municipio',
                'plantel.localidad',
                'plantel.niveles',
            ])->get();

            return response()->json(['data' => $resultados]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => true,
                'mensaje' => 'Error interno',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }

    //Filtro para energia

    public function filtrarPlantelesEnergia(Request $request)
    {
        $query = Plantel::query()->with(['energia', 'municipio', 'localidad', 'niveles']);

        // Filtros de regiones
        if ($request->filled('macroregion')) {
            $query->where('macroregion_id', $request->macroregion);
        }

        if ($request->filled('microregion')) {
            $query->where('microregion_id', $request->microregion);
        }

        if ($request->filled('municipio')) {
            $query->where('id_municipio', $request->municipio);
        }

        // Filtro por nivel educativo
        if ($request->filled('nivel')) {
            $query->whereHas('niveles', function ($q) use ($request) {
                $q->where('nivel', $request->nivel);
            });
        }

        // Filtros de energía usando la relación correcta 'energia'
        if ($request->has('suministro_energia')) {
            $query->whereHas('energia', function ($q) {
                $q->where('suministro_energia', 1);
            });
        }

        if ($request->has('energia_paneles_solares')) {
            $query->whereHas('energia', function ($q) {
                $q->where('energia_paneles_solares', 1);
            });
        }

        if ($request->has('energia_planta')) {
            $query->whereHas('energia', function ($q) {
                $q->where('energia_planta', 1);
            });
        }

        // Solo planteles con coordenadas
        $query->whereNotNull('latitud')->whereNotNull('longitud');

        $planteles = $query->get();

        return response()->json(['data' => $planteles]);
    }

    //Filtros para drenaje
    public function filtrarPlantelesDrenaje(Request $request)
    {
        $query = Plantel::query()->with(['drenaje', 'municipio', 'localidad', 'niveles']);

        // Filtros de regiones
        if ($request->filled('macroregion')) {
            $query->where('macroregion_id', $request->macroregion);
        }

        if ($request->filled('microregion')) {
            $query->where('microregion_id', $request->microregion);
        }

        if ($request->filled('municipio')) {
            $query->where('id_municipio', $request->municipio);
        }

        // Filtro por nivel educativo
        if ($request->filled('nivel')) {
            $query->whereHas('niveles', function ($q) use ($request) {
                $q->where('nivel', $request->nivel);
            });
        }

        // Filtros de drenaje usando la relación correcta 'drenaje'
        if ($request->has('drenaje_publico')) {
            $query->whereHas('drenaje', function ($q) {
                $q->where('drenaje_publico', 1);
            });
        }

        if ($request->has('fosa_septica')) {
            $query->whereHas('drenaje', function ($q) {
                $q->where('fosa_septica', 1);
            });
        }

        if ($request->has('planta_tratamiento')) {
            $query->whereHas('drenaje', function ($q) {
                $q->where('planta_tratamiento', 1);
            });
        }

        if ($request->has('descarga_otro')) {
            $query->whereHas('drenaje', function ($q) {
                $q->where('descarga_otro', 1);
            });
        }

        if ($request->has('separacion_aguas')) {
            $query->whereHas('drenaje', function ($q) {
                $q->where('separacion_aguas', 1);
            });
        }

        // Solo planteles con coordenadas
        $query->whereNotNull('latitud')->whereNotNull('longitud');

        $planteles = $query->get();

        return response()->json(['data' => $planteles]);
    }
}
