<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EscuelasCien extends Model
{
    protected $table='escuelas_al_100';
    
    protected $fillable = [
        'cct',
        'microregion', 
        'municipio', 
        'localidad', 
        'plantel', 
        'meta', 
        'monto', 
        'avance_final', 
        'latitud', 
        'longitud',
    ]; 
}
