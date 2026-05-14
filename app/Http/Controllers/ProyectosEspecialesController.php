<?php
//Controlador para el seguimiento de proyectos especiales
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Solicitante; 

class ProyectosEspecialesController extends Controller
{
    public function index()
    {
        $tickets = Ticket::orderBy('fecha_oficialia', 'desc')->get();

        return view('proyectos_especiales', compact('tickets')); 
    }

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

    public function dictamen($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('dictamen_ticket', compact('ticket'));
    }

    public function guardarDictamen(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        // Validar y actualizar datos del ticket
        $ticketData = $request->validate([
            'estatus' => 'required|in:Aprobado,En revisión,No aprobado',
            'nivel' => 'nullable|string',
            'modalidad' => 'nullable|string',
            'plantel' => 'nullable|string',
            'turno' => 'nullable|string',
        ]);

        $ticket->update($ticketData);

        // Validar datos del solicitante
        $solicitanteData = $request->validate([
            'nombre_solicitante' => 'required|string',
            'cargo_solicitante' => 'nullable|string',
            'organismo_dependencia' => 'nullable|string',
            'telefono_solicitante' => 'nullable|string',
            'correo_solicitante' => 'nullable|email',
            'persona_turna' => 'nullable|string',
            'cargo_turna' => 'nullable|string',
            'telefono_turna' => 'nullable|string',
            'correo_turna' => 'nullable|email',
            'clave_cct' => 'nullable|string',
            'nivel' => 'nullable|string',
            'modalidad' => 'nullable|string',
            'plantel' => 'nullable|string',
            'turno' => 'nullable|string',
            'numero_alumnos' => 'nullable|integer', 
        ]);

        // Relacionar con el ticket
        $solicitanteData['ticket_id'] = $ticket->id;

        // Crear o actualizar solicitante vinculado
        Solicitante::updateOrCreate(
            ['ticket_id' => $ticket->id],
            $solicitanteData
        );

        return redirect()->route('seguimiento-proyectos')
                        ->with('success', 'Dictamen guardado correctamente');
    }


}
