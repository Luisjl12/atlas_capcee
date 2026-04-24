@extends('layouts.app')

@section('title', 'Reporte Escuelas al 100')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark"><i class="fas fa-certificate text-primary"></i> Listado: Escuelas al 100</h2>
        <div>
            <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <button class="btn btn-success btn-sm"><i class="fas fa-file-excel"></i> Exportar a Excel</button>
        </div>
    </div>

    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body bg-light">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="municipio" class="form-control" placeholder="Buscar por Municipio..." value="{{ request('municipio') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background-color: #005088; color: white;">
                    <tr>
                        <th>Microrregión</th>
                        <th>Municipio</th>
                        <th>Localidad</th>
                        <th>Plantel</th>
                        <th>CCT</th>
                        <th>Meta</th>
                        <th>Monto Contratado</th>
                        <th class="text-center">Avance Físico</th>
                        <th class="text-center">Mapa</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($datos as $escuela)
                    <tr>
                        <td class="fw-bold">{{ $escuela->microrregion ?? 'N/A' }}</td>
                        <td>{{ $escuela->municipio ?? 'N/A' }}</td>
                        <td>{{ $escuela->localidad ?? 'N/A' }}</td>
                        <td>{{ $escuela->plantel ?? 'N/A' }}</td>
                        <td><code class="text-dark">{{ $escuela->cct ?? 'N/A' }}</code></td>
                        
                        <td><small>{{ $escuela->meta ?? 'Sin definir' }}</small></td>
                        <td class="text-end fw-bold">${{ number_format((float)($escuela->monto ?? 0), 2) }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $escuela->avance_final ?? 0 }}%"></div>
                                </div>
                                <span class="small fw-bold">{{ $escuela->avance_final ?? 0 }}%</span>
                            </div>
                        </td>
                        
                        <td class="text-center">
                            @if($escuela->latitud && $escuela->longitud)
                                <a target="_blank" href="{{ route('mapa.individual', $escuela->id) }}" class="btn btn-sm" style="background-color: #3F51B5; color: white;" title="Ver en Mapa">
                                    <i class="fas fa-map-marker-alt"></i> Mapa
                                </a>
                            @else
                                <button class="btn btn-sm btn-secondary" disabled title="Sin coordenadas"><i class="fas fa-map-marker-alt"></i></button>
                            @endif
                        </td>
                        
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fa-3x mb-3"></i><br>
                            No se encontraron registros en el programa Escuelas al 100.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection