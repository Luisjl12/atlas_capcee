<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Usuario;

class RolMiddleware
{
    public function handle(Request $request, Closure $next, $rol): Response
    {
        $usuarioId = session('id');

        if (!$usuarioId) {
            return redirect()->route('login');
        }

        $usuario = Usuario::find($usuarioId);

        if (!$usuario || $usuario->rol->nombre_rol !== $rol) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
