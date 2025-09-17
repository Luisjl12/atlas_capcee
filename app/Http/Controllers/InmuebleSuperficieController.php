<?php

namespace App\Http\Controllers;

use App\Models\InmuebleSuperficie;
use App\Models\Plantel;
use Illuminate\Http\Request;

class InmuebleSuperficieController extends Controller
{
    public function mostrarSuperficieCCT($cct)
    {
        $plantel = Plantel::with(['niveles', 'superficies'])->where('cct', $cct)->firstOrFail();
        return view('planteles.show', compact('superficies'));
    }
}
