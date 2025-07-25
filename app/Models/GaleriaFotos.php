<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GaleriaFotos extends Model
{
    protected $table = 'galeria_fotos';

    protected $fillable = [
        'cct',
        'id_espacio',
        'nombre_foto_original',
        'nombre_foto_sistema',
        'ruta_foto',
        'descripcion_foto',
        'fecha_subida',
        'id_usuario_subio'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_subio');
    }
    public function plantel()
    {
        return $this->belongsTo(Plantel::class, 'cct', 'cct');
    }
}
