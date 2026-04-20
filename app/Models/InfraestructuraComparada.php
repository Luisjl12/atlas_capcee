<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfraestructuraComparada extends Model
{
    protected $table = 'infraestructura_comparada';

    protected $fillable = [
        'cct',
        'nivel',
        'imparte',
        'fuente',
    ];

    public $timestamps = false; // porque tu tabla sí tiene created_at

    public function inmuebleNivel()
    {
        return $this->belongsTo(InmuebleNivel::class, 'cct', 'cct');
    }
}
