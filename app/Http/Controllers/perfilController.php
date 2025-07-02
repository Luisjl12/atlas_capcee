<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Usuario;

class PerfilController extends Controller
{
    public function formCambiarPassword()
    {
        return view('cambiar-password');
    }

    public function actualizarPassword(Request $request)
    {
        $request->validate([
            'password_actual' => 'required',
            'nueva_password' => 'required|min:6|confirmed',
        ]);

        $usuario = Usuario::find(Session::get('id'));

        if (!$usuario || !password_verify($request->password_actual, $usuario->password_hash)) {
            return back()->withErrors(['password_actual' => 'La contraseña actual no es válida.']);
        }

        $usuario->password_hash = password_hash($request->nueva_password, PASSWORD_DEFAULT);
        $usuario->save();

        Session::flash('success', 'Contraseña actualizada correctamente.');
        return redirect()->route('perfil')->with('success', 'Contraseña actualizada.');
    }
}
