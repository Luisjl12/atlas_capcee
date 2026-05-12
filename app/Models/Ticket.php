<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory; 
    protected $table = 'tickets';
    
    protected $fillable = [
        'folio', 
        'numero_oficio', 
        'areas_turnadas', 
        'quien_atiende', 
        'anexo', 
        'fecha_oficialia', 
        'fecha_dfe', 
        'estatus'
    ]; 

    public function Solicitante()
    {
        return $this -> hasOne(Solicitante::class); 
    }
}  
