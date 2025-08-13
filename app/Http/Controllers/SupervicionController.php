<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Corde;
use App\Models\Plantel;


class SupervicionController extends Controller
{
    public function index()
    {
        $datos = DB::table('cordes')
            ->leftJoin('planteles', 'cordes.id', '=', 'planteles.id_corde')
            ->select(
                'cordes.id',
                'cordes.nombre_corde',
                DB::raw('COUNT(planteles.id) as total_planteles'),
                DB::raw('AVG(planteles.porcentaje_avance_captura) as avance_promedio'),
                DB::raw('MAX(planteles.fecha_ultima_actualizacion_general) as ultima_actualizacion')
            )
            ->groupBy('cordes.id', 'cordes.nombre_corde')
            ->get();
        return view('panel_supervision.supervision', compact('datos'));
    }

    public function show($id)
    {
        $corde = Corde::findOrFail($id);

        $planteles = Plantel::where('id_corde', $id)
            ->select('cct', 'nombre_escuela', 'porcentaje_avance_captura', 'fecha_ultima_actualizacion_general')
            ->get();

        return view('panel_supervision.detalle', compact('corde', 'planteles'));
    }
}
