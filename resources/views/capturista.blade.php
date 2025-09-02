@extends('layouts.app')
@section('content')

<!--Dashboard para capturista-->

<div class="container mt-4">
    <div class="dashboard-welcome card-header-custom">
        <h2><i class="fas fa-tachometer-alt"></i><strong> Panel Principal</strong></h2>
    </div>

    <div class="card-body-custom pa-4">
        <p class="lead">¡Bienvenido,<strong> {{ session('nombre_completo') }}!</strong></p>
        <p>Has iniciado sesión como <strong>CAPTURISTA</strong></p>
        <div class="separador"></div>
        <h6><strong>Acciones Disponibles:</strong></h6>
        <nav class="dashboard-nav">
            <div class="contenedor-acciones">
                <div class="columna-acciones">
                    <a href="{{route('importarDatos.show')}}" class="accion-card green">
                        <i class="fas fa-upload"></i> Importar Datos
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
</div>

@endsection