<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Corde extends Model
{
    public function planteles()
    {
        return $this->hasMany(Plantel::class);
    }
}
