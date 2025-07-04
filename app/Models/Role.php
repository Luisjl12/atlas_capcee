<?php
//Modelo para la tabla "roles"
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['nombre_rol'];

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'role_id');
    }
}
