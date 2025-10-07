<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait FiltrablePorTerritorioYNivel
{
    public function aplicarFiltrosTerritorialesYNivel($query, Request $request)
    {
        if ($request->filled('macroregion')) {
            $query->where('macroregion_id', $request->macroregion);
        }

        if ($request->filled('microregion')) {
            $query->where('microregion_id', $request->microregion);
        }

        if ($request->filled('municipio')) {
            $query->where('id_municipio', $request->municipio);
        }

        if ($request->filled('nivel')) {
            $query->whereHas('niveles', function ($q) use ($request) {
                $q->where('nivel', $request->nivel);
            });
        }

        return $query;
    }
}
