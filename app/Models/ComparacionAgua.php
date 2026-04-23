<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComparacionAgua extends Model
{
    protected $table = 'comparacion_agua'; 

    protected $fillable = [
        'cct', 
        'agua_red_publica', 
        'agua_pozo', 
        'agua_cuerpo', 
        'agua_pipas', 
        'agua_otro', 
        'cisterna', 
        'tinacos', 
        'tanque',
        'almacenamiento_otro', 
        'estado_red_hidraulica', 
    ]; 

    public $timestamps = false; 

    public function inmuebleAgua()
    {
        return $this->belongsTo(InmuebleAgua::class, 'cct', 'cct'); 
    }
}
