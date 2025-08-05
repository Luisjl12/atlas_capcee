<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use App\Models\Plantel;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReporteController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }

    public function reporteMunicipio()
    {
        $municipios = Municipio::withCount('planteles')->get();
        $totalGeneral = $municipios->sum('planteles_count');
        return view('reportes.reporte_municipio', compact('municipios', 'totalGeneral'));
    }

    public function exportarMunicipiosCSV()
    {
        $municipios = Municipio::withCount('planteles')->get();
        $fileName = 'reporte_municipio.csv';
        $response = new StreamedResponse(function () use ($municipios) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Municipio', 'Total de Planteles']);

            foreach ($municipios as $municipio) {
                fputcsv($handle, [
                    $municipio->nombre_municipio,
                    $municipio->planteles_count
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename= "' . $fileName . '"');

        return $response;
    }
}
