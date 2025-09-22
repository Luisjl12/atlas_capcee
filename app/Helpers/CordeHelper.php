<?php

namespace App\Helpers;

use App\Models\Corde;

class CordeHelper
{
    public static function normalizarNombre($nombreCorde)
    {
        $nombre = ucwords(strtolower(trim($nombreCorde)));

        $equivalencias = [
            'Acatatlan' => 'Acatlán de Osorio',
            'Acatatlán' => 'Acatlán de Osorio',
            'San Pedro' => 'Cholula',
            'Cholula' => 'Cholula',
            'Tepexi de Rodríguez' => 'Tepexi',
            'Tepexi De Rodríguez' => 'Tepexi',
            'Tepexi De RodrÍguez' => 'Tepexi',
            'Tepexi De RodríGuez' => 'Tepexi',
        ];

        if (isset($equivalencias[$nombre])) {
            return $equivalencias[$nombre];
        }

        $corde = Corde::where('nombre_corde', 'LIKE', "%$nombre%")->first();
        return $corde ? $corde->nombre_corde : $nombre;
    }
}
