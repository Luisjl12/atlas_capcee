<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitante extends Model
{
    use HasFactory;

    protected $table = 'solicitantes'; 

    protected $fillable=[
        'ticket_id',
        'nombre_solicitante',
        'cargo_solicitante',
        'organismo_dependencia',
        'telefono_solicitante',
        'correo_solicitante',
        'persona_turna',
        'cargo_turna',
        'telefono_turna',
        'correo_turna',
        'clave_cct',
        'nivel', 
        'modalidad', 
        'plantel', 
        'turno', 
        'numero_alumnos', 
        'numero_maestros', 
        'numero_aulas', 
    ];

    //Relacion con ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
