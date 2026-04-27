<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class mapa_cien extends Controller
{
    public function index()
    {
        return view('mapa_escuelas_cien'); 
    }
}
