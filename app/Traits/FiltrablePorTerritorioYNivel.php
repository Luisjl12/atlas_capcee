<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait FiltrablePorTerritorioYNivel
{
    public function aplicarFiltrosTerritorialesYNivel($query, Request $request)
    {
        $query->where(function ($q) use ($request) {
            if ($request->filled('macroregion') && $request->macroregion !== 'null') {
                $q->orWhere('macroregion_id', $request->macroregion);
            }

            if ($request->filled('microregion') && $request->microregion !== 'null') {
                $q->orWhere('microregion_id', $request->microregion);
            }

            if ($request->filled('municipio') && $request->municipio !== 'null') {
                $q->orWhere('id_municipio', $request->municipio);
            }
        });

        if ($request->filled('nivel') && $request->nivel !== 'null') {
            $query->whereHas('niveles', function ($q) use ($request) {
                $q->where('nivel', $request->nivel);
            });
        }

        return $query;
    }
}
