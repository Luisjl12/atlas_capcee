<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ProteccionLecturaSiie
{
    public function handle(Request $request, Closure $next)
    {
        // Métodos que modifican datos en la base de datos
        $metodosDeEdicion = ['POST', 'PUT', 'PATCH', 'DELETE'];

        // Si la sesión viene del SIIE y se intenta usar un método de edición
        if (session('origen_siie') && in_array($request->method(), $metodosDeEdicion)) {
            
            // Excepción: Permitir hacer POST para cerrar sesión (si usas un form para el logout)
            if ($request->is('logout') || $request->is('salir')) {
                return $next($request);
            }

            // Bloquear y regresar con un mensaje de error
            return redirect()->back()->with('error', 'Acción denegada. Estás visualizando el Atlas en modo de solo lectura desde el SIIE.');
        }

        return $next($request);
    }
}