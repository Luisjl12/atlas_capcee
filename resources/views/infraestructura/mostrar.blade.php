@extends('layouts.app')

@section('title', 'Infraestructura del Plantel')

@section('content')
<div class="container mt-4">
    <h2>Infraestructura del Plantel: {{ $plantel->nombre_escuela ?? 'No disponible' }}</h2>

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Hidrosanitaria</h5>
            <p><strong>Fuente de agua:</strong> {{ $hidrosanitario->fuente_agua ?? 'No disponible' }}</p>
            <p><strong>Tipo de drenaje:</strong> {{ $hidrosanitario->tipo_drenaje ?? 'No disponible' }}</p>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Servicios Básicos</h5>
            <p><strong>Electricidad con contrato:</strong> {{ $servicio->electricidad_contrato ?? 'No disponible' }}</p>
            <p><strong>Telefonía fija:</strong> {{ $servicio->telefonia_fija ?? 'No disponible' }}</p>
            <p><strong>Acceso a internet:</strong> {{ $servicio->internet_acceso ?? 'No disponible' }}</p>
        </div>
    </div>

    <a href="{{ route('infraestructura.editar_completa', $plantel->cct) }}" class="btn btn-primary mt-4">
        <i class="fas fa-edit"></i> Editar Infraestructura
    </a>
</div>
@endsection