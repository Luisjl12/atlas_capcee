<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class DetalleProteccionCivil extends Model implements Auditable
{
    use AuditableTrait;

    protected $table = 'detalle_proteccion_civil';
    protected $primaryKey = 'cct';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'cct',
        'programa_interno_pc',
        'programa_interno_pc_fecha',
        'alarma_sismica',
        'alarma_sismica_funcional',
        'senaletica_estado',
        'extintores_cantidad',
        'extintores_vigentes',
        'extintores_ultima_recarga',
        'brigadas_conformadas',
        'observaciones',
        'botiquin_existencia',
        'simulacros_ultimo_anio',
        'estado_techados',
    ];

    public function plantel()
    {
        return $this->belongsTo(Plantel::class, 'cct', 'cct');
    }

    public function transformAudit(array $data): array
    {
        $tags = [];

        // Booleanos
        foreach (['alarma_sismica', 'alarma_sismica_funcional', 'botiquin_existencia', 'brigadas_conformadas', 'extintores_vigentes'] as $campo) {
            if (
                isset($data['old_values'][$campo]) &&
                isset($data['new_values'][$campo]) &&
                $data['old_values'][$campo] != $data['new_values'][$campo]
            ) {
                $tags[] = 'Cambio ' . ucwords(str_replace('_', ' ', $campo));
            }
        }

        // Numéricos
        foreach (['extintores_cantidad', 'simulacros_ultimo_anio'] as $campo) {
            if (
                isset($data['old_values'][$campo]) &&
                isset($data['new_values'][$campo]) &&
                $data['old_values'][$campo] != $data['new_values'][$campo]
            ) {
                $tags[] = 'Cambio ' . ucwords(str_replace('_', ' ', $campo));
            }
        }

        // Fechas
        foreach (['programa_interno_pc_fecha', 'extintores_ultima_recarga'] as $campo) {
            if (
                isset($data['old_values'][$campo]) &&
                isset($data['new_values'][$campo]) &&
                $data['old_values'][$campo] != $data['new_values'][$campo]
            ) {
                $tags[] = 'Cambio ' . ucwords(str_replace('_', ' ', $campo));
            }
        }

        // Texto libre
        foreach (['observaciones', 'senaletica_estado', 'programa_interno_pc', 'estado_techados'] as $campo) {
            if (
                isset($data['old_values'][$campo]) &&
                isset($data['new_values'][$campo]) &&
                $data['old_values'][$campo] != $data['new_values'][$campo]
            ) {
                $tags[] = 'Cambio ' . ucwords(str_replace('_', ' ', $campo));
            }
        }

        if (!empty($tags)) {
            $data['tags'] = json_encode($tags);
        }

        return $data;
    }
}


