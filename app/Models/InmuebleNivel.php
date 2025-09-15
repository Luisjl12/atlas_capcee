<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InmuebleNivel extends Model
{
    protected $table = 'inmueble_nivel';

    protected $fillable = [
        'cct',
        'nivel',
        'imparte',
    ];

    public $timestamps = false;

    public function inmueble()
    {
        return $this->belongsTo(Plantel::class, 'cct', 'cct');
    }
}
