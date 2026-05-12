<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProyectoInversion extends Model
{
    protected $table = 'proyectos_inversion';

    protected $primaryKey = 'id';

    protected $fillable = [
        'folio_ppi',
        'municipio',
        'nombre_proyecto',
        'monto_inversion',
        'inicio',
        'termino',
        'inicio_dif',
        'termino_dif',
        'av_fin_prog',
        'av_fin_real',
        'av_fis_prog',
        'av_fis_real',
        'empresa',
        'no_contrato',
        'monto_contratado',
        'plazo_ejec_dias',
        'estatus_general',
        'estatus_finanzas',
        'obs_finanzas',
        'estatus_admin',
        'obs_admin',
        'notificacion',
        'usuario_notif',
        'fecha_notif',
        'latitud',
        'longitud',
        'cct', 
        'modulo'
    ];
}
