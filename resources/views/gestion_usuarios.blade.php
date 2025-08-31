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

    {{-- Encabezado con botón de regreso y agregar --}}
    <div class="card-header-custom d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('dashboard.admin') }}" class="text-decoration-none d-inline-flex align-items-center text-dark">
            <h4 class="mb-4">
                <i class="fas fa-arrow-left"></i>
                <i class="fas fa-users-cog me-2"></i> Gestión de Usuarios
            </h4>
        </a>
        <a href="{{ route('usuarios.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Agregar Usuario
        </a>
    </div>



    {{-- Buscador --}}
    <div class="position-relative mt-3">
        <i class="fas fa-search position-absolute" style="top: 50%; left: 15px; transform: translateY(-50%); color: var(--color-vino-primario);"></i>
        <input type="text" id="buscar" class="form-control ps-5" placeholder="Buscar usuario por nombre o correo...">
    </div>

    {{-- Contenedor dinámico --}}
    <div id="tabla-usuarios">
        @include('partials.tabla_usuarios', ['usuarios' => $usuarios])
    </div>




</div>

<!--Modal para confirmación-->
<div id="modalConfirmacion" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <h3><i class="fas fa-exclamation-triangle"></i> Confirmación</h3>
        <p id="mensajeConfirmacion">¿Estás seguro de continuar?</p>
        <div class="modal-actions">
            <button id="btnCancelar" class="btn-cancelar">Cancelar</button>
            <a id="btnEliminar" class="btn btn-danger">Eliminar</a>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!--Script para buscador-->
<script>
    const URL_BUSCAR_USUARIOS = "{{ route('usuarios.buscar') }}";
</script>
<script src="{{ asset('js/buscador_usuarios.js') }}"></script>


<!--Script para confirmar eliminacion-->
<script>
    const CSRF_TOKEN = "{{ csrf_token() }}";
</script>
<script src="{{ asset('js/modal-confirmacion.js') }}"></script>

<!--Script para menu expandible-->
<script src="{{ asset('js/tabla-expandible.js') }}"></script>

@endpush