<?php
//Rutas para cada seccion segun su rol

namespace App\Helpers;

class RoleHelper
{

    //Ruta para mi perfil y buscador avanzado
    public static function dashboardRoute($roleId)
    {

        return match ($roleId) {
            1 => route('dashboard.admin'),
            2 => route('dashboard.analista'),
            3 => route('dashboard.supervisor'),
            4 => route('dashboard.director'),
            5 => route('dashboard.capturista'),
        };
    }

    //Ruta para gestionar planteles
    public static function gestionPlanteles($roleId)
    {
        return match ($roleId) {
            1 => route('dashboard.admin'),     // Administrador
            4 => route('dashboard.director'),  // Director
        };
    }

    //Rutas para gestionar reportes
    public static function gestionReportes($roleId)
    {
        return match ($roleId) {
            1 => route('dashboard.admin'),
            2 => route('dashboard.analista'),
        };
    }

    //Rutas para panel de supervision 
    public static function gestionSupervision($roleId)
    {
        return match ($roleId) {
            1 => route('dashboard.admin'),
            3 => route('dashboard.supervisor'),
        };
    }

    //Rutas para importar datos
    public static function importarDatos($roleId)
    {
        return match ($roleId) {
            1 => route('dashboard.admin'),
            2 => route('dashboard.analista'),
            5 => route('dashboard.capturista'),
        };
    }
}
