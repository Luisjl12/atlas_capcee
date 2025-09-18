<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InmuebleObras extends Model
{
    protected $table = 'inmueble_obras';


    protected $fillable = [
        'cct',
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
        'construccion_total',
        'construccion_otro',
    ];

    public function plantel()
    {
        return $this->belongsTo(Plantel::class, 'cct', 'cct');
    }
}
