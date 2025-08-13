<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Corde extends Model
{
    protected $table = 'cordes';

    public function planteles()
    {
        return $this->hasMany(Plantel::class, 'id_corde');
    }
}
