<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InmuebleSeguridad extends Model
{
    protected $table = 'inmueble_seguridad';

    protected $fillable = [
        'cct',
        'proteccion_civil',
        'barda_completa',
        'barda_incompleta',
        'infraestructura_discapacidad',
        'sin_infraestructura_discapacidad',
        'equipo_discapacidad_total',
        'estado_barda',
        'estado_cerco',
    ];

    public function plantel()
    {
        return $this->belongsTo(Plantel::class, 'cct', 'cct');
    }
}
