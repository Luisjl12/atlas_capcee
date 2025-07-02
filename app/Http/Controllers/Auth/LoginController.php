<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Usuario;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $usuario = Usuario::where('correo_electronico', $request->email)->first();

        if ($usuario && password_verify($request->password, $usuario->password_hash)) {


            Session::put('loggedin', true);
            Session::put('id', $usuario->id);
            Session::put('nombre_completo', $usuario->nombre_completo);
            Session::put('correo_electronico', $usuario->correo_electronico);
            Session::put('telefono_contacto', $usuario->telefono_contacto);
            Session::put('ultima_conexion', $usuario->now);
            Session::put('role_id', $usuario->role_id);


            $usuario->ultima_conexion = now();
            $usuario->save();


            Session::flash('success', 'Inicio de sesión exitoso.');

            $rol = $usuario->rol->nombre_rol;

            if ($rol === 'ADMINISTRADOR') {
                return redirect()->route('dashboard.admin');
            } elseif ($rol === 'ANALISTA') {
                return redirect()->route('dashboard.analista');
            } elseif ($rol === 'SUPERVISOR') {
                return redirect()->route('dashboard.supervisor');
            } elseif ($rol === 'DIRECTOR') {
                return redirect()->route('dashboard.director');
            }

            return redirect()->route('dashboard');
        }

        return redirect()->back()->withErrors(['error' => 'Credenciales incorrectas.']);
    }

    public function logout()
    {
        Session::flush();
        Session::flash('success', 'Sesión cerrada correctamente.');
        return redirect()->route('login');
    }
}
