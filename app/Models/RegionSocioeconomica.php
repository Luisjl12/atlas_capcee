<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegionSocioeconomica extends Model
{

    public $timestamps = false;

    protected $table = 'region_socioeconomica';
    protected $fillable = ['nombre'];

    public function planteles()
    {
        return $this->hasMany(Plantel::class, 'region_socioeconomica_id');
    }
}
