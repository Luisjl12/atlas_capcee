<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Plantel extends Model
{
    use Notifiable;

    protected $table = "planteles";
    protected $primaryKey = "id";

    protected $fillable = [
        'cct',
        'nombre_escuela',
        'nivel_educativo',
        'turno',
        'sostenimiento',
        'domicilio_calle_numero',
        'domicilio_colonia',
        'domicilio_cp',
        'latitud',
        'longitud',
        'id_municipio',
        'id_localidad',
        'id_corde'
    ];

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'id_municipio');
    }

    public function localidad()
    {
        return $this->belongsTo(Localidad::class, 'id_localidad');
    }

    public function corde()
    {
        return $this->belongsTo(Corde::class, 'id_corde');
    }
}
