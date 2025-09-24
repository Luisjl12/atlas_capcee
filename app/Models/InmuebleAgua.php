<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InmuebleAgua extends Model
{
    protected $table = 'inmueble_agua';

    protected $fillable = [
        'cct',
        'agua_red_publica',
        'agua_pozo',
        'agua_cuerpo',
        'agua_pipas',
        'agua_otro',
        'cisterna',
        'tinacos',
        'tanque',
        'almacenamiento_otro',
        'estado_red_hidraulica',
    ];

    public function plantel()
    {
        return $this->belongsTo(Plantel::class, 'cct', 'cct');
    }
}
