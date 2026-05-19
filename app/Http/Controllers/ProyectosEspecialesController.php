<?php
//Controlador para el seguimiento de proyectos especiales
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Solicitante; 

class ProyectosEspecialesController extends Controller
{
    //Listar proyectos
    public function index()
    {
        $tickets = Ticket::orderBy('fecha_oficialia', 'desc')->get();

        return view('proyectos_especiales', compact('tickets')); 
    }
    //Crear nuevo proyecto para su seguimiento
    public function store(Request $request)
    {
        $validated = $request->validate([
            'folio' => 'required|string|unique:tickets,folio',
            'numero_oficio' => 'nullable|string',
            'areas_turnadas' => 'nullable|string',
            'quien_atiende' => 'nullable|string',
            'anexo' => 'nullable|boolean',
            'fecha_oficialia' => 'nullable|date',
            'fecha_dfe' => 'nullable|date',
        ]); 

        Ticket::create($validated);
        
        return redirect()->route('seguimiento-proyectos')
                         ->with('success', 'Ticket creado correctamente'); 
    }
    //Presentar vista del dictamen del proyecto
    public function dictamen($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('dictamen_ticket', compact('ticket'));
    }
    //Crear o editar campos del proyecto
    public function guardarDictamen(Request $request, $id)
    {

        //Ver que datos llegan
        //Buscar el ticket
        $ticket = Ticket::findOrFail($id); 

        //Validar y actualizar el estado del ticket 
        $ticketData= $request->validate([
            'estatus'=>'nullable|in:Aprobado, En revision, No aprobado ', 
        ]); 

        $ticket->update($ticketData); 

        //Validar los campos 
        $solicitanteData = $request->validate([
            'nombre_solicitante'   => 'required|string',
            'cargo_solicitante'    => 'nullable|string',
            'organismo_dependencia'=> 'nullable|string',
            'telefono_solicitante' => 'nullable|string',
            'correo_solicitante'   => 'nullable|email',
            'persona_turna'        => 'nullable|string',
            'cargo_turna'          => 'nullable|string',
            'telefono_turna'       => 'nullable|string',
            'correo_turna'         => 'nullable|email',
            'clave_cct'            => 'nullable|string',
            'nivel'                => 'nullable|string',
            'modalidad'            => 'nullable|string',
            'plantel'              => 'nullable|string',
            'turno'                => 'nullable|string',
            'numero_alumnos'       => 'nullable|integer',
            'numero_maestros'      => 'nullable|integer',
            'numero_aulas'         => 'nullable|integer',
        ]); 

        //Relacionar con el ticket 
        $solicitanteData['ticket_id'] = $ticket->id; 

        //Crear o actualizar solicitante del ticket
        Solicitante::updateOrCreate(
            ['ticket_id'=>$ticket->id], 
            $solicitanteData
        ); 

        return redirect()->route('seguimiento-proyectos')
                        ->with('success', 'Dictamen Guardado correctamente');

    }
}
