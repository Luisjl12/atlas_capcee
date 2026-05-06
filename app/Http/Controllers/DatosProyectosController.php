<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProyectoInversion;
use App\Imports\ProyectosInversionImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Traits\FiltrablePorFechaProyecto;

class DatosProyectosController extends Controller
{
    use FiltrablePorFechaProyecto;

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

    public function edit($id)
    {
        $proyecto = ProyectoInversion::findOrFail($id); 
        return view ('proyectos.edit', compact ('proyecto')); 
    }

    public function update(Request $request, $id)
    {
        $proyecto= ProyectoInversion::findOrFail($id);
        $validated= $request->validate([
            'municipio'=>'required',
            'nombre_proyecto'=>'required', 
            'monto_inversion'=>'required|numeric', 
            'inicio'=>'required|date', 
            'termino'=>'required|date', 
            'empresa'=>'nullable|string', 
        ]); 

        $proyecto->update($validated); 

        return redirect()->route('proyectos.index')->with('success', 'Proyecto actualizado correctamente.'); 
    }

    public function verDetalles($id)
    {
        $proyecto= ProyectoInversion::findOrFail($id); 
        return view('verDetallesProyecto', compact ('proyecto')); 
    }

    public function mapaProyectos()
    {
        return view('mapaProyectos'); 
    }

    public function obtenerProyectosMapa(Request $request)
    {
        $query = ProyectoInversion::select(
            'id',
            'folio_ppi',
            'latitud',
            'longitud',
            'nombre_proyecto',
            'inicio',
            'termino',
            'monto_inversion',
            'municipio'
        )
        ->whereNotNull('latitud')
        ->whereNotNull('longitud')
        ->where('latitud', '!=', 0)
        ->where('longitud', '!=', 0);

        // aplicar filtros (anio y folio) desde el Trait
        $this->aplicarFiltrosFecha($query, $request);

        $proyectos = $query->get();

        return response()->json($proyectos);
    }

}
