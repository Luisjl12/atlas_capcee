<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\perfilController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReporteController;
use App\Http\Controller\SupervisorController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\SupervisarController;
use App\Http\Controllers\DashboardController;
use App\Models\Usuario;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/perfil', function () {
    return view('perfil');
})->middleware('auth.custom')->name('perfil');

Route::get('/cambiar-password', [perfilController::class, 'formCambiarPassword'])
    ->name('perfil.cambiar-password')
    ->middleware('auth.custom');

Route::post('/cambiar-password', [perfilController::class, 'actualizarPassword'])
    ->name('perfil.actualizar-password')
    ->middleware('auth.custom');

//Rutas para ROLES
Route::middleware(['auth.custom'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/analista', [DashboardController::class, 'analista'])->name('dashboard.analista');
    Route::get('/director', [DashboardController::class, 'director'])->name('dashboard.director');
    Route::get('/supervisor', [DashboardController::class, 'supervisor'])->name('dashboard.supervisor');
});

Route::get('/dashboard', function () {
    $usuarioId = session('id');

    if (!$usuarioId) {
        return redirect()->route('login');
    }

    $usuario = Usuario::with('rol')->find($usuarioId);



    if (!$usuario || !$usuario->rol) {
        abort(403, 'Rol no asignado');
    }

    $rol = $usuario->rol->nombre_rol;

    switch ($rol) {
        case 'ADMINISTRADOR':
            return redirect()->route('dashboard.admin');
        case 'ANALISTA':
            return redirect()->route('dashboard.analista');
        case 'SUPERVISOR':
            return redirect()->route('dashboard.supervisor');
        case 'DIRECTOR':
            return redirect()->route('dashboard.director');
        default:
            abort(403, 'Rol no válido');
    }
})->middleware('auth.custom')->name('dashboard');

Route::get('/gestion_usuarios', [App\Http\Controllers\AdminController::class, 'gestionUsuarios'])
    ->name('gestion.usuarios')
    ->middleware('auth.custom');
