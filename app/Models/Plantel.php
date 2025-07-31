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
        'id_corde',
        'telefono_plantel',
        'correo_institucional',
        'nombre_director_registrado',
        'id_director_asignado',
        'accesibilidad_rampas',
        'accesibilidad_banos_adaptados',
        'accesibilidad_sanaletica_braille',
        'accesibilidad_otros',
        'total_alumnos',
        'total_docentes',
        'total_administrativos',
        'estatus_plantel',

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
    public function director()
    {
        return $this->belongsTo(Usuario::class, 'id_director_asignado');
    }
    public function espacios()
    {
        return $this->hasMany(EspacioArea::class, 'cct', 'cct');
    }

    public function detalleHidrosanitario()
    {
        return $this->hasOne(DetalleHidrosanitario::class, 'cct', 'cct');
    }
    public function detalleServicio()
    {
        return $this->hasOne(DetalleServicio::class, 'cct', 'cct');
    }
    public function detalleProteccionCivil()
    {
        return $this->hasOne(DetalleProteccionCivil::class, 'cct', 'cct');
    }
    public function fotosGaleria()
    {
        return $this->hasOne(GaleriaFotos::class, 'cct', 'cct');
    }
}
