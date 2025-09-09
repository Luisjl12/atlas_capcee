<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleHidrosanitario extends Model
{
    protected $table = 'detalle_hidrosanitario';
    protected $fillable = [
        'cct',
        'fuente_agua',
        'tipo_drenaje',
        'almacenamiento_agua',
        'sanitarios_hombres_wc',
        'sanitarios_hombres_lavabos',
        'sanitarios_mujeres_wc',
        'sanitarios_mujeres_lavabos',
        'observaciones',
    ];
    public function plantel()
    {
        return $this->belongsTo(Plantel::class, 'cct', 'cct');
    }
}
