<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function gestionUsuarios()
    {
        return view('gestion_usuarios');
    }
}
