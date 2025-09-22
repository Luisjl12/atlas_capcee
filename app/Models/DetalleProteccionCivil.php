<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleProteccionCivil extends Model
{

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
        'extintores_vigentes',  // 
        'extintores_ultima_recarga',
        'brigadas_conformadas',
        'observaciones',
        'botiquin_existencia',
        'simulacros_ultimo_anio',
    ];

    public function plantel()
    {
        return $this->belongsTo(Plantel::class, 'cct', 'cct');
    }
}
