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
