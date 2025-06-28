<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;



Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth.custom')->name('dashboard');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/perfil', function () {
    return view('perfil');
})->middleware('auth.custom')->name('perfil');
