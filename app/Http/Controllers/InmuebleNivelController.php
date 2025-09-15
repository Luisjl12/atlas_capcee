<?php

namespace App\Http\Controllers;

use App\Models\InmuebleNivel;
use Illuminate\Http\Request;

class InmuebleNivelController extends Controller
{

    public function mostrarNivelesPorCCT($cct)
    {
        $niveles = InmuebleNivel::where('cct', $cct)->get();

        return view('planteles.show', compact('niveles'));
    }
}
