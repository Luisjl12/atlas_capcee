<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InmuebleNivel; 
use App\Models\InfraestructuraComparada; 
use App\Models\Plantel; 
use App\Models\ComparacionEdificio; 
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\DB; 

class ComparacionController extends Controller
{
    //Retornar a la vista
    public function mostrarFormulario()
    {
        return view('comparar');
    }
    //Comparar niveles 
   

    public function reportesComparar()
    {
        $registros = DB::table('infraestructura_comparada as ic')
            ->leftJoin('inmueble_nivel as in', 'ic.cct', '=', 'in.cct')
            ->select('ic.cct','ic.nivel as nivel_comparada','in.nivel as nivel_inmueble')
            ->get();

        $registrosEdificios = DB::table('comparacion_edificios as ce')
            ->leftJoin('planteles as p', 'ce.cct', '=', 'p.cct')
            ->select('ce.cct','ce.numero_edificios as edificios_comparada','p.numero_edificios as edificios_registrados')
            ->get();

        return view('reportes.comparacionPlanteles', compact('registros','registrosEdificios'));
    }

    public function comparar(Request $request)
    {
        $request->validate([
            'cct' => 'required|exists:inmueble_nivel,cct',
            'nivel'=> 'required',
            'imparte' => 'required|boolean',
        ]);

        InfraestructuraComparada::create($request->all());

        return redirect()->route('reportes.comparacion')
                         ->with('success','Dato de nivel registrado correctamente.');
    }

    public function insertarEdificios(Request $request)
    {
        $request->validate([
            'cct' => 'required|exists:planteles,cct',
            'numero_edificios' => 'required|integer|min:0',
            'fuente' => 'nullable|string|max:50',
        ]);

        ComparacionEdificio::create($request->all());

        return redirect()->route('reportes.comparacion')
                         ->with('success','Dato de edificios registrado correctamente.');
    }
    //Exportar niveles en la tabla
    public function exportarNiveles(){
        $fileName = 'reporte_niveles.csv';
        $response = new StreamedResponse(function() {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['CCT', 'Nivel ingresado', 'Nivel registrado', 'Estado']);

            $registros = DB::table('infraestructura_comparada as ic')
                ->leftJoin('inmueble_nivel as in', 'ic.cct', '=', 'in.cct')
                ->select('ic.cct', 'ic.nivel as nivel_comparada', 'in.nivel as nivel_inmueble')
                ->get();

            foreach ($registros as $row) {
                $estado = $row->nivel_comparada === $row->nivel_inmueble ? 'Coinciden' : 'Diferentes';
                fputcsv($handle, [$row->cct, $row->nivel_comparada, $row->nivel_inmueble, $estado]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$fileName.'"');

        return $response;
    }

    //Exportar numero de edificios a csv
    public function exportarEdificios()
    {
        $fileName = 'reporte_edificios.csv';
        $response = new StreamedResponse(function() {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['CCT', 'Edificios ingresados', 'Edificios registrados', 'Estado']);

            $registros = DB::table('comparacion_edificios as ce')
                ->leftJoin('planteles as p', 'ce.cct', '=', 'p.cct')
                ->select('ce.cct', 'ce.numero_edificios as edificios_comparada', 'p.numero_edificios as edificios_registrados')
                ->get();

            foreach ($registros as $row) {
                $estado = $row->edificios_comparada == $row->edificios_registrados ? 'Coinciden' : 'Diferentes';
                fputcsv($handle, [$row->cct, $row->edificios_comparada, $row->edificios_registrados, $estado]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$fileName.'"');

        return $response;
    }
    

}
