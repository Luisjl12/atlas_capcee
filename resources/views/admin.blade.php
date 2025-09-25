@extends('layouts.app')
@section('content')
<!--Vista del dashboard del admistrador -->
<!--Cabecera y footers estan el layout-->
<div class="container mt-4">
    <div class="dashboard-welcome card-header-custom">
        <h2><i class="fas fa-tachometer-alt"></i><strong> Panel Principal</strong></h2>
    </div>

    <div class="card-body-custom pa-4">
        <p class="lead">¡Bienvenido,<strong> {{ session('nombre_completo') }}!</strong></p>
        <p>Has iniciado sesión como <strong>ADMINISTRADOR</strong></p>
        <div class="separador"></div>
        <h6><strong>Acciones Disponibles:</strong></h6>
        <nav class="dashboard-nav">
            <div class="contenedor-acciones">
                <div class="columna-acciones">
                    <a href="{{route('gestion.usuarios')}}" class="accion-card red">
                        <i class="fas fa-users-cog"></i> Gestión de Usuarios
                    </a>
                    <a href="{{route('planteles.index')}}" class="accion-card red">
                        <i class="fas fa-school"></i> Gestionar Planteles
                    </a>

                    <a href="{{route('mapa.vista')}}" class="accion-card red">
                        <i class="fas fa-map"></i> Mapa de planteles
                    </a>
                </div>
                <div class="columna-acciones">

                    <a href="{{route('reportes.index')}}" class="accion-card yellow">
                        <i class="fas fa-chart-bar"></i> Gestión de reportes
                    </a>
                    <a href="{{route('panel.supervision')}}" class="accion-card yellow">
                        <i class="fas fa-tasks"></i> Panel de Supervisión
                    </a>
                    <a href="{{route('importarDatos.show')}}" class="accion-card green">
                        <i class="fas fa-upload"></i> Importar Datos
                    </a>
                </div>
                <div class="columna-acciones">
                    <a href="{{route('busqueda.avanzada')}}" class="accion-card green">
                        <i class="fas fa-search"></i> Buscador Avanzado
                    </a>
                    <a href="{{route('perfil')}}" class="accion-card green">
                        <i class="fas fa-user-edit"></i> Mi Perfil
                    </a>
                </div>

            </div>
        </nav>
    </div>
</div>

@endsection