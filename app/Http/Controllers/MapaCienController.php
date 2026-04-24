<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class MapaCienController extends Controller
{
    public function index()
    {
        $escuelas = DB::table('escuelas_al_100')
            ->select('cct', 'plantel', 'meta', 'latitud', 'longitud')
            ->whereNotNull('latitud')
            ->whereNotNull('longitud')
            ->get();

        return view('mapas_escuelas_cien', compact('escuelas'));
    }
}
