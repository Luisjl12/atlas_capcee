<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteInfraescolar extends Model
{
    use HasFactory;

    // Le decimos a Laravel el nombre exacto de la tabla para que no intente adivinar
    protected $table = 'reportes_infraescolar';

    protected $fillable = [
        'titulo',
        'descripcion',
        'archivo_pdf',
        'datos_grafica',
        'creado_por'
    ];
}