<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReporteInfraescolar;
use Illuminate\Support\Facades\Storage;

class InfraescolarController extends Controller
{
    // FUNCIONES REUTILIZABLES PARA LOS NÃšMEROS (Limpieza y Saltos de lÃ­nea)
    private function separarPorLineas($texto) {
        $texto = str_replace("\r", "", $texto);
        $lineas = array_map('trim', explode("\n", $texto));
        return array_values(array_filter($lineas, function($val) { return $val !== ''; }));
    }

    private function limpiarNumero($valor) {
        $limpio = str_replace(['$', ',', ' '], '', $valor);
        return floatval($limpio);
    }

    // 1. Mostrar formulario al Admin Y la tabla de reportes
    public function indexAdmin()
    {
        $reportes = ReporteInfraescolar::latest()->get();
        return view('admin.formulario_infraescolar', compact('reportes'));
    }

    // 2. Guardar Nuevo Reporte
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required',
            'archivo_pdf' => 'required|mimes:pdf|max:10000',
            'gastos' => 'required', 'aprobado' => 'required',
            'presupuesto_vigente' => 'required', 'pagado' => 'required'
        ]);

        $rutaPdf = $request->file('archivo_pdf')->store('reportes_pdf', 'public');

        $gastosArray = $this->separarPorLineas($request->gastos);
        $aprobadoArray = array_map([$this, 'limpiarNumero'], $this->separarPorLineas($request->aprobado));
        $vigenteArray = array_map([$this, 'limpiarNumero'], $this->separarPorLineas($request->presupuesto_vigente));
        $pagadoArray = array_map([$this, 'limpiarNumero'], $this->separarPorLineas($request->pagado));

        $restaArray = [];
        foreach ($vigenteArray as $index => $vigente) {
            $pagado = $pagadoArray[$index] ?? 0;
            $restaArray[] = $vigente - $pagado;
        }

        ReporteInfraescolar::create([
            'titulo' => $request->titulo,
            'archivo_pdf' => $rutaPdf,
            'datos_grafica' => json_encode([
                'labels' => $gastosArray, 'aprobado' => $aprobadoArray,
                'vigente' => $vigenteArray, 'pagado' => $pagadoArray, 'diferencia' => $restaArray 
            ]),
            'creado_por' => session('id')
        ]);

        return redirect()->back()->with('success', 'Reporte generado con Ã©xito.');
    }

    // 3. Mostrar pantalla del Director
    public function indexDirector()
    {
        $reportes = ReporteInfraescolar::latest()->get();
        return view('director.ver_reportes', compact('reportes'));
    }

    // ðŸ”¥ 4. Mostrar Formulario de EDICIÃ“N
    public function edit($id)
    {
        $reporte = ReporteInfraescolar::findOrFail($id);
        $grafica = json_decode($reporte->datos_grafica);
        return view('admin.editar_infraescolar', compact('reporte', 'grafica'));
    }

    // ðŸ”¥ 5. Guardar la EDICIÃ“N en la Base de Datos
    public function update(Request $request, $id)
    {
        $reporte = ReporteInfraescolar::findOrFail($id);

        $request->validate([
            'titulo' => 'required',
            'gastos' => 'required', 'aprobado' => 'required',
            'presupuesto_vigente' => 'required', 'pagado' => 'required'
        ]);

        // Si sube un PDF nuevo, borramos el viejo y guardamos el nuevo
        if ($request->hasFile('archivo_pdf')) {
            $request->validate(['archivo_pdf' => 'mimes:pdf|max:10000']);
            if(Storage::disk('public')->exists($reporte->archivo_pdf)) {
                Storage::disk('public')->delete($reporte->archivo_pdf);
            }
            $reporte->archivo_pdf = $request->file('archivo_pdf')->store('reportes_pdf', 'public');
        }

        $gastosArray = $this->separarPorLineas($request->gastos);
        $aprobadoArray = array_map([$this, 'limpiarNumero'], $this->separarPorLineas($request->aprobado));
        $vigenteArray = array_map([$this, 'limpiarNumero'], $this->separarPorLineas($request->presupuesto_vigente));
        $pagadoArray = array_map([$this, 'limpiarNumero'], $this->separarPorLineas($request->pagado));

        $restaArray = [];
        foreach ($vigenteArray as $index => $vigente) {
            $pagado = $pagadoArray[$index] ?? 0;
            $restaArray[] = $vigente - $pagado;
        }

        $reporte->titulo = $request->titulo;
        $reporte->datos_grafica = json_encode([
            'labels' => $gastosArray, 'aprobado' => $aprobadoArray,
            'vigente' => $vigenteArray, 'pagado' => $pagadoArray, 'diferencia' => $restaArray 
        ]);
        $reporte->save();

        return redirect()->route('infraescolar.admin')->with('success', 'Reporte actualizado correctamente.');
    }

    // ðŸ”¥ 6. ELIMINAR un reporte
    public function destroy($id)
    {
        $reporte = ReporteInfraescolar::findOrFail($id);
        // Borramos el PDF del servidor para no ocupar espacio basura
        if(Storage::disk('public')->exists($reporte->archivo_pdf)) {
            Storage::disk('public')->delete($reporte->archivo_pdf);
        }
        $reporte->delete();
        return redirect()->back()->with('success', 'Reporte eliminado para siempre.');
    }
    
    //Descargar pdf
    public function descargarPdf($id)
    {
        $reporte = ReporteInfraescolar::findOrFail($id);
        
        // Buscamos la ruta real del archivo en las tripas del servidor
        $rutaAbsoluta = storage_path('app/public/' . $reporte->archivo_pdf);

        // Verificamos que el archivo realmente exista antes de descargarlo
        if (file_exists($rutaAbsoluta)) {
            // Lo renombramos al vuelo para que se descargue con un nombre bonito
            $nombreArchivo = 'Reporte_Financiero_' . str_replace(' ', '_', $reporte->titulo) . '.pdf';
            return response()->download($rutaAbsoluta, $nombreArchivo);
        }

        // Si por alguna raz¨®n f¨ªsica el archivo se borr¨® de la carpeta
        return redirect()->back()->with('error', 'El archivo PDF no se encontr¨® en el servidor.');
    }


}