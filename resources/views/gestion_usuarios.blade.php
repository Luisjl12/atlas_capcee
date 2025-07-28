@extends('layouts.app')

@section('title', 'Gestionar Usuarios')

@section('content')

{{-- Mensajes flash --}}
@if(session('success'))
<div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger mt-3">{{ session('error') }}</div>
@endif

@includeIf('usuarios.partials.searcher')

{{-- Modal de eliminación, si también lo tienes como partial --}}
@includeIf('usuarios.partials.modal_eliminar')
<div class="container mt-4">
    <div class="card-header-custom d-flex justify-content-between align-items-center mb-3">
        <h2><i class="fas fa-users-cog me-2"></i> Gestión de Usuarios</h2>
        <a href="{{ route('usuarios.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Agregar Usuario
        </a>
    </div>

    <form method="GET" action="{{ route('usuarios.index') }}" class="search-container mb-4">
        <i class="fas fa-search search-icon"></i>
        <input type="text" name="buscar" class="form-control search-input"
            placeholder="Buscar por nombre o correo" value="{{ request('buscar') }}">
    </form>

</div>
<div class="mt-3 data-table-container">
    <table class="table data-table">
        <thead class="thead-custom">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Estatus</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tbody-js">
            @forelse ($usuarios as $usuario)
            <tr>
                <td data-label="ID">{{ $usuario->id }}</td>
                <td data-label="Nombre">{{ $usuario->nombre_completo }}</td>
                <td data-label="Correo">{{ $usuario->correo_electronico }}</td>
                <td data-label="Rol">{{ $usuario->rol->nombre_rol ?? 'N/D' }}</td>
                <td data-label="Estatus">
                    <span class="badge status-{{ strtolower($usuario->estado) }}">{{ $usuario->estado }}</span>
                </td>
                <td data-label="Acciones">
                    <div class="acciones-btns">
                        <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-sm btn-vino">
                            <i class="fas fa-pen"></i>
                            <span class="tooltiptext"></span>
                        </a>

                        @if(auth()->id() !== $usuario->id)
                        <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('¿Seguro que quieres eliminar a {{ $usuario->nombre_completo }}?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No hay usuarios registrados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

@section('scripts')
<script>
    document.getElementById('busqueda').addEventListener('keyup', function() {
        const query = this.value;

        if (query.length < 2) {
            return;
        }

        fetch(`/usuarios?q=${encodeURIComponent(query)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                document.querySelector('#tbody-js').innerHTML = html;
            });
    });
</script>
@endsection