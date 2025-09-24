<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Macroregion extends Model
{
    protected $table = 'macroregiones';

    protected $fillable = ['nombre_macroregiones'];

    public function planteles()
    {
        return $this->hasMany(Plantel::class);
    }
}
