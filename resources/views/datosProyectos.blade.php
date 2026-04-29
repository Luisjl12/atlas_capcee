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
    <h3 class="mb-3">Registros en la base de datos</h3>
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Folio PPI</th>
                <th>Municipio</th>
                <th>Nombre Proyecto</th>
                <th>Monto Inversión</th>
                <th>Inicio</th>
                <th>Término</th>
                <th>Empresa</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registros as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->folio_ppi }}</td>
                    <td>{{ $row->municipio }}</td>
                    <td>{{ $row->nombre_proyecto }}</td>
                    <td>{{ number_format($row->monto_inversion, 2) }}</td>
                    <td>{{ $row->inicio }}</td>
                    <td>{{ $row->termino }}</td>
                    <td>{{ $row->empresa }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-1">
                            <!-- Botón Editar -->
                            <a href="{{ route('proyectos.edit', $row->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-pen"></i>
                            </a>

                            <!-- Botón Eliminar -->
                            <form action="{{ route('proyectos.destroy', $row->id) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres eliminar este proyecto?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
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

{{-- Paginación --}}
@if($registros->hasPages())
    <nav class="pagination-container" aria-label="Navegación de páginas">
        {{ $registros->links('vendor.pagination.mi_paginacion') }}
    </nav>
@endif

@endsection
