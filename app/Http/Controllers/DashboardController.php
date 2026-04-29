<?php
//Controlador que reedirige al dashboard segun el rol
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin()
    {
        return view('admin');
    }

    public function analista()
    {
        return view('analista');
    }

    public function supervisor()
    {
        return view('supervisor');
    }

    public function director()
    {
        return view('director');
    }

    public function capturista()
    {
        return view('capturista');
    }

    public function visualizador()
    {
        return view('visualizador');
    }
    
    public function directorReportes()
    {
        return view('directorReportes');  
    }
    
    public function administradorPrincipal(){
        return view('administradorPrincipal'); 
    }

    public function proyectosEspeciales()
    {
        return view('proyectosEspeciales');
    }
}
