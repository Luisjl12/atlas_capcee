<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EspacioArea extends Model

{
    protected $table = 'espacios_areas';
    protected $fillable = [
        'cct',
        'nombre_espacio',
        'cantidad',
        'estado_conservacion'
    ];


    public function plantel()
    {
        return $this->belongsTo(Plantel::class, 'cct', 'cct');
    }
}
