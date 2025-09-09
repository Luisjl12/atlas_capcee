<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleServicio extends Model
{
    protected $table = 'detalle_servicios';
    protected $fillable = [
        'cct',
        'electricidad_contrato',
        'telefonia_fija',
        'internet_acceso',
        'gas_tipo',
        'internet_tipo',
        'observaciones',
    ];

    public function plantel()
    {
        return $this->belongsTo(Plantel::class, 'cct', 'cct');
    }
}
