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

    public function reporteEstatusPlantel()
    {
        $planteles = DB::table('planteles')
            ->leftJoin('municipios', 'planteles.id_municipio', '=', 'municipios.id')
            ->leftJoin('localidades', 'planteles.id_localidad', '=', 'localidades.id')
            ->select(
                'planteles.cct',
                'planteles.nombre_escuela',
                'planteles.estatus_plantel',
                'municipios.nombre_municipio',
                'localidades.nombre_localidad',
                'planteles.domicilio_calle_numero',
                'planteles.domicilio_colonia',
                'planteles.domicilio_cp'
            )
            ->get();

        return view('reportes.reporte_estatus', compact('planteles'));
    }

    public function exportarEstatusCSV()
    {
        $planteles = DB::table('planteles')
            ->leftJoin('municipios', 'planteles.id_municipio', '=', 'municipios.id')
            ->leftJoin('localidades', 'planteles.id_localidad', '=', 'localidades.id')
            ->select(
                'planteles.cct',
                'planteles.nombre_escuela',
                'planteles.estatus_plantel',
                'municipios.nombre_municipio',
                'localidades.nombre_localidad',
                'planteles.domicilio_calle_numero',
                'planteles.domicilio_colonia',
                'planteles.domicilio_cp'
            )
            ->get();
        $fileName = 'reporte_estatus_planteles.csv';
        $response = new StreamedResponse(function () use ($planteles) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['CCT', 'Nombre Plantel', 'Ubicación', 'Estatus']);

            foreach ($planteles as $p) {
                $ubicacion = ($p->nombre_municipio ?? '') . ', ' . ($p->nombre_localidad ?? '') . ', ' . $p->domicilio_calle_numero . ' ' . $p->domicilio_colonia . ' CP ' . $p->domicilio_cp;
                fputcsv($handle, [$p->cct, $p->nombre_escuela, $ubicacion, $p->estatus_plantel]);
            }
            fclose($handle);
        });
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $fileName . '"');
        return $response;
    }

    public function exportarEstatusPDF()
    {
        $planteles = DB::table('planteles')
            ->leftJoin('municipios', 'planteles.id_municipio', '=', 'municipios.id')
            ->leftJoin('localidades', 'planteles.id_localidad', '=', 'localidades.id')
            ->select(
                'planteles.cct',
                'planteles.nombre_escuela',
                'planteles.estatus_plantel',
                'municipios.nombre_municipio',
                'localidades.nombre_localidad',
                'planteles.domicilio_calle_numero',
                'planteles.domicilio_colonia',
                'planteles.domicilio_cp'
            )
            ->get();
        $pdf = Pdf::loadView('reportes.pdf_estatus', compact('planteles'));
        return $pdf->download('reportes_estatus_planteles.pdf');
    }

    public function infraestructura()
    {
        $infraestructura = DB::table('planteles')
            ->leftJoin('detalle_hidrosanitario', 'planteles.cct', '=', 'detalle_hidrosanitario.cct')
            ->leftJoin('detalle_servicios', 'planteles.cct', '=', 'detalle_servicios.cct')
            ->leftJoin('espacios_areas', 'planteles.cct', '=', 'espacios_areas.cct')
            ->select(
                'planteles.cct',
                'planteles.nombre_escuela',
                'detalle_hidrosanitario.fuente_agua',
                'detalle_hidrosanitario.tipo_drenaje',
                'detalle_servicios.electricidad_contrato',
                'detalle_servicios.telefonia_fija',
                'detalle_servicios.internet_acceso',
                'espacios_areas.estado_conservacion'
            )
            ->get();

        // Ejemplo: contar cuántos planteles tienen cada tipo de fuente de agua
        $labels = $infraestructura->pluck('fuente_agua')->unique()->values();
        $espacios = $labels->map(function ($label) use ($infraestructura) {
            return $infraestructura->where('fuente_agua', $label)->count();
        });

        return view('reportes.reporte_infraestructura', compact('infraestructura', 'labels', 'espacios'));
    }


    public function exportarInfraestructuraCSV()
    {
        $infraestructura = DB::table('planteles')
            ->leftJoin('detalle_hidrosanitario', 'planteles.cct', '=', 'detalle_hidrosanitario.cct')
            ->leftJoin('detalle_servicios', 'planteles.cct', '=', 'detalle_servicios.cct')
            ->select(
                'planteles.cct',
                'planteles.nombre_escuela',
                'detalle_hidrosanitario.fuente_agua',
                'detalle_hidrosanitario.tipo_drenaje',
                'detalle_servicios.electricidad_contrato',
                'detalle_servicios.telefonia_fija',
                'detalle_servicios.internet_acceso'
            )
            ->get();

        $fileName = 'reporte_infraestructura.csv';

        $response = new StreamedResponse(function () use ($infraestructura) {
            $handle = fopen('php://output', 'w');

            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Encabezados del CSV
            fputcsv($handle, [
                'CCT',
                'Nombre de la Escuela',
                'Fuente de Agua',
                'Tipo de Drenaje',
                'Contrato de Electricidad',
                'Telefonía Fija',
                'Acceso a Internet'
            ]);

            // Filas
            foreach ($infraestructura as $item) {
                fputcsv($handle, [
                    $item->cct,
                    $item->nombre_escuela,
                    $item->fuente_agua ?? 'No registrado',
                    $item->tipo_drenaje ?? 'No registrado',
                    $item->electricidad_contrato == 1 ? 'Sí' : 'No',
                    $item->telefonia_fija == 1 ? 'Sí' : 'No',
                    $item->internet_acceso == 1 ? 'Sí' : 'No'
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $fileName . '"');

        return $response;
    }

    public function exportarInfraestructuraPDF()
    {
        $infraestructura = DB::table('planteles')
            ->leftjoin('detalle_hidrosanitario', 'planteles.cct', '=', 'detalle_hidrosanitario.cct')
            ->leftjoin('detalle_servicios', 'planteles.cct', '=', 'detalle_servicios.cct')
            ->select(
                'planteles.cct',
                'planteles.nombre_escuela',
                'detalle_hidrosanitario.fuente_agua',
                'detalle_hidrosanitario.tipo_drenaje',
                'detalle_servicios.electricidad_contrato',
                'detalle_servicios.telefonia_fija',
                'detalle_servicios.internet_acceso'
            )
            ->get();
        $pdf = Pdf::loadView('reportes.infraestructura_pdf', compact('infraestructura'))
            ->setPaper('a4', 'landscape');
        return $pdf->download('reportes_infraestructura.pdf');
    }
}
