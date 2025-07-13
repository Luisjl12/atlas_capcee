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
    ];
    public function plantel()
    {
        return $this->belongsTo(Plantel::class, 'cct', 'cct');
    }
}
