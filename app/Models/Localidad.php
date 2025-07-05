<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Localidad extends Model
{
    protected $table = 'localidades'; // 👈 Corrige el nombre de la tabla

    // Agrega si tienes campos llenables:
    protected $fillable = ['nombre_localidad'];

    public function planteles()
    {
        return $this->hasMany(Plantel::class);
    }
}
