<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InmuebleNivel; 
use App\Models\InfraestructuraComparada; 
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\DB; 

class ComparacionController extends Controller
{
    public function mostrarFormulario()
    {
        return view('comparar');
    }
    
    public function comparar(Request $request)
    {
        $request->validate([
            'cct' => 'required|exists:inmueble_nivel,cct',
            'nivel'=> 'required',
            'imparte' => 'required|boolean',
        ]);

        // Insertar registro en infraestructura_comparada
        InfraestructuraComparada::create($request->all());

        // Generar CSV con todos los registros
        $fileName = 'comparacion.csv';
        $response = new StreamedResponse(function() {
            $handle = fopen('php://output', 'w');
            // Cabeceras del CSV
            fputcsv($handle, ['CCT', 'Nivel educativo ingresado por el director/docente', 'Nivel educativo registrado por el CAPCEE']);

           $registros = DB::table('infraestructura_comparada as ic')
            ->leftJoin('inmueble_nivel as in', 'ic.cct', '=', 'in.cct')
            ->select(
                'ic.cct',
                'ic.nivel as nivel_comparada',
                'in.nivel as nivel_inmueble'
            )
            ->get();

           foreach ($registros as $row) {
                fputcsv($handle, [
                    $row->cct,
                    $row->nivel_comparada,
                    $row->nivel_inmueble
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$fileName.'"');

        return $response;

    }
}
