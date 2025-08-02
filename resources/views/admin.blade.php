@extends('layouts.app')
@section('content')
<!--Vista del dashboard del admistrador -->

<div class="dashboard-welcome card-header-custom">
    <h2><i class="fas fa-tachometer-alt"></i> Panel Principal</h2>
</div>

<div class="card-body-custom">
    <p class="lead">¡Bienvenido,<strong> {{ session('nombre_completo') }}!</strong></p>
    <p>Has iniciado sesión como <strong>ADMINISTRADOR</strong></p>
    <hr>
    <h6>Acciones Disponibles:</h6>
    <nav class="dashboard-nav">
        <div class="contenedor-acciones-grid">
            <div class="columna-acciones">
                <a href="{{route('gestion.usuarios')}}" class="accion-card red">
                    <i class="fas fa-users-cog"></i> Gestión de Usuarios
                </a>
                <a href="{{route('planteles.index')}}" class="accion-card red">
                    <i class="fas fa-school"></i> Gestionar Planteles
                </a>
            </div>
            <div class="columna-acciones">
                <a href="{{route('perfil')}}" class="accion-card green">
                    <i class="fas fa-user-edit"></i> Mi Perfil
                </a>
            </div>
        </div>
    </nav>
</div>

@endsection