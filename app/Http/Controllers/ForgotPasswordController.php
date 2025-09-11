<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\PasswordResetsCode;
use App\Notifications\PasswordResetCode;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;


class ForgotPasswordController extends Controller
{
    //Enviar codigo 
    public function sendCode(Request $request)
    {
        $request->validate([
            'correo_electronico' => 'required|email',
        ]);

        $user = usuario::where('correo_electronico', $request->correo_electronico)->first();

        if ($user) {
            $code = rand(100000, 999999);

            PasswordResetsCode::create([
                'email' => $user->correo_electronico,
                'code' => $code,
                'expires_at' => Carbon::now()->addMinutes(10),
            ]);
            $user->notify(new PasswordResetCode($code));
        }

        return back()->with('status', 'Un código ha sido enviado a su correo');
    }

    //Verificar codigo
    public function verifyCode(Request $request)
    {
        $request->validate([
            'correo_electronico' => 'required|email',
            'code' => 'required',
        ]);

        $record = PasswordResetsCode::where('email', $request->correo_electronico)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$record) {
            return back()->withErrors(['code', 'El código es inválido o ha expirado']);
        }

        return redirect()->route('password.reset.form', [
            'email' => $request->correo_electronico,
            'code' => $request->code
        ]);
    }

    //Resetear contraseña
    public function resetPassword(Request $request)
    {
        $request->validate([
            'correo_electronico' => 'required|email',
            'code' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $record = PasswordResetsCode::where('email', $request->correo_electronico)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$record) {
            return back()->withErrors(['code' => 'El código es inválido o ha expirado.']);
        }

        $user = Usuario::where('correo_electronico', $request->correo_electronico)->firstOrFail();
        $user->password_hash = Hash::make($request->password);
        $user->save();

        $record->delete();

        return redirect()->route('login')->with('status', 'Tu contraseña se actualizó correctamente.');
    }
}
