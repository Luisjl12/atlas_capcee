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
        <!-- Agregar Tabs -->
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
            <h5 class="text-primary">Contenido de espacios y áreas aquí...</h5>

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

                @if ($plantel->espacios->isEmpty())
                <p>No hay espacios registrados para este plantel</p>
                @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-primary">
                            <tr>
                                <th>Nombre del Espacio</th>
                                <th>Cantidad</th>
                                <th>Estado de Conservación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($plantel->espacios as $espacio)
                            <tr>
                                <td>{{ $espacio->nombre_espacio }}</td>
                                <td>{{ $espacio->cantidad }}</td>
                                <td>{{ $espacio->estado_conservacion }}</td>

                                <td style="height: 40px;">

                                    <form action="{{ route('espacios.destroy', $espacio->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este espacio?')">
                                            Eliminar
                                        </button>
                                    </form>


                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>


        </div>


        <!-- Servicios -->
        <div class="tab-pane fade" id="servicios" role="tabpanel">
            <p>Contenido de servicios aquí...</p>
            <div class="row mt-3">
                <div class="col-12 mb-3">
                    <h5>Hidrosanitaria</h5>
                    <div class="row">
                        <div class="col-md-6"><strong>Fuente de Agua </strong>{{$hidrosanitario->fuente_agua ?? 'No disponible'}}</div>
                        <div class="col-md-6"><strong>Tipo Drenaje </strong>{{$hidrosanitario->tipo_drenaje ?? 'No disponible'}}</div>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <h5>Servicios Básicos</h5>
                    <div class="row">
                        <div class="col-md-4"><strong>Contrato Electricidad </strong>{{ $servicio->electricidad_contrato === 1 ? 'Sí' : ($servicio->electricidad_contrato === 0 ? 'No' : 'No disponible') }}</div>
                        <div class="col-md-4"><strong>Telefonia Fija </strong>{{ $servicio->telefonia_fija === 1 ? 'Sí' : ($servicio->telefonia_fija === 0 ? 'No' : 'No disponible') }}</div>
                        <div class="col-md-4"><strong>Acceso a Internet </strong>{{ $servicio->internet_acceso === 1 ? 'Sí' : ($servicio->internet_acceso === 0 ? 'No' : 'No disponible') }}</div>
                    </div>
                </div>
            </div>
            <a href="{{ route('infraestructura.editar_completa', $plantel->cct) }}" class="btn btn-primary mt-3">
                <i class="fas fa-edit"></i> Editar Infraestructura Completa
            </a>

        </div>


    </div>
</div>
</div>
@endsection