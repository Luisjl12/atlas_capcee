<?php
//Rutas para cada seccion segun su rol

namespace App\Helpers;

class RoleHelper
{
    
    //Ruta para gestionar usuarios
    public static function gestionUsuarios($roleId)
    {
        return match ($roleId) {
            1 => route('dashboard.admin'),
            6 => route('dashboard.visualizador'),
            8 => route('dashboard.administradorPrincipal'),
        };
    }

    //Ruta para mi perfil y buscador avanzado
    public static function dashboardRoute($roleId)
    {
        return match ($roleId) {
            1 => route('dashboard.admin'),
            2 => route('dashboard.analista'),
            3 => route('dashboard.supervisor'),
            4 => route('dashboard.director'),
            5 => route('dashboard.capturista'),
            6 => route('dashboard.visualizador'), 
            7 => route('dashboard.directorReportes'), 
            8 => route('dashboard.administradorPrincipal'), 
        };
    }

    //Ruta para gestionar planteles
    public static function gestionPlanteles($roleId)
    {
        return match ($roleId) {
            1 => route('dashboard.admin'),
            2 => route('dashboard.analista'),
            3 => route('dashboard.supervisor'),
            4 => route('dashboard.director'),
            5 => route('dashboard.capturista'),
            6 => route('dashboard.visualizador'), 
            7 => route('dashboard.directorReportes'), 
            8 => route('dashboard.administradorPrincipal'), 
        };
    }

    //Rutas para gestionar reportes
    public static function gestionReportes($roleId)
    {
        return match ($roleId) {
            1 => route('dashboard.admin'),
            2 => route('dashboard.analista'),
            6 => route('dashboard.visualizador'), 
            8 => route('dashboard.administradorPrincipal'),
        };
    }

    //Rutas para panel de supervision 
    public static function gestionSupervision($roleId)
    {
        return match ($roleId) {
            1 => route('dashboard.admin'),
            3 => route('dashboard.supervisor'),
            6 => route('dashboard.visualizador'), 
            8 => route('dashboard.administradorPrincipal'),
        };
    }

    //Rutas para importar datos
    public static function importarDatos($roleId)
    {
        return match ($roleId) {
            1 => route('dashboard.admin'),
            2 => route('dashboard.analista'),
            5 => route('dashboard.capturista'),
            6 => route('dashboard.visualizador'), 
            8 => route('dashboard.administradorPrincipal'),
        };
    }
    //Rutas para mapas
    public static function mapaVista($roleId)
    {
        return match ($roleId) {
            1 => route('dashboard.admin'),
            4 => route('dashboard.director'),
            5 => route('dashboard.capturista'),
            6 => route('dashboard.visualizador'), 
            7 => route('dashboard.directorReportes'), 
            8 => route('dashboard.administradorPrincipal'),
        };
    }
    
    //Ruta para historial de cambios
    public static function historialVista($roleId)
    {
        return match ($roleId) {
            1 => route('dashboard.admin'),
            5 => route('dashboard.capturista'),
            8 => route('dashboard.administradorPrincipal'),
        };
    }
}
