<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProyectoInversion;
use App\Imports\ProyectosInversionImport;
use Maatwebsite\Excel\Facades\Excel;

class DatosProyectosController extends Controller
{
    public function index()
    {
        $registros=ProyectoInversion::paginate(10); 
        return view ('datosProyectos', compact('registros'));  
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

        Excel::import(new ProyectosInversionImport, $request->file('file'));

        return redirect()->back()->with('success', 'Datos importados correctamente.');
    }

    public function destroy($id)
    {
        $proyecto = ProyectoInversion::findOrFail($id);
        $proyecto -> delete(); 
        
        return redirect()->route('proyectos.index')->with('success', 'Proyecto eliminado correctamente'); 
    }
}
