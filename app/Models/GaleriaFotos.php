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
}
