@extends('layouts.app')
@section('content')
<!--Vista del dashboard del analista -->


<div class="container mt-4">
    <div class="dashboard-welcome card-header-custom">
        <h2><i class="fas fa-tachometer-alt"></i> Panel Principal</h2>
    </div>
</div>

<div class="container mt-4">
    <p class="lead">¡Bienvenido,<strong> {{ session('nombre_completo') }}!</strong></p>
    <p>Has iniciado sesión como <strong>ANALISTA</strong></p>
    <div class="separador"></div>
    <h6>Acciones Disponibles:</h6>
    <nav class="dashboard-nav">
        <div class="contenedor-acciones">
            <div class="columna-acciones">
                <a href="{{route('reportes.index')}}" class="accion-card yellow">
                    <i class="fas fa-chart-bar"></i> Gestión de reportes
                </a>
            </div>
            <div class="columna-acciones">
                <a href="{{route('busqueda.avanzada')}}" class="accion-card green">
                    <i class="fas fa-search"></i> Buscador Avanzado
                </a>
            </div>
        </div>
    </nav>

</div>

@endsection