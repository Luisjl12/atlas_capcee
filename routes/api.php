<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\ComparacionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// routes/api.php
//Route::post('/infraestructura/comparar', [ComparacionController::class, 'comparar']);
//Route::get('/infraestructura/form', [ComparacionController::class, 'mostrarFormulario'])->name('infraestructura.form');
//Route::post('/infraestructura/comparar',[ComparacionController::class, 'comparar'])->name('infraestructura.comparar');