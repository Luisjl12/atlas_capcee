<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait FiltrablePorCategoria
{
    public function aplicarFiltrosPorCategoria($query, Request $request, string $categoria)
    {
        $atributos = $this->atributosPorCategoria($categoria);

        //Filtro por superficie
        if ($categoria === 'superficie' && $request->filled('superficie')) {
            $query->whereHas('superficies', function ($q) use ($request) {
                $q->where('rango', $request->superficie)
                    ->where('aplica', 1);
            });
            return $query;
        }

        $filtros = array_filter($request->only($atributos), fn($v) => $v == 1);

        //Categorias para medir el equipo para discapacitados
        if ($categoria === 'accesibilidad') {
            // Filtros booleanos
            $filtros = array_filter($request->only([
                'infraestructura_discapacidad',
                'sin_infraestructura_discapacidad'
            ]), fn($v) => $v == 1);

            if (!empty($filtros)) {
                $query->whereHas('seguridad', function ($q) use ($filtros) {
                    foreach ($filtros as $campo => $valor) {
                        $q->where($campo, 1);
                    }
                });
            }

            // Filtro por categoría de equipo
            if ($request->filled('equipo_discapacidad_categoria')) {
                $query->whereHas('seguridad', function ($q) use ($request) {
                    switch ($request->equipo_discapacidad_categoria) {
                        case 'ninguno':
                            $q->where('equipo_discapacidad_total', 0);
                            break;
                        case 'bajo':
                            $q->whereBetween('equipo_discapacidad_total', [1, 2]);
                            break;
                        case 'medio':
                            $q->whereBetween('equipo_discapacidad_total', [3, 5]);
                            break;
                        case 'alto':
                            $q->where('equipo_discapacidad_total', '>', 5);
                            break;
                    }
                });
            }

            return $query;
        }

        //Logica especial para categorias de los estados hidraulica, energetica y conservacion
        if ($categoria === 'estado_conservacion') {
            if ($request->filled('estado_red_hidraulica')) {
                $query->whereHas('agua', function ($q) use ($request) {
                    $q->where('estado_red_hidraulica', $request->estado_red_hidraulica);
                });
            }

            if ($request->filled('estado_instalacion_sanitaria')) {
                $query->whereHas('sanitario', function ($q) use ($request) {
                    $q->where('estado_instalacion_sanitaria', $request->estado_instalacion_sanitaria);
                });
            }

            if ($request->filled('estado_instalacion_electrica')) {
                $query->whereHas('energia', function ($q) use ($request) {
                    $q->where('estado_instalacion_electrica', $request->estado_instalacion_electrica);
                });
            }

            return $query;
        }

        //logica especial para sanitarios 
        if ($categoria === 'sanitario') {
            if ($request->filled('estado_banos')) {
                $query->whereHas('sanitario', fn($q) => $q->where('estado_banos', $request->estado_banos));
            }

            if ($request->filled('banos_hombres_min')) {
                $query->whereHas('sanitario', fn($q) => $q->where('banos_hombres', '>=', $request->banos_hombres_min));
            }

            if ($request->filled('banos_mujeres_min')) {
                $query->whereHas('sanitario', fn($q) => $q->where('banos_mujeres', '>=', $request->banos_mujeres_min));
            }

            if ($request->filled('lavamanos_min')) {
                $query->whereHas('sanitario', fn($q) => $q->where('lavamanos', '>=', $request->lavamanos_min));
            }

            if ($request->filled('estado_lavamanos')) {
                $query->whereHas('sanitario', fn($q) => $q->where('estado_lavamanos', $request->estado_lavamanos));
            }

            if ($request->filled('tomas_bebederos_min')) {
                $query->whereHas('sanitario', fn($q) => $q->where('tomas_bebederos', '>=', $request->tomas_bebederos_min));
            }

            if ($request->filled('estado_bebederos')) {
                $query->whereHas('sanitario', fn($q) => $q->where('estado_bebederos', $request->estado_bebederos));
            }

            return $query;
        }

        //Logica especial para seguridad
        if ($categoria === 'seguridad') {
            if ($request->filled('proteccion_civil')) {
                $query->whereHas('seguridad', fn($q) => $q->where('proteccion_civil', $request->proteccion_civil));
            }

            if ($request->filled('barda_completa')) {
                $query->whereHas('seguridad', fn($q) => $q->where('barda_completa', $request->barda_completa));
            }

            if ($request->filled('estado_barda')) {
                $query->whereHas('seguridad', fn($q) => $q->where('estado_barda', $request->estado_barda));
            }

            if ($request->filled('estado_cerco')) {
                $query->whereHas('seguridad', fn($q) => $q->where('estado_cerco', $request->estado_cerco));
            }

            return $query;
        }

        if ($categoria === 'energia') {
            if ($request->filled('suministro_energia')) {
                $query->whereHas(
                    'energia',
                    fn($q) =>
                    $q->where('suministro_energia', $request->suministro_energia)
                );
            }

            if ($request->filled('energia_paneles_solares')) {
                $query->whereHas(
                    'energia',
                    fn($q) =>
                    $q->where('energia_paneles_solares', $request->energia_paneles_solares)
                );
            }

            if ($request->filled('energia_planta')) {
                $query->whereHas(
                    'energia',
                    fn($q) =>
                    $q->where('energia_planta', $request->energia_planta)
                );
            }

            return $query;
        }

        if ($categoria === 'edificios') {
            if ($request->filled('numero_edificios_min')) {
                $query->where('numero_edificios', '>=', $request->numero_edificios_min);
            }

            if ($request->filled('numero_edificios_max')) {
                $query->where('numero_edificios', '<=', $request->numero_edificios_max);
            }
            
            if ($request->filled('numero_edificios_exacto')) {
                $query->where('numero_edificios', '=', $request->numero_edificios_exacto);
            }

            return $query;
        }


        if (!empty($filtros)) {
            $query->whereHas($categoria, function ($q) use ($filtros) {
                foreach ($filtros as $campo => $valor) {
                    $q->where($campo, 1);
                }
            });
        }


        return $query;
    }

    private function atributosPorCategoria(string $categoria): array
    {
        return match ($categoria) {
            'energia' => [
                'suministro_energia',
                'energia_paneles_solares',
                'energia_planta',
            ],
            'drenaje' => [
                'drenaje_publico',
                'fosa_septica',
                'planta_tratamiento',
                'descarga_otro',
                'separacion_aguas',
            ],

            'agua' => [
                'agua_red_publica',
                'agua_pozo',
                'agua_cuerpo',
                'agua_pipas',
                'agua_otro',
                'cisterna',
                'tinacos',
                'tanque',
                'almacenamiento_otro',
            ],

            'obras' => [
                'rehabilitacion_realizada',
                'rehabilitacion_impermeabilizacion',
                'rehabilitacion_albanileria',
                'rehabilitacion_pintura',
                'rehabilitacion_red_hidraulica',
                'rehabilitacion_red_sanitaria',
                'rehabilitacion_esctructural',
                'obras_nuevas',
                'construccion_educativa',
                'construccion_deportiva',
                'construccion_sanitaria',
                'construccion_complementos',
                'construccion_otro',
            ],

            'superficie' => [
                'superficie',
            ],

            'accesibilidad' => [
                'infraestructura_discapacidad',
                'sin_infraestructura_discapacidad',
                'equipo_discapacidad_categoria',
            ],

            'estado_conservacion' => [
                'estado_red_hidraulica',
                'estado_instalacion_sanitaria',
                'estado_instalacion_electrica',
            ],

            'sanitario' => [
                'estado_banos',
                'banos_hombres_min',
                'banos_mujeres_min',
                'lavamanos_min',
                'estado_lavamanos',
                'tomas_bebederos_min',
                'estado_bebederos',
            ],

            'seguridad' => [
                'proteccion_civil',
                'barda_completa',
                'estado_barda',
                'estado_cerco',
            ],

            'edificios' => [
            'numero_edificios_min', 
            'numero_edificios_max',
            'numero_edificios_exacto'
            ],

            default => [],
        };
    }
}
