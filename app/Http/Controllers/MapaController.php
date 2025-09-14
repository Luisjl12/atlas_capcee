<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plantel;

class MapaController extends Controller
{
    public function mapa()
    {
        // Recupera todos los planteles que tengan latitud y longitud
        $planteles = Plantel::with(['municipio', 'localidad'])
            ->select(
                'nombre_escuela as nombre',
                'cct',
                'latitud as lat',
                'longitud as lng',
                'estatus_plantel',
                'id_municipio',
                'id_localidad'
            )
            ->whereNotNull('latitud')
            ->whereNotNull('longitud')
            ->get();


        return view('planteles.mapa', compact('planteles'));
    }
}
