<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProyectosEspecialesController extends Controller
{
    public function index()
    {
        return view('proyectos_especiales'); 
    }
}
