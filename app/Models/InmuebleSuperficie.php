<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InmuebleSuperficie extends Model
{
    protected $table = 'inmueble_superficie';

    protected $fillable = ['cct', 'rango', 'aplica'];

    public function plantel()
    {
        return $this->belongsTo(Plantel::class, 'cct', 'cct');
    }
}
