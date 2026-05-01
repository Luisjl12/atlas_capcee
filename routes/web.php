<?php
//Controladores 

use Illuminate\Support\Facades\Auth;
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
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\BusquedaController;
use App\Http\Controllers\SupervicionController;
use App\Http\Controllers\ImportarDatosController;
use App\Http\Controllers\MapaController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\InmuebleNivelController;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\InfraescolarController;
use App\Http\Controllers\ComparacionController; 
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\MapaCienController;
use App\Http\Controllers\ImportarDatosCapceeController; 
use App\Http\Controllers\DatosProyectosController; 
use App\Http\Controllers\ProyectoInversionController; 
use App\Http\Controllers\ProyectosEspecialesController; 

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
//Ruta para actualizar datos personales
Route::post('/perfil/actualizar-datos', [PerfilController::class, 'actualizarDatosPersonales'])->name('perfil.actualizarDatos');

//Rutas para actualizar el password
Route::post('/cambiar-password', [perfilController::class, 'actualizarPassword'])
    ->name('perfil.actualizar-password')
    ->middleware('auth.custom');

//Rutas para acceder segun el rol desde el login
Route::middleware(['auth.custom'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/analista', [DashboardController::class, 'analista'])->name('dashboard.analista');
    Route::get('/director', [DashboardController::class, 'director'])->name('dashboard.director');
    Route::get('/supervisor', [DashboardController::class, 'supervisor'])->name('dashboard.supervisor');
    Route::get('/capturista', [DashboardController::class, 'capturista'])->name('dashboard.capturista');
    Route::get('/visualizador', [DashboardController::class, 'visualizador'])->name('dashboard.visualizador');
    Route::get('/director_reportes',[DashboardController::class, 'directorReportes'])->name('dashboard.directorReportes'); 
    Route::get('/administrador_principal', [DashboardController::class, 'administradorPrincipal'])->name('dashboard.administradorPrincipal'); 
    Route::get('/proyectos-especiales', [DashboardController::class, 'proyectosEspeciales'])->name('dashboard.proyectosEspeciales'); 
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
        case 'CAPTURISTA':
            return redirect()->route('dashboard.capturista');
        case 'Visualizador':
            return redirect()->route('dashboard.visualizador');
         case 'DIRECTOR REPORTE':
            return redirect()->route('dashboard.directorReportes');
        case 'ADMINISTRADOR PRINCIPAL':
            return redirect()->route('dashboard.administradorPrincipal');
        case 'PROYECTOS ESPECIALES': 
            return redirect()->route('dashboard.proyectosEspeciales'); 
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

//Ruta para filtro 
Route::get('/planteles/filtrar', [PlantelController::class, 'filtrarEstatus'])->name('planteles.filtrar');

Route::resource('planteles', PlantelController::class);

//Agregar planteles 
// Agrupamos todas las rutas de actualización de planteles bajo el middleware de protección
Route::middleware([\App\Http\Middleware\ProteccionLecturaSiie::class])->group(function () {

    // Agregar planteles (Sección I)
    Route::post('/planteles', [PlantelController::class, 'store'])->name('planteles.store');

    // Actualizaciones de las secciones II a VI
    Route::put('/planteles/{id}/ubicacion', [PlantelController::class, 'updateUbicacion'])->name('planteles.update.ubicacion');
    Route::put('/planteles/{id}/contacto', [PlantelController::class, 'updateContacto'])->name('planteles.update.contacto');
    Route::put('/planteles/{id}/accesibilidad', [PlantelController::class, 'updateAccesibilidad'])->name('planteles.update.accesibilidad');
    Route::put('/planteles/{id}/usuarios', [PlantelController::class, 'updateTotalUsuariosPlanteles'])->name('planteles.update.totalUsuariosPlanteles');
    Route::put('/planteles/{id}/estatus', [PlantelController::class, 'updateEstatus'])->name('planteles.update.estatus');

});



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
//Ruta para visualizar archivo 
Route::get('/archivos-plantel/{id}/visualizar', [ArchivoPlantelController::class, 'visualizar'])->name('archivos-plantel.visualizar');

//Rutas para acceder a la galeria de fotos
Route::get('/planteles/{cct}/galeria', [GaleriaFotoController::class, 'create'])->name('galeria.fotos');
Route::post('/planteles/{cct}/galeria', [GaleriaFotoController::class, 'store'])->name('galeria.store');
//Ruta para eliminar fotos 
Route::delete('/galeria/{foto}', [GaleriaFotoController::class, 'destroy'])->name('galeria.destroy');


//Buscadores interactivos de usuarios
Route::get('/usuarios/buscar', [UsuarioController::class, 'buscar'])->name('usuarios.buscar');

//Ruta para dirigir a la seccion de generar reportes
Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
//Ruta para dirigir a la planteles por municipio dentro de la seccion de reportes 
Route::get('/reportes/municipio', [ReporteController::class, 'reporteMunicipio'])->name('reportes.municipio');
//Ruta para exportar los reportes a csv
Route::get('/reportes/municipio/exportar', [ReporteController::class, 'exportarMunicipiosCSV'])->name('reportes.municipios.exportar');
//Ruta para generar pdf
Route::get('/reportes/municipios/pdf', [ReporteController::class, 'exportarMunicipioPDF'])->name('reportes.municipio.pdf');

//Ruta para reportes segun el estatus
Route::get('/reportes/estatus-plantel', [ReporteController::class, 'reporteEstatusPlantel'])->name('reportes.estatus');
//Ruta para generar csv para reportes segun el estatus
Route::get('/reportes/estatus-plantel/csv', [ReporteController::class, 'exportarEstatusCSV'])->name('reportes.estatus.csv');
//Ruta para generar pdf para reportes segun el estatus
Route::get('/reportes/estatus-plantel/pdf', [ReporteController::class, 'exportarEstatusPDF'])->name('reportes.estatus.pdf');

//Ruta para reportes segun la infraestructura
Route::get('/reportes/infraestructura', [ReporteController::class, 'infraestructura'])->name('reportes.infraestructura');
//Ruta para exportar a csv detalles de infraestructura
Route::get('/reportes/infraestructura/exportar', [ReporteController::class, 'exportarInfraestructuraCSV'])
    ->name('reportes.infraestructura.exportar');
//Ruta para exportar a pdf detalle de infraestructura
Route::get('reportes/infraestructura/pdf', [ReporteController::class, 'exportarInfraestructuraPDF'])->name('reportes.infraestructura.pdf');


//Ruta para el buscador avanzado
Route::get('/busqueda-avanzada', [BusquedaController::class, 'index'])->name('busqueda.avanzada');


//Ruta para seccion de panel de supervicion 
Route::get('/panel-supervision', [SupervicionController::class, 'index'])->name('panel.supervision');
//Ruta para la accion de "ver" en el panel de supervicion
Route::get('/panel-supervision/show/{id}', [SupervicionController::class, 'show'])->name('supervision.show');


//Ruta para la seccion de importar datos
Route::get('/importar-datos', [ImportarDatosController::class, 'index'])->name('importarDatos.show');
//Ruta para importar los archivos 
Route::post('/importar-archivos', [ImportarDatosController::class, 'store'])->name('importarDatos.store');


//Ruta para visualizar mapas
Route::get('/mapa', [MapaController::class, 'vistaMapa'])->name('mapa.vista');
Route::get('/mapa-planteles', [MapaController::class, 'mapa'])->name('mapa.datos');



//Ruta para eliminar varias fotos seleccionadas
Route::post('/galeria/eliminar', [GaleriaFotoController::class, 'eliminarSeleccionadas'])->name('galeria.eliminarSeleccionadas');


//Notificaciones 
Route::get('/password/recuperar', function () {
    return view('notificaciones.notificaciones');
})->name('password.recuperar');
Route::post('/password/enviar-codigo', [ForgotPasswordController::class, 'sendCode'])->name('password.enviarCodigo');
Route::post('/password/verificar-codigo', [ForgotPasswordController::class, 'verifyCode'])->name('password.verificarCodigo');
Route::get('/password/reset', function (\Illuminate\Http\Request $request) {
    return view('notificaciones.reset', [
        'email' => $request->query('email'),
        'code' => $request->query('code'),
    ]);
})->name('password.reset.form');

Route::post('/password/reset', [ForgotPasswordController::class, 'resetPassword'])
    ->name('password.reset');

//Auditorias
Route::get('/planteles/{id}/auditorias', [PlantelController::class, 'mostrarAuditorias'])
    ->name('planteles.auditoria');

//Niveles 
Route::get('/plantel/{cct}/niveles', [InmuebleNivelController::class, 'mostrarNivelesPorCCT']);
//M2
//Route::get('/planteles/{cct}/detalles', [InmuebleSuperficieController::class, 'mostrarSuperficieCCT'])->name('planteles.detalles');


//Ruta para filtrar planteles en el mapa
Route::get('/filtrar-planteles', [MapaController::class, 'filtrar']);

Route::get('/filtrar-agua', [MapaController::class, 'filtrarAgua']);

Route::get('/filtrar-energia', [MapaController::class, 'filtrarPlantelesEnergia'])
    ->name('filtrar-energia');


Route::get('/filtrar-drenaje', [MapaController::class, 'filtrarPlantelesDrenaje'])->name('filtrar-drenaje');

Route::get('/filtrar-instalaciones', [MapaController::class, 'filtrarInstalaciones']);

Route::get('/filtrar-obras', [MapaController::class, 'filtrarPlantelesObras']);

Route::get('/filtrar-seguridad', [MapaController::class, 'filtrarPlantelesSeguridad']);

Route::get('/filtrar-accesibilidad', [MapaController::class, 'filtrarPlantelesAccesibilidad']);

Route::get('/filtrar-sanitarios', [MapaController::class, 'filtrarPlantelesSanitarios']);

Route::get('/filtrar-avanzado', [MapaController::class, 'filtrarAvanzado']);

Route::get('/buscar-cct/{cct}', [MapaController::class, 'buscarPorCCT']);

//Rutas para historial de cambios
Route::get('/historial-modificaciones', [HistorialController::class, 'index'])->name('historial.index');

//Rutas para exportar CSV
Route::get('/exportar-csv/{categoria}', [MapaController::class, 'exportarCSV']);

//Rutas para los nuevos roles
Route::get('/infraescolar/director', [InfraescolarController::class, 'indexDirector'])
    ->name('infraescolar.director');
    
Route::get('/infraescolar/admin', [InfraescolarController::class, 'indexAdmin'])
    ->name('infraescolar.admin');


// Rutas exclusivas del Administrador
Route::middleware(['auth.custom', 'rol:ADMINISTRADOR PRINCIPAL'])->group(function () {
    Route::get('/admin/infraescolar', [InfraescolarController::class, 'indexAdmin'])->name('infraescolar.admin');
    Route::post('/admin/infraescolar/guardar', [InfraescolarController::class, 'store'])->name('infraescolar.store');
    
    //  NUEVAS RUTAS PARA EDITAR Y ELIMINAR 
    Route::get('/admin/infraescolar/editar/{id}', [InfraescolarController::class, 'edit'])->name('infraescolar.edit');
    Route::post('/admin/infraescolar/actualizar/{id}', [InfraescolarController::class, 'update'])->name('infraescolar.update');
    Route::delete('/admin/infraescolar/eliminar/{id}', [InfraescolarController::class, 'destroy'])->name('infraescolar.destroy');
});

//  ESTE ES EL BLOQUE QUE FALTABA PARA EL DIRECTOR 
Route::middleware(['auth.custom', 'rol:DIRECTOR REPORTE'])->group(function () {
    Route::get('/director/infraescolar', [InfraescolarController::class, 'indexDirector'])->name('infraescolar.director');
});
// Rutas exclusivas del Director
Route::middleware(['auth.custom', 'rol:DIRECTOR REPORTE'])->group(function () {
    Route::get('/director/infraescolar', [InfraescolarController::class, 'indexDirector'])->name('infraescolar.director');
    
    //  NUEVA RUTA PARA FORZAR LA DESCARGA DEL PDF 
    Route::get('/director/infraescolar/pdf/{id}', [InfraescolarController::class, 'descargarPdf'])->name('infraescolar.descargar_pdf');
});

//Ruta para descargar los pdf
 Route::get('/director/infraescolar/pdf/{id}', [InfraescolarController::class, 'descargarPdf'])->name('infraescolar.descargar_pdf');
 

//Ruta para comparar datos de infraestructura
Route::get('/comparacion/form', [ComparacionController::class, 'mostrarFormulario'])->name('infraestructura.form');
Route::post('/comparacion/comparar',[ComparacionController::class, 'comparar'])->name('infraestructura.comparar');
//Descargar reporte de comparacion 
Route::get('/comparacion/exportar', [ComparacionController::class, 'exportarComparacion'])->name('infraestructura.exportar');
//Nueva vista para reportes de comparacion 
Route::get('/comparacion/reportes', [ComparacionController::class, 'reportesComparar'])
    ->name('reportes.comparacion');

Route::post('/comparacion/niveles', [ComparacionController::class, 'comparar'])
    ->name('comparacion.niveles.store');

Route::post('/comparacion/edificios', [ComparacionController::class, 'insertarEdificios'])
    ->name('comparacion.edificios.store');

Route::get('/comparacion/niveles/exportar', [ComparacionController::class, 'exportarNiveles'])
    ->name('reportes.niveles.exportar');

Route::get('/comparacion/edificios/exportar', [ComparacionController::class, 'exportarEdificios'])
    ->name('reportes.edificios.exportar');

Route::get('/comparacion/edificios', [ComparacionController::class, 'reportesComparar'])
    ->name('reportes.edificios');

Route::get('/saltar-a-siie', function () {
    $usuarioId = session('id');

    if (!$usuarioId) {
        return redirect()->route('login');
    }

    $usuarioDB = DB::table('usuarios')->where('id', $usuarioId)->first();

    $payload = [
        'username' => $usuarioDB->correo_electronico, 
        'rol' => session('rol') ?? 'ADMINISTRADOR',
        'iat' => time(),
        'exp' => time() + 60 
    ];

    $jwt = JWT::encode($payload, 'secreto_capcee_123_llave_super_segura', 'HS256');

    return redirect()->away("http://127.0.0.1:5000/sso-login?token=" . $jwt);
})->middleware('auth.custom');

Route::get('/sso-login', function (Request $request) {
    $token = $request->query('token');

    if (!$token) {
        return redirect()->route('login')->with('error', 'No se proporcionó un token de acceso.');
    }

    try {
        $llave_secreta = 'secreto_capcee_123_llave_super_segura';
        $decoded = JWT::decode($token, new Key($llave_secreta, 'HS256'));

        $usuario = DB::table('usuarios')
            ->where('correo_electronico', $decoded->username) 
            ->first();

        if ($usuario) {
            Auth::loginUsingId($usuario->id);
            session([
                'id' => $usuario->id,
                'rol' => $decoded->rol ?? 'ADMINISTRADOR',
                
                'nombre' => $usuario->nombre ?? ($usuario->correo_electronico ?? 'Usuario Atlas'), 
                'origen_siie'=>true
            ]);

            return redirect('/admin'); 
        } else {
            return "El usuario " . $decoded->username . " es válido pero no existe en Atlas.";
        }

    } catch (\Exception $e) {
        return "Error al validar el acceso desde SIIE: " . $e->getMessage();
    }
});

Route::post('/comparacion/agua', [ComparacionController::class, 'insertarAgua'])->name('comparacion.agua.store'); 

Route::get('/comparacion/agua/exportar', [ComparacionController::class, 'exportarAgua'])->name('reportes.agua.exportar'); 


Route::get('/reportes/escuelas-al-100', [App\Http\Controllers\ReporteController::class, 'escuelas100'])->name('reportes.escuelas100');

Route::put('/planteles/{id}/metas', [PlantelController::class, 'updateMetas'])->name('planteles.update.metas');

Route::get('/mapa-escuelas-cien',[MapaCienController::class, 'index'])->name('mapa.escuelasCien'); 

Route::get('/mapa/escuela/{id}', [App\Http\Controllers\ReporteController::class, 'mapaIndividual'])->name('mapa.individual');

Route::get('/admin/mapa-escuelas-100', [App\Http\Controllers\ReporteController::class, 'mapaEscuelas100General'])->name('mapa.escuelas100.general');

//
Route::post('/importar-escuelas', [ImportarDatosCapceeController::class, 'store'])->name('importar.escuelas'); 


//Importar datos intervencion del capcee / proyectos
Route::get('/proyectos', [DatosProyectosController::class, 'index'])->name('proyectos.index'); 

Route::post('/importar-proyectos', [DatosProyectosController::class, 'store'])->name('proyectos.importar');

Route::delete('/proyectos/{id}', [DatosProyectosController::class, 'destroy'])->name('proyectos.destroy'); 

Route::get('/proyectos/{id}/edit', [DatosProyectosController::class, 'edit'])->name('proyectos.edit');
Route::put('/proyectos/{id}', [DatosProyectosController::class, 'update'])->name('proyectos.update');
//Ver detalles de proyectos especiales 
Route::get('/proyectos{id}/ver-detalles', [DatosProyectosController::class, 'verDetalles'])->name('proyectos.detalle'); 



//Rutas para el rol de proyectos especiales 
Route::get('/seguimiento-proyectos', [ProyectosEspecialesController::class, 'index'])->name('seguimiento-proyectos'); 
