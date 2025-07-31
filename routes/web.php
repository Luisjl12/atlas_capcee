<?php
//Controladores 

use App\Http\Controllers\ArchivoPlantelController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\perfilController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EspacioAreaController;
use App\Http\Controllers\InfraestructuraController;
use App\Models\Usuario;
use PhpParser\Builder\Function_;
use PhpParser\Node\Expr\FuncCall;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PlantelController;
use App\Http\Controllers\DetalleProteccionCivilController;
use App\Http\Controllers\GaleriaFotoController;


//Rutas para ver y acceder al login, accion del logout
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//Rutas para acceder al perfil  
Route::get('/perfil', function () {
    return view('perfil');
})->middleware('auth.custom')->name('perfil');

//Rutas para cambiar el password
Route::get('/cambiar-password', [perfilController::class, 'formCambiarPassword'])
    ->name('perfil.cambiar-password')
    ->middleware('auth.custom');
//Ruta para actusalizar datos personales
Route::post('/perfil/actualizar-datos', [PerfilController::class, 'actualizarDatosPersonales'])->name('perfil.actualizarDatos');

//Rutas para actualizar el password
Route::post('/cambiar-password', [perfilController::class, 'actualizarPassword'])
    ->name('perfil.actualizar-password')
    ->middleware('auth.custom');

//Rutas para acceder segun el rol segun el login
Route::middleware(['auth.custom'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/analista', [DashboardController::class, 'analista'])->name('dashboard.analista');
    Route::get('/director', [DashboardController::class, 'director'])->name('dashboard.director');
    Route::get('/supervisor', [DashboardController::class, 'supervisor'])->name('dashboard.supervisor');
});

//Rutas para acceder al dashboard desde la barra superior 
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


//Ruta para acceder al menu de gestion de usuarios y buscar usuarios
Route::get('/gestion_usuarios', [App\Http\Controllers\AdminController::class, 'gestionUsuarios'])
    ->name('gestion.usuarios')
    ->middleware('auth.custom');


//Ruta para acceder a los diferentes acciones de gestion de usuarios 
Route::middleware(['auth.custom'])->prefix('usuarios')->group(function () {
    Route::get('/', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/crear', [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::post('/guardar', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::get('/{id}/editar', [UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::put('/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
});


//Rutas para planteles y buscador interactivo de plantel 
Route::get('/planteles/buscar', [PlantelController::class, 'buscar'])->name('planteles.buscar');

Route::resource('planteles', PlantelController::class);

//Agregar planteles 
Route::post('/planteles', [PlantelController::class, 'store'])->name('planteles.store'); // Sección I

Route::put('/planteles/{id}/ubicacion', [PlantelController::class, 'updateUbicacion'])->name('planteles.update.ubicacion'); // Sección II
Route::put('/planteles/{id}/contacto', [PlantelController::class, 'updateContacto'])->name('planteles.update.contacto'); // Sección III
Route::put('/planteles/{id}/accesibilidad', [PlantelController::class, 'updateAccesibilidad'])->name('planteles.update.accesibilidad'); // Sección IV
Route::put('/planteles/{id}/usuarios', [PlantelController::class, 'updateTotalUsuariosPlanteles'])->name('planteles.update.totalUsuariosPlanteles'); // Sección V
Route::put('/planteles/{id}/estatus', [PlantelController::class, 'updateEstatus'])->name('planteles.update.estatus'); // Sección VI



//Rutas para las "ver planteles"
Route::get('/planteles/{id}', [PlantelController::class, 'show'])->name('planteles.show');
// Mostrar formulario de edición de espacios
Route::get('/planteles/{id}/editar-espacios', [PlantelController::class, 'editEspacios'])->name('planteles.edit_espacios');
Route::get('/planteles/{id}/editar-servicios', [PlantelController::class, 'editServicios'])->name('planteles.edit_servicios');
Route::get('/planteles/{cct}/editar-espacios', [PlantelController::class, 'editEspacios'])->name('planteles.edit_espacios');
Route::delete('/espacios/{id}', [EspacioAreaController::class, 'destroy'])->name('espacios.destroy');
Route::post('/espacios', [EspacioAreaController::class, 'store'])->name('espacios.store');

//Mostrar detalles del plantel 
Route::get('/infraestructura/{cct}', [InfraestructuraController::class, 'mostrarInfraestructura'])->name('infraestructura.mostrar');


//Editar o agregar servicios  
Route::get('/infraestructura/{cct}/editar_servicios', [InfraestructuraController::class, 'editServicios'])->name('infraestructura.edit_servicios');
Route::put('/infraestructura/{cct}/actualizar_servicios', [InfraestructuraController::class, 'updateServicios'])->name('infraestructura.update_servicios');

//Editar o agregar hidrosanitario 
Route::get('/infraestructura/{cct}/editar_hidrosanitario', [InfraestructuraController::class, 'editHidrosanitario'])->name('infraestructura.edit_hidrosanitario');
Route::put('infraestructura/{cct}/actualizar_hidrosanitario', [InfraestructuraController::class, 'updateHidrosanitario'])->name('infraestructura.update_hidrosanitario');
Route::get('/infraestructura/{cct}/editar', [InfraestructuraController::class, 'editarInfraestructuraCompleta'])->name('infraestructura.editar_completa');


//Ruta para detalles proteccion civil
Route::get('/planteles/{id}/editar-proteccion-civil', [PlantelController::class, 'editarProteccionCivil'])->name('planteles.editar_proteccion_civil');
// Crear nuevo detalle de Protección Civil
Route::post('/planteles/{cct}/proteccion-civil', [PlantelController::class, 'guardarProteccionCivil'])->name('detalle_proteccion_civil.store');

Route::get('/municipios/{id}/localidades', [PlantelController::class, 'getLocalidades']);
Route::get('/localidades/{municipio_id}', [\App\Http\Controllers\LocalidadController::class, 'getPorMunicipio']);

//Rutas para ver los archivos
Route::post('/planteles/{id}/archivos', [ArchivoPlantelController::class, 'store'])->name('archivos.store')->middleware('auth.custom');
Route::get('/planteles/{id}', [ArchivoPlantelController::class, 'show'])->name('planteles.show');
//Ruta para descargar los archivos 
Route::get('/archivos/{archivo}/descargar', [ArchivoPlantelController::class, 'descargar'])->name('archivos.descargar');
//Eliminar achivo
Route::delete('/archivods{id}', [ArchivoPlantelController::class, 'destroy'])->name('archivos.destroy');

//Rutas para acceder a la galeria de fotos
Route::get('/planteles/{cct}/galeria', [GaleriaFotoController::class, 'create'])->name('galeria.fotos');
Route::post('/planteles/{cct}/galeria', [GaleriaFotoController::class, 'store'])->name('galeria.store');
//Ruta para eliminar fotos 
Route::delete('/galeria/{foto}', [GaleriaFotoController::class, 'destroy'])->name('galeria.destroy');


//Buscadores interactivos de usuarios
Route::get('/usuarios/buscar', [UsuarioController::class, 'buscar'])->name('usuarios.buscar');
