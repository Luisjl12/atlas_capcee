<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    public function planteles()
    {
        return $this->hasMany(Plantel::class);
    }
}
