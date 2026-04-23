<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InmuebleNivel; 
use App\Models\InfraestructuraComparada; 
use App\Models\Plantel; 
use App\Models\ComparacionEdificio; 
use App\Models\ComparacionAgua; 
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\DB; 

class ComparacionController extends Controller
{
    //Retornar a la vista
    public function mostrarFormulario()
    {
        return view('comparar');
    }

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

        $registrosAgua = DB::table('comparacion_agua as ca')
            ->leftJoin('inmueble_agua as ia', 'ca.cct', '=', 'ia.cct')
            ->select(
                'ca.cct',
                'ca.agua_red_publica',
                'ia.agua_red_publica as agua_red_publica_reg',
                'ca.agua_pozo',
                'ia.agua_pozo as agua_pozo_reg',
                'ca.agua_cuerpo',
                'ia.agua_cuerpo as agua_cuerpo_reg',
                'ca.agua_pipas',
                'ia.agua_pipas as agua_pipas_reg',
                'ca.agua_otro',
                'ia.agua_otro as agua_otro_reg',
                'ca.cisterna',
                'ia.cisterna as cisterna_reg',
                'ca.tinacos',
                'ia.tinacos as tinacos_reg',
                'ca.tanque',
                'ia.tanque as tanque_reg',
                'ca.almacenamiento_otro',
                'ia.almacenamiento_otro as almacenamiento_otro_reg',
                'ca.estado_red_hidraulica',
                'ia.estado_red_hidraulica as estado_red_hidraulica_reg'
            )
            ->get();

        return view('reportes.comparacionPlanteles', compact('registros','registrosEdificios', 'registrosAgua'));
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

    //Insertar edificios
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
            // Encabezados: ahora incluye la descripción
            fputcsv($handle, ['CCT', 'Edificios ingresados', 'Edificios registrados', 'Tipo de edificio', 'Estado']);

            $registros = DB::table('comparacion_edificios as ce')
                ->leftJoin('planteles as p', 'ce.cct', '=', 'p.cct')
                ->select(
                    'ce.cct',
                    'ce.numero_edificios as edificios_comparada',
                    'p.numero_edificios as edificios_registrados',
                    'ce.descripcion_edificios'
                )
                ->get();

            foreach ($registros as $row) {
                $estado = $row->edificios_comparada == $row->edificios_registrados ? 'Coinciden' : 'Diferentes';
                fputcsv($handle, [
                    $row->cct,
                    $row->edificios_comparada,
                    $row->edificios_registrados,
                    $row->descripcion_edificios,
                    $estado
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$fileName.'"');

        return $response;
    }

    //Insertar comparacion agua
    public function insertarAgua(Request $request)
    {
        $request->validate([
            'cct'=>'required|exists:inmueble_agua,cct', 
            'agua_red_publica'=>'nullable|boolean', 
            'agua_pozo'=>'nullable|boolean', 
            'agua_cuerpo'=>'nullable|boolean', 
            'agua_pipas'=>'nullable|boolean', 
            'agua_otros'=>'nullable|boolean', 
            'cisterna'=>'nullable|boolean', 
            'tinacos'=>'nullable|boolean', 
            'tanque'=>'nullable|boolean', 
            'almacenamiento_otro'=>'nullable|boolean', 
            'estado_red_hidraulica'=>'nullable|string|max:255', 
        ]);

        ComparacionAgua::create($request->all());


        return redirect()->route('reportes.comparacion')
                         ->with('success', 'Dato agregado directamente'); 
    }
    //Exportar csv de detalles hidraulicos
    public function exportarAgua(){
        $fileName='exportar_agua.csv'; 
        $response= new StreamedResponse(function(){
            $handle = fopen('php://output', 'w'); 
            fputcsv($handle,[
                'CCT', 'Red pública (ingresado)', 'Red pública (registrado)',
                'Pozo (ingresado)', 'Pozo (registrado)',
                'Cuerpo (ingresado)', 'Cuerpo (registrado)',
                'Pipas (ingresado)', 'Pipas (registrado)',
                'Otro (ingresado)', 'Otro (registrado)',
                'Cisterna (ingresado)', 'Cisterna (registrado)',
                'Tinacos (ingresado)', 'Tinacos (registrado)',
                'Tanque (ingresado)', 'Tanque (registrado)',
                'Almacenamiento otro (ingresado)', 'Almacenamiento otro (registrado)',
                'Estado red pública (ingresado)', 'Estado red pública (registrado)',
                'Estado'
            ]); 

            $registros= DB::table('comparacion_agua as ca')
            ->leftJoin('inmueble_agua as ia', 'ca.cct', '=', 'ia.cct')
            ->select(
                'ca.cct',
                'ca.agua_red_publica','ia.agua_red_publica as agua_red_publica_reg',
                'ca.agua_pozo','ia.agua_pozo as agua_pozo_reg',
                'ca.agua_cuerpo','ia.agua_cuerpo as agua_cuerpo_reg',
                'ca.agua_pipas','ia.agua_pipas as agua_pipas_reg',
                'ca.agua_otro','ia.agua_otro as agua_otro_reg',
                'ca.cisterna','ia.cisterna as cisterna_reg',
                'ca.tinacos','ia.tinacos as tinacos_reg',
                'ca.tanque','ia.tanque as tanque_reg',
                'ca.almacenamiento_otro','ia.almacenamiento_otro as almacenamiento_otro_reg',
                'ca.estado_red_hidraulica','ia.estado_red_hidraulica as estado_red_hidraulica_reg'
            )
            ->get(); 
            foreach ($registros as $row){
                $estado = ($row->agua_red_publica==$row->agua_red_publica_reg &&
                           $row-> agua_pozo == $row->agua_pozo_red && 
                           $row-> agua_cuerpo == $row->agua_cuerpo_reg) ? 'Coinciden': 'No coinciden'; 
            
                fputcsv($handle, [
                    $row->cct,
                    $row->agua_red_publica, $row->agua_red_publica_reg,
                    $row->agua_pozo, $row->agua_pozo_reg,
                    $row->agua_cuerpo, $row->agua_cuerpo_reg,
                    $row->agua_pipas, $row->agua_pipas_reg,
                    $row->agua_otro, $row->agua_otro_reg,
                    $row->cisterna, $row->cisterna_reg,
                    $row->tinacos, $row->tinacos_reg,
                    $row->tanque, $row->tanque_reg,
                    $row->almacenamiento_otro, $row->almacenamiento_otro_reg,
                    $row->estado_red_hidraulica, $row->estado_red_hidraulica_reg,
                    $estado
                ]); 
            }
            fclose($handle); 
        }); 

        $response->headers->set('Content-Type', 'text/csv'); 
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$fileName.'"');

        return $response;
    }
}
