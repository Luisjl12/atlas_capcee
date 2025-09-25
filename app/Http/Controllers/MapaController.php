<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plantel;
use App\Models\Localidad;
use App\Models\Macroregion;

class MapaController extends Controller
{
    public function mapa(Request $request)
    {
        // Construye el query sin ejecutarlo aún
        $query = Plantel::with(['municipio', 'localidad'])
            ->select(
                'id',
                'nombre_escuela as nombre',
                'cct',
                'latitud as lat',
                'longitud as lng',
                'estatus_plantel',
                'id_municipio',
                'id_localidad'
            )
            ->whereNotNull('latitud')
            ->whereNotNull('longitud');

        // Filtro por localidad
        if ($request->filled('localidad')) {
            $query->where('id_localidad', $request->localidad);
        }

        // Ejecuta el query con paginación
        $planteles = $query->paginate(500);

        return response()->json($planteles);
    }

    public function vistaMapa()
    {
        $localidades = Localidad::orderBy('nombre_localidad')->get();
        $macroregiones = Macroregion::orderBy('nombre_macroregion')->get();
        $niveles = \App\Models\InmuebleNivel::select('nivel')->distinct()->orderBy('nivel')->get();
        $rangosSuperficie = \App\Models\InmuebleSuperficie::select('rango')->distinct()->orderBy('rango')->get();



        return view('planteles.mapa', compact('localidades', 'macroregiones', 'niveles', 'rangosSuperficie'));
    }

    public function filtrar(Request $request)
    {
        try {
            // dd($request->all());
            $query = Plantel::query();

            if ($request->filled('macroregion')) {
                $query->where('macroregion_id', $request->macroregion);
            }

            if ($request->filled('nivel')) {
                $query->whereHas('niveles', function ($q) use ($request) {
                    $q->where('nivel', $request->nivel);
                });
            }

            if ($request->filled('superficie')) {
                $query->whereHas('superficies', function ($q) use ($request) {
                    $q->where('rango', $request->superficie);
                });
            }

            $query->whereNotNull('latitud')->whereNotNull('longitud');

            $planteles = $query->with(['niveles', 'superficies', 'municipio', 'localidad'])->get();

            return response()->json(['data' => $planteles]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error interno al aplicar filtros',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }
}
