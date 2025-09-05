<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plantel;

class MapaController extends Controller
{
    public function mapa()
    {
        // Recupera todos los planteles que tengan latitud y longitud
        $planteles = Plantel::select('nombre_escuela as nombre', 'cct', 'latitud as lat', 'longitud as lng')
            ->whereNotNull('latitud')
            ->whereNotNull('longitud')
            ->get();

        return view('planteles.mapa', compact('planteles'));
    }
}
