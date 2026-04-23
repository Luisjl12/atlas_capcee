<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComparacionEdificio extends Model
{
    protected $table= 'comparacion_edificios'; 
    protected $primarykey='id'; 
    protected $fillable = [
        'cct', 
        'numero_edificios', 
        'fuente', 
        'descripcion_edificios',
    ];

    public $timestamps=false;

    public function plantel()
    {
        return $this -> belongsTo(Pllantel::class, 'cct', 'cct');
    }
}
