<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller
{
    public function store(Request $request)
    {
        $validated= $request->validate([
            'folio' => 'required|string|unique:tickets,folio',
            'numero_oficio' => 'nullable|string',
            'areas_turnadas' => 'nullable|string',
            'quien_atiende' => 'nullable|string',
            'anexo' => 'nullable|boolean',
            'fecha_oficialia' => 'nullable|date',
            'fecha_dfe' => 'nullable|date',
        ]); 

        $ticket = Ticket::create($validated);
        
        return redirect()->route('tickets.index')->with('success', 'Ticket creado correctamente'); 
    }
}
