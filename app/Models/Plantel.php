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
    
     public function regionSocioeconomica()
    {
        return $this->belongsTo(\App\Models\RegionSocioeconomica::class);
    }

    public function comparacionEdificios()
    {
        return $this->hasMany(comparacionEdificios::class, 'cct', 'cct'); 
    }

    public function transformAudit(array $data): array
    {
        $tags = [];

        // Detectar cambios específicos
        if (isset($data['old_values']['nombre_escuela']) && isset($data['new_values']['nombre_escuela'])) {
            $tags[] = 'Cambio de Nombre';
        }

        if (isset($data['old_values']['cct']) && isset($data['new_values']['cct'])) {
            $tags[] = 'Cambio de CCT';
        }

        if (isset($data['old_values']['turno']) && isset($data['new_values']['turno'])) {
            $tags[] = 'Cambio de Turno';
        }

        if (isset($data['old_values']['nivel_educativo']) && isset($data['new_values']['nivel_educativo'])) {
            $tags[] = 'Cambio de Nivel';
        }

        if (isset($data['old_values']['sostenimiento']) && isset($data['new_values']['sostenimiento'])) {
            $tags[] = 'Cambio de Sostenimiento';
        }
        //Tag municipios
        if (
            isset($data['old_values']['id_municipio']) &&
            isset($data['new_values']['id_municipio']) &&
            $data['old_values']['id_municipio'] != $data['new_values']['id_municipio']
        ) {
            $nombreAnterior = \App\Models\Municipio::find($data['old_values']['id_municipio'])?->nombre_municipio;
            $nombreNuevo = \App\Models\Municipio::find($data['new_values']['id_municipio'])?->nombre_municipio;

            $tags[] = 'Cambio de Municipio';

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

            $tags[] = 'Cambio de Localidad';
        }
        //Tag Corde 
        if (
            isset($data['old_values']['id_corde']) &&
            isset($data['new_values']['id_corde']) &&
            $data['old_values']['id_corde'] != $data['new_values']['id_corde']
        ) {
            $nombreAnterior = \App\Models\Corde::find($data['old_values']['id_corde'])?->nombre_corde;
            $nombreNuevo = \App\Models\Corde::find($data['new_values']['id_corde'])?->nombre_corde;
            $tags[] = 'cambio de Corde';
        }
        //Tag Calle y numero
        if (isset($data['old_values']['domicilio_calle_numero']) && isset($data['new_values']['domicilio_calle_numero'])) {
            $tags[] = 'Cambio de Domicilio';
        }

        //Tag domiclio_colonia
        if (isset($data['old_values']['domicilio_colonia']) && isset($data['new_values']['domicilio_colonia'])) {
            $tags[] = 'cambio Domicilio Colonia';
        }
        //Tag CP. 
        if (isset($data['old_values']['domicilio_cp']) && isset($data['new_values']['domicilio_cp'])) {
            $tags[] = 'cambio de Codigo postal';
        }

        //Cambio en la latitud
        if (isset($data['old_values']['latitud']) && isset($data['new_values']['latitud'])) {
            $tags[] = 'cambio de latitud';
        }

        //Cambio de logitud 
        if (isset($data['old_values']['longitud']) && isset($data['new_values']['longitud'])) {
            $tags[] = 'cambio de longitud';
        }

        //Cambio de contacto de telefono
        if (isset($data['old_values']['telefono_plantel']) && isset($data['new_values']['telefono_plantel'])) {
            $tags[] = 'cambio de telefono de contacto';
        }

        //Cambio de correo de contacto
        if (isset($data['old_values']['correo_institucional']) && isset($data['new_values']['correo_institucional'])) {
            $tags[] = 'cambio de correo institucional';
        }

        //Cambio de director asignado
        if (isset($data['old_values']['nombre_director_registrado']) && isset($data['new_values']['nombre_director_registrado'])) {
            $tags[] = 'Se agrego o cambio director asignado al plantel';
        }

        //Accesibilidad rampas 
        if (
            isset($data['old_values']['accesibilidad_rampas']) &&
            isset($data['new_values']['accesibilidad_rampas']) &&
            $data['old_values']['accesibilidad_rampas'] != $data['new_values']['accesibilidad_rampas']
        ) {
            $antes = $data['old_values']['accesibilidad_rampas'] ? 'Sí' : 'No';
            $despues = $data['new_values']['accesibilidad_rampas'] ? 'Sí' : 'No';

            $tags[] = 'Cambio en la accesibilidad en rampas';
        }

        //Accesibilidad baños adaptadaos 
        if (
            isset($data['old_values']['accesibilidad_banos_adaptados']) &&
            isset($data['new_values']['accesibilidad_banos_adaptados']) &&
            $data['old_values']['accesibilidad_banos_adaptados'] != $data['new_values']['accesibilidad_banos_adaptados']
        ) {
            $antes = $data['old_values']['accesibilidad_banos_adaptados'] ? 'Sí' : 'No';
            $despues = $data['new_values']['accesibilidad_banos_adaptados'] ? 'Sí' : 'No';

            $tags[] = 'Cambio en la accesibilidad en baños adaptados';
        }

        // Accesibilidad señaletica braile
        if (
            isset($data['old_values']['accesibilidad_sanaletica_braille']) &&
            isset($data['new_values']['accesibilidad_sanaletica_braille']) &&
            $data['old_values']['accesibilidad_sanaletica_braille'] != $data['new_values']['accesibilidad_sanaletica_braille']
        ) {
            $antes = $data['old_values']['accesibilidad_sanaletica_braille'] ? 'Sí' : 'No';
            $despues = $data['new_values']['accesibilidad_sanaletica_braille'] ? 'Sí' : 'No';

            $tags[] = 'Cambio en la accesibilidad de señaletica braille';
        }

        //Total de administrativos, docentes y alumnos
        if (
            isset($data['old_values']['total_alumnos']) &&
            isset($data['new_values']['total_alumnos']) &&
            $data['old_values']['total_alumnos'] != $data['new_values']['total_alumnos']
        ) {
            $tags[] = 'Cambio en el Total de Alumnos';
        }

        if (
            isset($data['old_values']['total_docentes']) &&
            isset($data['new_values']['total_docentes']) &&
            $data['old_values']['total_docentes'] != $data['new_values']['total_docentes']
        ) {
            $tags[] = 'Cambio en el Total de Docentes';
        }

        if (
            isset($data['old_values']['total_administrativos']) &&
            isset($data['new_values']['total_administrativos']) &&
            $data['old_values']['total_administrativos'] != $data['new_values']['total_administrativos']
        ) {
            $tags[] = 'Cambio en el Total de Administrativos';
        }

        //Cambio en el estatus de plantel
        if (
            isset($data['old_values']['estatus_plantel']) &&
            isset($data['new_values']['estatus_plantel']) &&
            $data['old_values']['estatus_plantel'] != $data['new_values']['estatus_plantel']
        ) {
            $tags[] = 'Cambio en el Estatus del Plantel';
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
