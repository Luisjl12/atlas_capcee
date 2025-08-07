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
@includeIf('usuarios.partials.modal_eliminar')

<div class="container mt-4">
    <div class="card-header-custom d-flex justify-content-between align-items-center mb-3">
        <a href="{{route('dashboard.admin')}}" class="text-decoration-none d-inline-flex align-items-center text-dark">
            <h4 class="mb-4">
                <i class="fas fa-arrow-left "></i>
                <i class="fas fa-users-cog me-2"></i> Gestión de Usuarios
            </h4>
        </a>
        <a href="{{ route('usuarios.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Agregar Usuario
        </a>
    </div>

    {{-- Buscador con diseño --}}
    <div class="search-container mb-4">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="buscar" class="form-control" placeholder="    Buscar usuario por nombre o correo...">
    </div>


    {{-- Contenedor que será reemplazado vía AJAX --}}
    <div id="tabla-usuarios">
        @include('partials.tabla_usuarios', ['usuarios' => $usuarios])
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#buscar').on('input', function() {
        let buscar = $(this).val();

        $.ajax({
            url: "{{ route('usuarios.buscar') }}",
            method: 'GET',
            data: {
                buscar: buscar
            },
            success: function(response) {
                $('#tabla-usuarios').html(response.html);
            }
        });
    });
</script>
@endpush