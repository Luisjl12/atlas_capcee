<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'nombre_completo',
        'correo_electronico',
        'telefono_contacto',
        'ultima_conexion',
        'password_hash',
        'estado',
        'role_id'
    ];

    protected $hidden = [
        'password_hash',
    ];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function getEmailForPasswordReset()
    {
        return $this->correo_electronico;
    }
    public function rol()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function tieneRol($nombreRol)
    {
        return optional($this->rol)->nombre_rol === $nombreRol;
    }
}
