<?php

namespace App\Http\Controllers;

use App\Models\InmuebleNivel;
use App\Models\Plantel;
use Illuminate\Http\Request;

class InmuebleNivelController extends Controller
{

    public function mostrarNivelesPorCCT($cct)
    {
        $plantel = Plantel::where('cct', $cct)->first(); // o findOrFail si quieres validar existencia
        $niveles = InmuebleNivel::where('cct', $cct)->get();

        return view('planteles.show', compact('plantel', 'niveles'));
    }
}
