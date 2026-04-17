@extends('layouts.app')

@section('content')


<div class="card-header-custom">
    @php
    use App\Helpers\RoleHelper;
    @endphp
    <a href="{{RoleHelper::historialVista(session('role_id')) }}" class="btn-icon-only">
        <i class="fas fa-arrow-left"></i>
        <h2><i class="fas fa-history"></i> Historial de Cambios</h2>
    </a>
</div>

<div class="mt-3 data-table-container">
    <table class="table data-table">
        <thead class="thead-custom">
            <tr>
                <th>Fecha</th>
                <th>Entidad</th>
                <th>CCT / Plantel</th>
                <th>Evento</th>
                <th>Detalles</th>
                <th>Cambios realizados</th>
            </tr>
        </thead>
        <tbody id="tbody-js">
            @forelse ($auditorias as $audit)
            {{-- Fila móvil --}}
            <tr class="usuario-row d-table-row d-md-none">
                <td colspan="6" class="usuario-nombre position-relative" style="cursor: pointer;">
                
                        @if($audit->auditable instanceof \App\Models\Plantel)
                        {{ $audit->auditable->cct ?? '—' }} - {{ $audit->auditable->nombre_escuela ?? 'Plantel eliminado' }}
                        @elseif($audit->auditable instanceof \App\Models\DetalleServicio)
                        {{ $audit->auditable->cct ?? 'Sin CCT' }} - {{ $audit->auditable->plantel->nombre_escuela ?? 'Sin nombre' }}
                        @elseif($audit->auditable instanceof \App\Models\DetalleHidrosanitario)
                        {{ $audit->auditable->cct ?? 'Sin CCT' }} - {{ optional($audit->auditable->plantel)->nombre_escuela ?? 'Sin nombre' }}
                        @else
                        —
                        @endif
                    
                    <div class="toggle-icon position-absolute top-0 end-0 p-2">
                        <i class="fas fa-chevron-down text-muted"></i>
                    </div>
                </td>
            </tr>

            {{-- Detalles móviles --}}
            <tr class="usuario-detalle d-none d-md-none">
                <td colspan="6">
                    <div class="detalle-container d-flex flex-wrap gap-3">
                        <div class="detalle-bloque flex-grow-1" style="min-width: 250px;">
                            <strong>Fecha:</strong> {{ $audit->created_at->format('d/m/Y H:i') }}<br>
                            <strong>Evento:</strong> {{ ucfirst($audit->event) }}<br>

                            @if($audit->auditable instanceof \App\Models\Plantel)
                            <strong>Entidad:</strong> Plantel<br>
                            <strong>CCT:</strong> {{ $audit->auditable->cct ?? '—' }}<br>
                            <strong>Nombre:</strong> {{ $audit->auditable->nombre_escuela ?? '—' }}<br>
                            @elseif($audit->auditable instanceof \App\Models\DetalleServicio)
                            <strong>Entidad:</strong> Servicios<br>
                            <strong>CCT:</strong> {{ $audit->auditable->cct ?? '—' }}<br>
                            <strong>Nombre:</strong> {{ $audit->auditable->plantel->nombre_escuela ?? '—' }}<br>
                            <strong>Internet:</strong> {{ $audit->auditable->internet_tipo ?? '—' }}<br>
                            @elseif($audit->auditable instanceof \App\Models\DetalleProteccionCivil)
                            <strong>Entidad:</strong> Protección Civil<br>
                            <strong>CCT:</strong> {{ $audit->auditable->cct ?? '—' }}<br>
                            <strong>Extintores vigentes:</strong> {{ $audit->auditable->extintores_vigentes ?? '—' }}<br>
                            @elseif($audit->auditable instanceof \App\Models\DetalleHidrosanitario)
                            <strong>Entidad:</strong> Hidrosanitario<br>
                            <strong>CCT:</strong> {{ $audit->auditable->cct ?? '—' }}<br>
                            <strong>Nombre:</strong> {{ optional($audit->auditable->plantel)->nombre_escuela ?? '—' }}<br>
                            <strong>Fuente de agua:</strong> {{ $audit->auditable->fuente_agua ?? '—' }}<br>
                            <strong>Tipo de drenaje:</strong> {{ $audit->auditable->tipo_drenaje ?? '—' }}<br>
                            @endif

                        </div>

                        <div class="detalle-bloque flex-grow-1" style="min-width: 250px;">
                            <strong>Detalles:</strong><br>
                            @forelse($audit->new_values as $campo => $valor)
                            <strong>{{ $campo }}:</strong> {{ $valor }}<br>
                            @empty
                            <em>No hay cambios registrados.</em>
                            @endforelse

                            <strong>Tags:</strong><br>
                            @forelse(json_decode($audit->tags ?? '[]') as $tag)
                            <span class="badge status-historial me-1">{{ $tag }}</span>
                            @empty
                            <em>Sin etiquetas.</em>
                            @endforelse
                        </div>
                    </div>
                </td>
            </tr>

            {{-- Fila escritorio --}}
            <tr class="d-none d-md-table-row">
                <td>{{ $audit->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    @if($audit->auditable instanceof \App\Models\Plantel)
                    Plantel
                    @elseif($audit->auditable instanceof \App\Models\DetalleServicio)
                    Servicios

                    @elseif($audit->auditable instanceof \App\Models\DetalleProteccionCivil)
                    Protección Civil
                    @elseif($audit->auditable instanceof \App\Models\DetalleHidrosanitario)
                    Hidrosanitario
                    @else
                    —
                    @endif
                </td>
                <td>
                    @if($audit->auditable instanceof \App\Models\Plantel)
                    {{ $audit->auditable->cct ?? '—' }} - {{ $audit->auditable->nombre_escuela ?? 'Plantel eliminado' }}
                    @elseif($audit->auditable instanceof \App\Models\DetalleServicio)
                    {{ $audit->auditable->cct ?? 'Sin CCT' }} - {{ $audit->auditable->plantel->nombre_escuela ?? 'Sin nombre' }}

                    @elseif($audit->auditable instanceof \App\Models\DetalleProteccionCivil)
                    {{ $audit->auditable->cct ?? 'Sin CCT' }}
                    @elseif($audit->auditable instanceof \App\Models\DetalleHidrosanitario)
                    {{ $audit->auditable->cct ?? 'Sin CCT' }} - {{ optional($audit->auditable->plantel)->nombre_escuela ?? 'Sin nombre' }}

                    @else
                    —
                    @endif
                </td>
                <td>{{ ucfirst($audit->event) }}</td>
                <td>
                    @foreach($audit->new_values as $campo => $valor)
                    <strong>{{ $campo }}:</strong> {{ $valor }}<br>
                    @endforeach
                </td>
                <td>
                    @foreach(json_decode($audit->tags ?? '[]') as $tag)
                    <span class="badge status-historial me-1">{{ $tag }}</span>
                    @endforeach
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No hay modificaciones registradas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
 {{-- Paginación --}}
    @if(method_exists($auditorias, 'links'))
    <nav class="pagination-container" aria-label="Navegación de páginas">
        <ul class="pagination">
            <li class="page-item">
                {{ $auditorias->links('vendor.pagination.mi_paginacion') }}
            </li>
        </ul>
    </nav>
    @endif

@push('scripts')
<script>
    window.addEventListener('load', function() {
        const filasPrincipales = document.querySelectorAll('.usuario-row');

        filasPrincipales.forEach(fila => {
            fila.addEventListener('click', function() {
                const siguiente = fila.nextElementSibling;

                if (siguiente && siguiente.classList.contains('usuario-detalle')) {
                    siguiente.classList.toggle('d-none');

                    const icono = fila.querySelector('.toggle-icon i');
                    if (icono) {
                        icono.classList.toggle('fa-chevron-down');
                        icono.classList.toggle('fa-chevron-up');
                    }
                }
            });
        });
    });
</script>
@endpush

@endsection
