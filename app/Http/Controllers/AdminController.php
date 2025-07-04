<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    //Redirige a la vista de gestionar usuarios
    public function gestionUsuarios()
    {
        return redirect()->route('usuarios.index');
    }
}
