<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InmuebleDrenaje extends Model
{
    protected $table = 'inmueble_drenaje';

    protected $fillable = [
        'cct',
        'drenaje_publico',
        'fosa_septica',
        'planta_tratamiento',
        'descarga_otro',
        'separacion_aguas',
        'sin_separacion_de_agua'

    ];

    public function planteles()
    {
        return $this->belongsTo(Plantel::class, 'cct', 'cct');
    }
}
