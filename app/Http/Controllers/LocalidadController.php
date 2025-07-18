<?php

namespace App\Http\Controllers;

use App\Models\Localidad;

use Illuminate\Http\Request;

class LocalidadController extends Controller
{
    public function getPorMunicipio($municipio_id)
    {
        $localidades = Localidad::where('municipio_id', $municipio_id)->get();
        return response()->json($localidades);
    }
}
