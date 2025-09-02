@extends('layouts.app')

@section('title', 'Listado de Planteles')



@section('content')


<div class="container mt-4">
    @php
    use App\Helpers\RoleHelper;
    @endphp

    <div class="card-header-custom d-flex justify-content-between align-items-center mb-3">
        <a href="{{RoleHelper::gestionPlanteles(session('role_id')) }}" class="text-decoration-none d-inline-flex align-items-center text-dark">
            <h4 class="mb-4">
                <i class="fas fa-arrow-left "></i>
                <i class="fas fa-school"></i> Gestionar Planteles
            </h4>
        </a>
        <a href="{{ route('planteles.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Registrar Nuevo Plantel
        </a>
    </div>
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <strong>¡Éxito!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    @endif

    <form action="{{ route('planteles.filtrar') }}" method="GET">
        <select name="estatus" onchange="this.form.submit()">
            <option value="">-- Filtrar por estatus --</option>
            <option value="ACTIVO" {{ request('estatus') == 'ACTIVO' ? 'selected' : '' }}>Activo</option>
            <option value="INACTIVO" {{ request('estatus') == 'INACTIVO' ? 'selected' : '' }}>Inactivo</option>
            <option value="EN_REVISION" {{ request('estatus') == 'EN_REVISION' ? 'selected' : '' }}>En revisión</option>
        </select>
    </form>


    <div class="position-relative mb-4">
        <i class="fas fa-search position-absolute" style="top: 50%; left: 15px; transform: translateY(-50%); color: var(--color-vino-primario);"></i>
        <input type="text" id="buscar" class="form-control ps-5" placeholder="Buscar por CCT o Nombre...">
    </div>

    <div id="resultados">
        @include('partials.lista', ['planteles' => $planteles])
    </div>
</div>

<!--Mostrar modal para confirmacion de eliminar plantel-->
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

<script>
    const RUTA_BUSCAR_PLANTELES = "{{ route('planteles.buscar') }}";
</script>
<script src="{{ asset('js/buscador_planteles.js') }}"></script>


<script>
    const CSRF_TOKEN = "{{ csrf_token() }}";
</script>
<script src="{{ asset('js/modal-confirmacion.js') }}"></script>

<!--Script para menu expandible-->
<script src="{{ asset('js/tabla-expandible.js') }}"></script>

<!--Limpia los datos de la accion "ver"-->
<script>
    localStorage.removeItem('pasoActivo');
</script>

@endpush