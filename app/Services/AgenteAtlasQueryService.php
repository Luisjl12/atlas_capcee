<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AgenteAtlasQueryService
{
    private const T_PLANTELES     = 'planteles';
    private const T_PROYECTOS     = 'proyectos_inversion';
    private const T_MUNICIPIOS    = 'municipios';
    private const T_MACROREGIONES = 'macroregiones';
    private const T_ENERGIA       = 'inmueble_energia';
    private const T_AGUA          = 'inmueble_agua';
    private const T_DRENAJE       = 'inmueble_drenaje';
    private const T_SANITARIOS    = 'inmueble_sanitarios';
    private const T_SERVICIOS     = 'detalle_servicios';

    private const LIMIT = 10;

    public function consultarInfraestructura(array $params): array
    {
        $p  = self::T_PLANTELES;
        $m  = self::T_MUNICIPIOS;
        $e  = self::T_ENERGIA;
        $a  = self::T_AGUA;
        $d  = self::T_DRENAJE;
        $s  = self::T_SERVICIOS;
        $mr = self::T_MACROREGIONES;

        $query = DB::table($p)
            ->leftJoin($e,  "{$p}.cct", '=', "{$e}.cct")
            ->leftJoin($a,  "{$p}.cct", '=', "{$a}.cct")
            ->leftJoin($d,  "{$p}.cct", '=', "{$d}.cct")
            ->leftJoin($s,  "{$p}.cct", '=', "{$s}.cct")
            ->leftJoin($m,  "{$p}.id_municipio", '=', "{$m}.id")
            ->leftJoin($mr, "{$p}.macroregion_id", '=', "{$mr}.id")
            ->select([
                "{$p}.id", "{$p}.cct", "{$p}.nombre_escuela",
                "{$p}.nivel_educativo", "{$p}.turno", "{$p}.estatus_plantel",
                "{$p}.macroregion_id", "{$mr}.nombre_macroregion",
                "{$m}.nombre_municipio as municipio",
                "{$e}.energia_paneles_solares", "{$e}.sin_energia", "{$e}.energia_red_contrato",
                "{$a}.agua_red_publica", "{$a}.cisterna",
                "{$d}.drenaje_publico", "{$d}.fosa_septica",
                "{$s}.internet_acceso",
            ]);

        if (!empty($params['energia_solar']))   $query->where("{$e}.energia_paneles_solares", 1);
        if (!empty($params['sin_energia']))      $query->where("{$e}.sin_energia", 1);
        if (!empty($params['internet']))         $query->where("{$s}.internet_acceso", 1);
        if (!empty($params['agua_red_publica'])) $query->where("{$a}.agua_red_publica", 1);
        if (!empty($params['cisterna']))         $query->where("{$a}.cisterna", 1);
        if (!empty($params['drenaje_publico']))  $query->where("{$d}.drenaje_publico", 1);
        if (!empty($params['fosa_septica']))     $query->where("{$d}.fosa_septica", 1);
        if (!empty($params['municipio']))        $query->where("{$m}.nombre_municipio", 'like', '%'.$params['municipio'].'%');
        if (!empty($params['macroregion_id']))   $query->where("{$p}.macroregion_id", $params['macroregion_id']);

        $totalEncontrado = (clone $query)->count("{$p}.id");

        $rows = $query->orderBy("{$p}.nombre_escuela")->limit(self::LIMIT)
            ->get()->map(fn($r) => (array)$r)->toArray();

        if (empty($rows)) {
            return ['resultado' => 'No se encontraron planteles con los filtros indicados.', 'total_encontrado' => 0];
        }

        $estadisticas = (clone $query)->selectRaw("
            COUNT(*)                          as total,
            SUM({$e}.energia_paneles_solares) as con_paneles_solares,
            SUM({$e}.sin_energia)             as sin_energia,
            SUM({$a}.agua_red_publica)        as con_agua_red,
            SUM({$a}.cisterna)                as con_cisterna,
            SUM({$d}.drenaje_publico)         as con_drenaje_publico,
            SUM({$d}.fosa_septica)            as con_fosa_septica,
            SUM({$s}.internet_acceso)         as con_internet
        ")->first();

        return [
            'total_encontrado'       => $totalEncontrado,
            'nota'                   => $totalEncontrado > self::LIMIT
                ? "Se muestran los primeros " . self::LIMIT . " de {$totalEncontrado} planteles." : null,
            'estadisticas_servicios' => [
                'con_paneles_solares' => (int)($estadisticas->con_paneles_solares ?? 0),
                'sin_energia'         => (int)($estadisticas->sin_energia ?? 0),
                'con_agua_red'        => (int)($estadisticas->con_agua_red ?? 0),
                'con_cisterna'        => (int)($estadisticas->con_cisterna ?? 0),
                'con_drenaje_publico' => (int)($estadisticas->con_drenaje_publico ?? 0),
                'con_fosa_septica'    => (int)($estadisticas->con_fosa_septica ?? 0),
                'con_internet'        => (int)($estadisticas->con_internet ?? 0),
            ],
            'planteles_ejemplo'      => $rows,
        ];
    }

    public function consultarObrasCapcee(array $params): array
    {
        $pr = self::T_PROYECTOS;
        $pl = self::T_PLANTELES;

        $query = DB::table($pr)
            ->leftJoin($pl, "{$pr}.cct", '=', "{$pl}.cct")
            ->select([
                "{$pr}.id", "{$pr}.cct", "{$pr}.folio_ppi",
                "{$pr}.nombre_proyecto", "{$pr}.modulo", "{$pr}.municipio",
                "{$pr}.inicio", "{$pr}.termino",
                "{$pr}.monto_inversion", "{$pr}.monto_contratado",
                "{$pr}.av_fis_real as avance_fisico_pct",
                "{$pr}.av_fin_real as avance_financiero_pct",
                "{$pr}.estatus_general", "{$pr}.empresa", "{$pr}.no_contrato",
                "{$pl}.macroregion_id",
            ]);

        if (!empty($params['tipo_obra'])) {
            $val = '%' . $params['tipo_obra'] . '%';
            $query->where(fn($q) => $q->where("{$pr}.modulo", 'like', $val)
                                      ->orWhere("{$pr}.nombre_proyecto", 'like', $val));
        }
        if (!empty($params['anio_inicio']))  $query->whereYear("{$pr}.inicio", (int)$params['anio_inicio']);
        if (!empty($params['cct']))          $query->where("{$pr}.cct", trim($params['cct']));
        if (!empty($params['municipio']))    $query->where("{$pr}.municipio", 'like', '%'.$params['municipio'].'%');
        if (!empty($params['macroregion_id'])) $query->where("{$pl}.macroregion_id", $params['macroregion_id']);

        $totalEncontrado = (clone $query)->count("{$pr}.id");

        $agregados = (clone $query)->selectRaw("
            COUNT(*)                     as total,
            ROUND(AVG(av_fis_real), 1)   as promedio_avance_fisico,
            ROUND(AVG(av_fin_real), 1)   as promedio_avance_financiero,
            SUM(monto_contratado)        as monto_total_contratado,
            SUM(monto_inversion)         as monto_total_inversion
        ")->first();

        $porEstatus = (clone $query)
            ->selectRaw('estatus_general, COUNT(*) as total')
            ->groupBy('estatus_general')->orderByDesc('total')
            ->get()->pluck('total', 'estatus_general')->toArray();

        $rows = $query->orderByDesc("{$pr}.inicio")->limit(self::LIMIT)
            ->get()->map(fn($r) => (array)$r)->toArray();

        if (empty($rows)) {
            return ['resultado' => 'No se encontraron obras con los filtros indicados.', 'total_encontrado' => 0];
        }

        return [
            'total_encontrado'         => $totalEncontrado,
            'nota'                     => $totalEncontrado > self::LIMIT
                ? "Se muestran las primeras " . self::LIMIT . " de {$totalEncontrado} obras." : null,
            'resumen_agregado'         => [
                'promedio_avance_fisico_pct'     => ($agregados->promedio_avance_fisico ?? 0) . '%',
                'promedio_avance_financiero_pct' => ($agregados->promedio_avance_financiero ?? 0) . '%',
                'monto_total_contratado'         => '$' . number_format($agregados->monto_total_contratado ?? 0, 2, '.', ','),
                'monto_total_inversion'          => '$' . number_format($agregados->monto_total_inversion ?? 0, 2, '.', ','),
            ],
            'distribucion_por_estatus' => $porEstatus,
            'obras_ejemplo'            => $rows,
        ];
    }

    public function resumenEstadisticas(): array
    {
        $totalPlanteles = DB::table(self::T_PLANTELES)->count();
        $proyectos      = DB::table(self::T_PROYECTOS);

        $totalProyectos           = (clone $proyectos)->count();
        $avanceFisicoPromedio     = round((clone $proyectos)->avg('av_fis_real') ?? 0, 1);
        $avanceFinancieroPromedio = round((clone $proyectos)->avg('av_fin_real') ?? 0, 1);
        $montoTotalContratado     = (clone $proyectos)->sum('monto_contratado') ?? 0;

        $porEstatus = (clone $proyectos)
            ->selectRaw('estatus_general, COUNT(*) as total')
            ->whereNotNull('estatus_general')
            ->groupBy('estatus_general')->orderByDesc('total')
            ->get()->pluck('total', 'estatus_general')->toArray();

        return [
            'total_planteles'                => $totalPlanteles,
            'total_proyectos_inversion'      => $totalProyectos,
            'proyectos_por_estatus'          => $porEstatus,
            'promedio_avance_fisico_pct'     => $avanceFisicoPromedio . '%',
            'promedio_avance_financiero_pct' => $avanceFinancieroPromedio . '%',
            'monto_total_contratado'         => '$' . number_format($montoTotalContratado, 2, '.', ','),
        ];
    }

    public function resumenPorFiltro(array $params): array
    {
        $pr = self::T_PROYECTOS;
        $pl = self::T_PLANTELES;

        $query = DB::table($pr)->leftJoin($pl, "{$pr}.cct", '=', "{$pl}.cct");

        if (!empty($params['macroregion_id'])) $query->where("{$pl}.macroregion_id", $params['macroregion_id']);
        if (!empty($params['municipio']))      $query->where("{$pr}.municipio", 'like', '%'.$params['municipio'].'%');
        if (!empty($params['anio_inicio']))    $query->whereYear("{$pr}.inicio", $params['anio_inicio']);

        $agregados = (clone $query)->selectRaw("
            COUNT(*)                   as total_obras,
            ROUND(AVG(av_fis_real), 1) as avance_fisico_promedio,
            ROUND(AVG(av_fin_real), 1) as avance_financiero_promedio,
            SUM(monto_contratado)      as monto_total
        ")->first();

        $porEstatus = (clone $query)
            ->selectRaw('estatus_general, COUNT(*) as total')
            ->groupBy('estatus_general')->orderByDesc('total')
            ->get()->pluck('total', 'estatus_general')->toArray();

        $porTipo = (clone $query)
            ->selectRaw('modulo, COUNT(*) as total, ROUND(AVG(av_fis_real),1) as avance_promedio')
            ->whereNotNull('modulo')->groupBy('modulo')->orderByDesc('total')->limit(10)
            ->get()->map(fn($r) => (array)$r)->toArray();

        return [
            'filtros_aplicados' => $params,
            'totales'           => [
                'obras'                      => $agregados->total_obras ?? 0,
                'avance_fisico_promedio'     => ($agregados->avance_fisico_promedio ?? 0) . '%',
                'avance_financiero_promedio' => ($agregados->avance_financiero_promedio ?? 0) . '%',
                'monto_total_contratado'     => '$' . number_format($agregados->monto_total ?? 0, 2, '.', ','),
            ],
            'por_estatus'       => $porEstatus,
            'por_tipo_de_obra'  => $porTipo,
        ];
    }
}