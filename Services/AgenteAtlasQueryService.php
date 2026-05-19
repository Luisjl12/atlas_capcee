<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * AgenteAtlasQueryService
 *
 * Ejecuta consultas parametrizadas y seguras contra la BD del Atlas.
 * Ajustado 100% al esquema real de CAPCEE (proyectos_inversion, planteles, municipios).
 */
class AgenteAtlasQueryService
{
    /* ── Nombres de tablas reales ─────────── */
    private const T_OBRAS      = 'proyectos_inversion'; 
    private const T_PLANTELES  = 'planteles';       
    private const T_MUNICIPIOS = 'municipios';      

    /* ── Límite máximo de filas por consulta ─────────────── */
    private const MAX_ROWS = 20;

    /* ──────────────────────────────────────────────────────
     * 1. buscar_obras
     * Busca obras con filtros opcionales de estatus y municipio.
     * ────────────────────────────────────────────────────── */
    public function buscarObras(array $params): array
    {
        $query = DB::table(self::T_OBRAS)
            ->select([
                'id',
                'folio_ppi',            
                'nombre_proyecto',      
                'municipio',            
                'estatus_general',      
                'monto_contratado',     
                'av_fis_real as avance_fisico',       
                'av_fin_real as avance_financiero',   
                'inicio as fecha_inicio',        
                'termino as fecha_fin_programada',
            ]);

        if (!empty($params['estatus'])) {
            $query->where('estatus_general', $params['estatus']);
        }

        if (!empty($params['municipio'])) {
            $query->where('municipio', 'like', '%' . $params['municipio'] . '%');
        }

        // (Nota: Se omitió el ciclo escolar porque no está en la tabla proyectos_inversion)

        $rows = $query->orderByDesc('inicio')
                      ->limit(self::MAX_ROWS)
                      ->get()
                      ->toArray();

        if (empty($rows)) {
            return ['resultado' => 'No se encontraron obras con los filtros indicados.'];
        }

        return [
            'total_mostrado' => count($rows),
            'obras'          => array_map(fn($r) => (array) $r, $rows),
        ];
    }

    /* ──────────────────────────────────────────────────────
     * 2. ver_detalle_obra
     * Devuelve el detalle completo de una obra por ID o folio.
     * ────────────────────────────────────────────────────── */
    public function verDetalleObra(array $params): array
    {
        $query = DB::table(self::T_OBRAS);

        if (!empty($params['id'])) {
            $query->where('id', (int) $params['id']);
        } elseif (!empty($params['folio_ppi'])) {
            $query->where('folio_ppi', $params['folio_ppi']); 
        } elseif (!empty($params['nombre'])) {
            $query->where('nombre_proyecto', 'like', '%' . $params['nombre'] . '%'); 
        } else {
            return ['error' => 'Debes indicar id, folio_ppi o nombre de la obra.'];
        }

        $obra = $query->first();

        if (!$obra) {
            return ['resultado' => 'No se encontró ninguna obra con ese criterio.'];
        }

        return ['obra' => (array) $obra];
    }

    /* ──────────────────────────────────────────────────────
     * 3. listar_planteles
     * Lista planteles filtrados usando LEFT JOIN para nombre de municipio.
     * ────────────────────────────────────────────────────── */
    public function listarPlanteles(array $params): array
    {
        $query = DB::table(self::T_PLANTELES)
            ->leftJoin(self::T_MUNICIPIOS, self::T_PLANTELES . '.id_municipio', '=', self::T_MUNICIPIOS . '.id')
            ->select([
                self::T_PLANTELES . '.id',
                self::T_PLANTELES . '.cct',               
                self::T_PLANTELES . '.nombre_escuela as nombre_plantel',    
                self::T_MUNICIPIOS . '.nombre_municipio as municipio',        
                self::T_PLANTELES . '.macroregion_id',       
                self::T_PLANTELES . '.nivel_educativo',  
                self::T_PLANTELES . '.latitud', 
                self::T_PLANTELES . '.longitud',        
            ]);

        if (!empty($params['municipio'])) {
            $query->where(self::T_MUNICIPIOS . '.nombre_municipio', 'like', '%' . $params['municipio'] . '%');
        }

        if (!empty($params['macroregion'])) {
            $query->where(self::T_PLANTELES . '.macroregion_id', $params['macroregion']); 
        }

        if (!empty($params['nivel_educativo'])) {
            $query->where(self::T_PLANTELES . '.nivel_educativo', $params['nivel_educativo']); 
        }

        $rows = $query->orderBy(self::T_PLANTELES . '.nombre_escuela')
                      ->limit(self::MAX_ROWS)
                      ->get()
                      ->toArray();

        if (empty($rows)) {
            return ['resultado' => 'No se encontraron planteles con los filtros indicados.'];
        }

        return [
            'total_mostrado' => count($rows),
            'planteles'      => array_map(fn($r) => (array) $r, $rows),
        ];
    }

    /* ──────────────────────────────────────────────────────
     * 4. resumen_estadisticas
     * Devuelve conteos y promedios globales para el dashboard.
     * ────────────────────────────────────────────────────── */
    public function resumenEstadisticas(): array
    {
        $obras = DB::table(self::T_OBRAS);

        $totalObras      = (clone $obras)->count();
        $obrasPorEstatus = (clone $obras)
            ->selectRaw('estatus_general as estatus, COUNT(*) as total')  
            ->groupBy('estatus_general')
            ->get()
            ->pluck('total', 'estatus')
            ->toArray();

        $promedioFisico     = round((clone $obras)->avg('av_fis_real') ?? 0, 1);    
        $promedioFinanciero = round((clone $obras)->avg('av_fin_real') ?? 0, 1); 
        $montoTotal         = (clone $obras)->sum('monto_contratado') ?? 0;              

        $totalPlanteles = DB::table(self::T_PLANTELES)->count();

        return [
            'total_obras'                => $totalObras,
            'obras_por_estatus'          => $obrasPorEstatus,
            'promedio_avance_fisico'     => $promedioFisico . '%',
            'promedio_avance_financiero' => $promedioFinanciero . '%',
            'monto_total_contratos'      => '$' . number_format($montoTotal, 2),
            'total_planteles'            => $totalPlanteles,
        ];
    }

    /* ──────────────────────────────────────────────────────
     * 5. listar_municipios
     * Devuelve lista de municipios disponibles (para filtros).
     * ────────────────────────────────────────────────────── */
    public function listarMunicipios(): array
    {
        $municipios = DB::table(self::T_MUNICIPIOS)
            ->select(['id', 'nombre_municipio as nombre']) 
            ->orderBy('nombre_municipio')
            ->get()
            ->toArray();

        return [
            'municipios' => array_map(fn($r) => (array) $r, $municipios),
        ];
    }
}
