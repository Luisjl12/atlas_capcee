@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Importar Proyectos</h2>

    <!-- Formulario de importación -->
    <form action="{{ route('proyectos.importar') }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf
        <input type="file" name="file" required>
        <button type="submit" class="btn btn-primary">Importar</button>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Tabla de registros -->
    <div class="table-responsive mt-3 data-table-container">
    <!--Tabla para proyectos-->
    <table class="table data-table">
        <thead class="thead-custom">
            <tr>
                <th>Folio PPI</th>
                <th>CCT</th>
                <th>Municipio</th>
                <th>Nombre Proyecto</th>
                <th>Monto Inversión</th>
                <th>Inicio</th>
                <th>Término</th>
                <th>Empresa</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tbody-js">
            @forelse ($registros as $row)
            <!-- Fila principal visible solo en móvil -->
            <tr class="proyecto-row d-table-row d-md-none">
                <td colspan="8" class="proyecto-nombre position-relative" style="cursor: pointer;">
                    <strong>Proyecto</strong>
                    <div>{{ $row->nombre_proyecto }}</div>
                    <div class="toggle-icon position-absolute top-0 end-0 p-2">
                        <i class="fas fa-chevron-down text-muted"></i>
                    </div>
                </td>
            </tr>

            <!-- Fila completa visible solo en escritorio -->
            <tr class="d-none d-md-table-row">
                <td>{{ $row->folio_ppi }}</td>
                <td>{{ $row->cct}}</td>
                <td>{{ $row->municipio }}</td>
                <td>{{ $row->nombre_proyecto }}</td>
                <td>{{ number_format($row->monto_inversion, 2) }}</td>
                <td>{{ $row->inicio }}</td>
                <td>{{ $row->termino }}</td>
                <td>{{ $row->empresa }}</td>
                <td>
                    <div class="acciones-btns d-flex align-items-center gap-1 flex-nowrap">
                        <a href="{{ route('proyectos.detalle', $row->id) }}" class="btn btn-sm btn-info custom-tooltip">
                            <i class="fas fa-eye"></i>
                            <span class="tooltiptext">Ver Detalle</span>
                        </a>
                        <a href="{{ route('proyectos.edit', $row->id) }}" class="btn btn-sm btn-primary custom-tooltip">
                            <i class="fas fa-pen"></i>
                            <span class="tooltiptext">Editar</span>
                        </a>
                        <form action="{{ route('proyectos.destroy', $row->id) }}" method="POST"
                              onsubmit="return confirm('¿Seguro que quieres eliminar este proyecto?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger custom-tooltip">
                                <i class="fas fa-trash"></i>
                                <span class="tooltiptext">Eliminar</span>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>

            <!-- Detalles expandibles solo en móvil -->
            <tr class="proyecto-detalle d-none d-md-none">
                <td colspan="8">
                    <div class="detalle-container d-flex flex-wrap justify-content-between gap-3">
                        <div class="detalle-bloque flex-grow-1" style="min-width: 250px;">
                            <strong>Folio PPI:</strong> {{ $row->folio_ppi }}<br>
                            <strong>CCT:</strong>{{$row->cct}}<br>
                            <strong>Municipio:</strong> {{ $row->municipio }}<br>
                            <strong>Nombre:</strong> {{ $row->nombre_proyecto }}<br>
                            <strong>Empresa:</strong> {{ $row->empresa }}
                        </div>
                        <div class="detalle-bloque flex-grow-1" style="min-width: 250px;">
                            <strong>Monto Inversión:</strong> {{ number_format($row->monto_inversion, 2) }}<br>
                            <strong>Inicio:</strong> {{ $row->inicio }}<br>
                            <strong>Término:</strong> {{ $row->termino }}
                        </div>
                        <div class="w-100 mt-2"><strong>Acciones</strong>
                            <div class="acciones-btns d-flex align-items-center gap-1 flex-wrap">
                                <a href="{{ route('proyectos.detalle', $row->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('proyectos.edit', $row->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('proyectos.destroy', $row->id) }}" method="POST"
                                      onsubmit="return confirm('¿Seguro que quieres eliminar este proyecto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">No hay registros disponibles.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

</div>

{{-- Paginación --}}
@if($registros->hasPages())
    <nav class="pagination-container" aria-label="Navegación de páginas">
        {{ $registros->links('vendor.pagination.mi_paginacion') }}
    </nav>
@endif

@endsection
