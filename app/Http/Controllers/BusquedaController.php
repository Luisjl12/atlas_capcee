<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plantel;
use App\Models\Corde;
use App\Models\Municipio;

class BusquedaController extends Controller
{
    public function index(Request $request)
    {

        $query = Plantel::query()->with('municipio', 'detalleProteccionCivil');

        if ($request->filled('busqueda')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre_escuela', 'like', '%' . $request->busqueda . '%')
                    ->orWhere('cct', 'like', '%' . $request->busqueda . '%');
            });
        }

        if ($request->filled('id_corde')) {
            $query->where('id_corde', $request->id_corde);
        }

        if ($request->filled('id_municipio')) {
            $query->where('id_municipio', $request->id_municipio);
        }
        if ($request->filled('nivel_educativo')) {
            $query->where('nivel_educativo', $request->nivel_educativo);
        }

        if ($request->filled('sostenimiento')) {
            $query->where('sostenimiento', $request->sostenimiento);
        }

        if ($request->filled('alarma_sismica')) {
            $query->whereHas('detalleProteccionCivil', function ($q) use ($request) {
                $q->where('alarma_sismica', $request->alarma_sismica);
            });
        }


        $planteles = $query->paginate(10)->appends($request->query());

        // Datos para los selects
        $cordes = Corde::all();
        $municipios = Municipio::all();
        // etc...

        return view('busqueda.avanzada', compact('planteles', 'cordes', 'municipios'));
    }
}
