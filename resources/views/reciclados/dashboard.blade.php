{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Panel principal')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="card-title">Bienvenido, {{ session('nombre_completo') ?? 'Usuario' }} 👋</h1>
            <p class="card-text">Has iniciado sesión correctamente</p>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">Cerrar sesión</button>
            </form>

            <a href="{{ route('perfil') }}" class="perfil-btn">Ver Perfil</a>
        </div>
    </div>
</div>
@endsection