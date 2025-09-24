<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InmuebleSanitarios extends Model
{
    protected $table = 'inmueble_sanitarios';

    protected $fillable = [
        'cct',
        'banos_hombres',
        'banos_mujeres',
        'banos_mujres',
        'banos_mixtos',
        'total_sanitarios',
        'sanitarios_ambos',
        'lavamanos',
        'tomas_bebederos',
        'banos_discapacitados',
        'estado_instalacion_sanitaria',
        'estado_banos',
        'estado_minigitorios',
        'estado_lavamanos',
        'estado_bebederos',

    ];
    public function plantel()
    {
        return $this->belongsTo(Plantel::class, 'cct', 'cct');
    }
}
