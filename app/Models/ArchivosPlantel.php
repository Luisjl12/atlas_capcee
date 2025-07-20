<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchivosPlantel extends Model
{
    protected $table = 'archivos_plantel';
    protected $fillable = [
        'cct',
        'nombre_archivo_original',
        'nombre_archivo_sistema',
        'ruta_archivo',
        'tipo_documento',
        'descripcion',
        'fecha_subido',
        'mime_type',
        'tamano_byte',
        'id_usuario_subio',
        'fecha_actualizacion_seccion'
    ];

    public function plantel()
    {
        return $this->belongsTo(Plantel::class, 'cct', 'cct');
    }
}
