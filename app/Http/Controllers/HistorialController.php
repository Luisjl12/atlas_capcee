<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;
use Carbon\Carbon;

class HistorialController extends Controller
{
    public function index()
    {
        $desde = Carbon::now()->subDays(3);

        $auditorias = Audit::whereIn('auditable_type', [
            \App\Models\Plantel::class,
            \App\Models\DetalleProteccionCivil::class,
            \App\Models\DetalleServicio::class,
            \App\Models\DetalleHidrosanitario::class,
        ])
            ->where('created_at', '>=', $desde)
            ->latest()
            ->paginate(10);

        return view('historial.index', compact('auditorias'));
    }
}

