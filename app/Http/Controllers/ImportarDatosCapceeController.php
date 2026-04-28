<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EscuelasCien; 
use Maatwebsite\Excel\Facades\Excel; 

class ImportarDatosCapceeController extends Controller
{
    public function store (Request $request)
    {
        set_time_limit(300);

        $request->validate([
            'archivo'=>'required|file|mimes:csv, txt, xlsx, xls|max:10240',  
        ]); 

        $archivo= $request->file('archivo'); 
        $extension=$archivo->getClientOriginalExtension();

         if (in_array($extension, ['xlsx', 'xls'])) {
            $data = Excel::toArray([], $archivo)[0];
        } else {
            $contenido = file_get_contents($archivo->getRealPath());
            if (substr($contenido, 0, 3) === "\xEF\xBB\xBF") {
                $contenido = substr($contenido, 3);
            }
            $contenido = mb_convert_encoding($contenido, 'UTF-8', 'auto');
            $lineas = array_filter(explode(PHP_EOL, $contenido), fn($linea) => trim($linea) !== '');
            $data = array_map('str_getcsv', $lineas);
        }

        if (empty($data) || count($data) < 2) {
            return redirect()->back()->withErrors(['archivo' => 'El archivo está vacío o mal formado.']);
        }

        $encabezados= array_map('trim', data[0]);
        $camposEsperados=['MICROREGION', 'MUNICIPIO', 'LOCALIDAD', 'PLANTEL', 'META', 'MONTO CONTRATADO', 'META']; 
        $faltantes=array_diff($camposEsperados, $encabezados);

        if (!empty($faltantes)) {
            return redirect()->back()->withErrors([
                'archivo' => 'Faltan encabezados requeridos: ' . implode(', ', $faltantes)
            ]);
        }
    }
}
