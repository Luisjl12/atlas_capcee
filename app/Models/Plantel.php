<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;

class Plantel extends Model implements Auditable
{
    use Notifiable, \OwenIt\Auditing\Auditable;

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
        'numero_edificios',


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
    public function niveles()
    {
        return $this->hasMany(\App\Models\InmuebleNivel::class, 'cct', 'cct');
    }

    public function superficies()
    {
        return $this->hasMany(InmuebleSuperficie::class, 'cct', 'cct');
    }

    public function agua()
    {
        return $this->hasOne(InmuebleAgua::class, 'cct', 'cct');
    }

    public function energia()
    {
        return $this->hasOne(InmuebleEnergia::class, 'cct', 'cct');
    }

    public function drenaje()
    {
        return $this->hasOne(InmuebleDrenaje::class, 'cct', 'cct');
    }

    public function sanitario()
    {
        return $this->hasOne(InmuebleSanitarios::class, 'cct', 'cct');
    }

    public function obras()
    {
        return $this->hasOne(InmuebleObras::class, 'cct', 'cct');
    }

    public function seguridad()
    {
        return $this->hasOne(InmuebleSeguridad::class, 'cct', 'cct');
    }

    public function macroregion()
    {
        return $this->belongsTo(Macroregion::class);
    }

    public function microregion()
    {
        return $this->belongsTo(Microregion::class);
    }


    public function auditorias()
    {
        return $this->morphMany(\OwenIt\Auditing\Models\Audit::class, 'auditable');
    }

    public function transformAudit(array $data): array
    {
        $tags = [];

        // Detectar cambios específicos
        if (isset($data['old_values']['nombre_escuela']) && isset($data['new_values']['nombre_escuela'])) {
            $tags[] = 'CambioNombre';
        }

        if (isset($data['old_values']['cct']) && isset($data['new_values']['cct'])) {
            $tags[] = 'CambioCCT';
        }

        if (isset($data['old_values']['turno']) && isset($data['new_values']['turno'])) {
            $tags[] = 'CambioTurno';
        }

        if (isset($data['old_values']['nivel_educativo']) && isset($data['new_values']['nivel_educativo'])) {
            $tags[] = 'CambioNivel';
        }

        if (isset($data['old_values']['sostenimiento']) && isset($data['new_values']['sostenimiento'])) {
            $tags[] = 'CambioSostenimiento';
        }
        //Tag municipios
        if (
            isset($data['old_values']['id_municipio']) &&
            isset($data['new_values']['id_municipio']) &&
            $data['old_values']['id_municipio'] != $data['new_values']['id_municipio']
        ) {
            $nombreAnterior = \App\Models\Municipio::find($data['old_values']['id_municipio'])?->nombre_municipio;
            $nombreNuevo = \App\Models\Municipio::find($data['new_values']['id_municipio'])?->nombre_municipio;

            $tags[] = 'CambioMunicipio';

            // Opcional: agregar nombres al detalle

        }
        //Tag localidades
        if (
            isset($data['old_values']['id_localidad']) &&
            isset($data['new_values']['id_localidad']) &&
            $data['old_values']['id_localidad'] != $data['new_values']['id_localidad']
        ) {
            $nombreAnterior = \App\Models\Localidad::find($data['old_values']['id_localidad'])?->nombre_localidad;
            $nombreNuevo = \App\Models\Localidad::find($data['new_values']['id_localidad'])?->nombre_localidad;

            $tags[] = 'CambioLocalidad';
        }
        //Tag Corde 
        if (
            isset($data['old_values']['id_corde']) &&
            isset($data['new_values']['id_corde']) &&
            $data['old_values']['id_corde'] != $data['new_values']['id_corde']
        ) {
            $nombreAnterior = \App\Models\Corde::find($data['old_values']['id_corde'])?->nombre_corde;
            $nombreNuevo = \App\Models\Corde::find($data['new_values']['id_corde'])?->nombre_corde;
            $tags[] = 'cambioCorde';
        }
        //Tag Calle y numero
        if (isset($data['old_values']['domicilio_calle_numero']) && isset($data['new_values']['domicilio_calle_numero'])) {
            $tags[] = 'CambioDomicilio';
        }

        //Tag domiclio_colonia
        if (isset($data['old_values']['domicilio_colonia']) && isset($data['new_values']['domicilio_colonia'])) {
            $tags[] = 'cambio Domicilio Colonia';
        }

        // Agregar el rol del usuario como tag adicional
        $usuario = $this->resolveUser();
        if ($usuario && $usuario->rol) {
            $tags[] = strtoupper($usuario->rol->nombre_rol);
        }

        // Convertir los tags a JSON para evitar errores de tipo
        if (!empty($tags)) {
            $data['tags'] = json_encode($tags);
        }

        return $data;
    }
}
