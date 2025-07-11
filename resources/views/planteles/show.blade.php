@extends('layouts.app')

@section('title', 'Ver Plantel')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h4>
            <i class="fas fa-school"></i> {{ $plantel->nombre }} <small class="text-muted">(CCT: {{ $plantel->id }})</small>
        </h4>
        <a href="{{ route('planteles.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al Listado
        </a>
    </div>

    <ul class="nav nav-tabs mt-4" id="plantelTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="ficha-base-tab" data-bs-toggle="tab" href="#ficha-base" role="tab">Ficha Base</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="espacios-tab" data-bs-toggle="tab" href="#espacios" role="tab">Espacios / Áreas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="servicios-tab" data-bs-toggle="tab" href="#servicios" role="tab">Servicios</a>
        </li>
        <!-- Agrega más tabs aquí -->
    </ul>

    <div class="tab-content mt-3" id="plantelTabContent">
        <!-- Ficha Base -->
        <div class="tab-pane fade show active" id="ficha-base" role="tabpanel">
            <h5 class="text-primary">Información General del Plantel</h5>
            <div class="row mt-3">
                <div class="col-md-4"><strong>Nombre Oficial:</strong> {{ $plantel->nombre_escuela }}</div>
                <div class="col-md-4"><strong>Nivel Educativo:</strong> {{ $plantel->nivel_educativo }}</div>
                <div class="col-md-4"><strong>Turno:</strong> {{ $plantel->turno }}</div>
                <div class="col-md-4"><strong>Sostenimiento:</strong> {{ $plantel->sostenimiento }}</div>
            </div>
            <a href="{{ route('planteles.edit', $plantel->id) }}" class="btn btn-warning mt-3">
                <i class="fas fa-edit"></i> Editar Ficha Base
            </a>
        </div>

        <!-- Espacios / Áreas -->
        <div class="tab-pane fade" id="espacios" role="tabpanel">
            <h5 class="text-primary">Contenido de espacios y areas aqui....</h5>
            <div class="row mt-3">
                <h5 class="text-primary">Registrar nuevo espacio</h5>
                <form action="{{ route('espacios.store') }}" method="POST" class="row g-3 mb-4">
                    @csrf
                    <input type="hidden" name="cct" value="{{ $plantel->id }}">

                    <div class="col-md-4">
                        <label for="nombre_espacio" class="form-label">Nombre del Espacio</label>
                        <input type="text" name="nombre_espacio" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label for="cantidad" class="form-label">Cantidad</label>
                        <input type="number" name="cantidad" class="form-control" min="1" required>
                    </div>
                    <div class="col-md-4">
                        <label for="estado_conservacion" class="form-label">Estado de Conservación</label>
                        <input type="text" name="estado_conservacion" class="form-control" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-plus-circle"></i> Agregar
                        </button>
                    </div>
                </form>
                @forelse ($plantel ->espacios as $espacio)
                <div class="row mb-2">
                    <div class="col-md-4"><strong>Nombre:</strong> {{ $espacio->nombre_espacio }}</div>
                    <div class="col-md-4"><strong>Cantidad:</strong> {{ $espacio->cantidad }}</div>
                    <div class="col-md-4"><strong>Estado:</strong> {{ $espacio->estado_conservacion }}</div>
                </div>
                @empty
                <p>No hay espacios registrados para este plantel</p>
                @endforelse
            </div>
            <a href="{{ route('planteles.edit_espacios', $plantel->id) }}" class="btn btn-warning mt-3">
                <i class="fas fa-edit"></i> Editar Espacios / Áreas
            </a>
        </div>

        <!-- Servicios -->
        <div class="tab-pane fade" id="servicios" role="tabpanel">
            <p>Contenido de servicios aquí...</p>
            <a href="{{ route('planteles.edit_servicios', $plantel->id) }}" class="btn btn-warning mt-3">
                <i class="fas fa-edit"></i> Editar Servicios
            </a>
        </div>
    </div>
</div>
@endsection