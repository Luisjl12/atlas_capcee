<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Corde extends Model
{
    protected $table = 'cordes';
    protected $fillable = ['nombre_corde'];

    public function planteles()
    {
        return $this->hasMany(Plantel::class, 'id_corde');
    }
}
