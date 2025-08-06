<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use App\Models\Plantel;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }

    public function reporteMunicipio()
    {
        $datos = DB::table('planteles')
            ->join('municipios', 'planteles.id_municipio', '=', 'municipios.id')
            ->join('localidades', 'planteles.id_localidad', '=', 'localidades.id')
            ->select(
                'municipios.nombre_municipio AS municipio',
                'localidades.nombre_localidad AS localidad',
                DB::raw('COUNT(planteles.id) AS total_planteles'),
                DB::raw('GROUP_CONCAT(planteles.nombre_escuela SEPARATOR ", ") AS nombre_planteles')
            )
            ->groupBy('municipios.nombre_municipio', 'localidades.nombre_localidad')
            ->get();
        $totalGeneral = $datos->sum('total_planteles');

        return view('reportes.reporte_municipio', compact('datos', 'totalGeneral'));
    }

    public function exportarMunicipiosCSV()
    {
        $datos = DB::table('planteles')
            ->join('municipios', 'planteles.id_municipio', '=', 'municipios.id')
            ->join('localidades', 'planteles.id_localidad', '=', 'localidades.id')
            ->select(
                'municipios.nombre_municipio AS municipio',
                'localidades.nombre_localidad AS localidad',
                DB::raw('COUNT(planteles.id) AS total_planteles'),
                DB::raw('GROUP_CONCAT(planteles.nombre_escuela SEPARATOR ", ") AS nombres_planteles')
            )
            ->groupBy('municipios.nombre_municipio', 'localidades.nombre_localidad')
            ->get();

        $fileName = 'reporte_municipios_localidades.csv';
        $response = new StreamedResponse(function () use ($datos) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Municipio', 'Localidad', 'Nombre de los Planteles ',  'Total de planteles']);

            foreach ($datos as $fila) {
                fputcsv($handle, [
                    $fila->municipio,
                    $fila->localidad,
                    $fila->nombres_planteles,
                    $fila->total_planteles
                ]);
            }
            fclose($handle);
        });


        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename= "' . $fileName . '"');

        return $response;
    }

    public function exportarMunicipioPDF()
    {
        $datos = DB::table('planteles')
            ->join('municipios', 'planteles.id_municipio', '=', 'municipios.id')
            ->join('localidades', 'planteles.id_localidad', '=', 'localidades.id')
            ->select(
                'municipios.nombre_municipio AS municipio',
                'localidades.nombre_localidad AS localidad',
                DB::raw('COUNT(planteles.id) AS total_planteles'),
                DB::raw('GROUP_CONCAT(planteles.nombre_escuela SEPARATOR ", ") AS nombres_planteles')
            )
            ->groupBy('municipios.nombre_municipio', 'localidades.nombre_localidad')
            ->get();
        $totalGeneral = $datos->sum('total_planteles');

        $pdf = Pdf::loadView('reportes.pdf_municipio', compact('datos', 'totalGeneral'));
        return $pdf->download('reporte_municipio.pdf');
    }
}
