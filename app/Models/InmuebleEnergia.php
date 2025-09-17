<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InmuebleEnergia extends Model
{
    protected $table = 'inmueble_energia';

    protected $fillable = [
        'cct',
        'energia_red_contrato',
        'energia_red_sin_contrato',
        'energia_planta',
        'energia_paneles_solares',
        'sin_energia',
        'gas_natural',
        'gas_estacionario',
        'gas_cilindro',
        'sin_gas',
    ];

    public function plantel()
    {
        return $this->belongsTo(Plantel::class, 'cct', 'cct');
    }
}
