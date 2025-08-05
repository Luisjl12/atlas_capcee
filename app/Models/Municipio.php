<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $fillable = ['nombre_municipio'];
    public function planteles()
    {
        return $this->hasMany(Plantel::class, 'id_municipio');
    }
}
