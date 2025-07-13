@extends('layouts.app')

@section('title', 'Editar Infraestructura del Plantel')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Editar Infraestructura del Plantel: {{ $plantel->nombre ?? 'Sin nombre' }}</h2>

    {{-- FORMULARIO SERVICIOS --}}
    <div class="card mb-5">
        <div class="card-header bg-success text-white">
            <strong>Servicios Básicos</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('infraestructura.update_servicios', $plantel->cct) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Electricidad con contrato</label>
                    <select name="electricidad_contrato" class="form-control" required>
                        <option value="Sí" {{ $servicio->electricidad_contrato ? 'selected' : '' }}>Sí</option>
                        <option value="No" {{ !$servicio->electricidad_contrato ? 'selected' : '' }}>No</option>
                    </select>

                    <div class="mb-3">
                        <label>Telefonia fija</label>
                        <select name="telefonia_fija" class="form-control" required>
                            <option value="Sí" {{ $servicio->telefonia_fija ? 'selected' : '' }}>Sí</option>
                            <option value="No" {{ !$servicio->telefonia_fija ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Electricidad con contrato</label>
                        <select name="internet_acceso" class="form-control" required>
                            <option value="Sí" {{ $servicio->internet_acceso ? 'selected' : '' }}>Sí</option>
                            <option value="No" {{ !$servicio->internet_acceso ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">Guardar Servicios</button>
            </form>
        </div>
    </div>

    {{-- FORMULARIO HIDROSANITARIO --}}
    <div class="card mb-5">
        <div class="card-header bg-primary text-white">
            <strong>Hidrosanitario</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('infraestructura.update_hidrosanitario', $plantel->cct) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Fuente de Agua</label>
                    <input type="text" name="fuente_agua" class="form-control" value="{{ old('fuente_agua', $hidrosanitario->fuente_agua ?? '') }}">
                </div>

                <div class="mb-3">
                    <label>Tipo de Drenaje</label>
                    <input type="text" name="tipo_drenaje" class="form-control" value="{{ old('tipo_drenaje', $hidrosanitario->tipo_drenaje ?? '') }}">
                </div>

                <button type="submit" class="btn btn-primary">Guardar Hidrosanitario</button>
            </form>
        </div>
    </div>

    <a href="{{ route('infraestructura.mostrar', $plantel->cct) }}" class="btn btn-secondary">Volver</a>
</div>
@endsection