<?php
//Controlador que gestiona los usuarios registrados en la plataforma
namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Role;
use App\Models\Plantel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::with('rol')->get();
        return view('gestion_usuarios', compact('usuarios'));
    }

    public function create()
    {
        $roles = Role::all(); // para el select
        return view('create', compact('roles'));
    }
    //Crear usuarios
    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required',
            'correo_electronico' => 'required|email|unique:usuarios,correo_electronico',
            'role_id' => 'required|exists:roles,id',
            'estado' => 'required',
            'password' => 'required|min:6',
            'telefono_contacto' => 'required|string|max:20',
        ]);

        Usuario::create([
            'nombre_completo' => $request->nombre_completo,
            'correo_electronico' => $request->correo_electronico,
            'role_id' => $request->role_id,
            'estado' => $request->estado,
            'password_hash' => Hash::make($request->password),
            'telefono_contacto' => $request->telefono_contacto,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente');
    }
    //Editar usuarios
    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        $roles = Role::all();
        return view('edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nombre_completo' => 'required',
            'correo_electronico' => 'required|email|unique:usuarios,correo_electronico,' . $usuario->id,
            'telefono_contacto' => 'required|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'estado' => 'required|in:ACTIVO, INACTIVO',
            'password' => 'nullable|string||min:6',
        ]);

        $datos = ([
            'nombre_completo' => $request->nombre_completo,
            'correo_electronico' => $request->correo_electronico,
            'telefono_contacto' => $request->telefono_contacto,
            'role_id' => $request->role_id,
            'estado' => $request->estado,
        ]);

        if ($request->filled('password')) {
            $datos['password_hash'] = Hash::make($request->password);
        }

        $usuario->update($datos);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente');
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        //Verificar si el usuario es director y tiene planteles asignados. 
        Plantel::where('id_director_asignado', $usuario->id)->update(['id_director_asignado' => null]);
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente');
    }
}
