<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Microregion extends Model
{
    protected $table = 'microregiones';

    protected $fillable = ['nombre_microregiones'];

    public function planteles()
    {
        return $this->hasMany(Plantel::class);
    }
}
