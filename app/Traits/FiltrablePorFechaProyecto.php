<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait FiltrablePorFechaProyecto
{
    public function aplicarFiltrosFecha($query, Request $request)
    {
        if ($request->filled('anio') && $request->anio !== 'null') {
            $query->where(function ($q) use ($request) {
                $q->whereYear('inicio', $request->anio)
                  ->orWhereYear('termino', $request->anio);
            });
        }

        if ($request->filled('folio')&& $request->folio !== 'null'){
            $query->where('folio_ppi', 'LIKE', '%' . $request->folio . '%');
        }

        return $query;
    }
}
