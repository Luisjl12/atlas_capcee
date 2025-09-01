<?php

namespace App\Helpers;

class RoleHelper
{
    public static function dashboardRoute($roleId)
    {

        return match ($roleId) {
            1 => route('dashboard.admin'),
            2 => route('dashboard.analista'),
            3 => route('dashboard.supervisor'),
            4 => route('dashboard.director'),
        };
    }

    public static function gestionPlanteles($roleId)
    {
        return match ($roleId) {
            1 => route('dashboard.admin'),     // Administrador
            4 => route('dashboard.director'),  // Director
        };
    }

    public static function gestionReportes($roleId)
    {
        return match ($roleId) {
            1 => route('dashboard.admin'),
            2 => route('dashboard.analista'),
        };
    }

    public static function gestionSupervision($roleId)
    {
        return match ($roleId) {
            1 => route('dashboard.admin'),
            3 => route('dashboard.supervisor'),
        };
    }
}
