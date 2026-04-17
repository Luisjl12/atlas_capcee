@extends('layouts.app')

@section('title', 'Listado de Planteles')



@section('content')

    @php
    use App\Helpers\RoleHelper;
    @endphp

    <div class="card-header-custom">
        <a href="{{ RoleHelper::gestionPlanteles(session('role_id')) }}" class="btn-icon-only">
            <i class="fas fa-arrow-left"></i>
            <h2><i class="fas fa-school"></i> Gestionar Planteles</h2>
        </a>

        <a href="{{ route('planteles.create') }}" class="btn-custom btn-success">
            <i class="fas fa-plus"></i> Registrar Nuevo Plantel
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    <div class="buscador-container d-flex align-items-center gap-3 mb-4">
        {{-- Barra de búsqueda --}}
        <div class="buscador-input-wrapper">
            <i class="fas fa-search"></i>
            <input type="text" id="buscar" class="form-control" placeholder="Buscar por CCT o Nombre...">
        </div>

        {{-- Filtro de estatus --}}
        <form action="{{ route('planteles.filtrar') }}" method="GET">
            <select name="estatus" class="form-select" style="background-color: #f8f9fa;" onchange="this.form.submit()">
                <option value="">Todos</option>
                <option value="ACTIVO" {{ request('estatus') == 'ACTIVO' ? 'selected' : '' }}>Activo</option>
                <option value="INACTIVO" {{ request('estatus') == 'INACTIVO' ? 'selected' : '' }}>Inactivo</option>
                <option value="EN_REVISION" {{ request('estatus') == 'EN_REVISION' ? 'selected' : '' }}>En revisión</option>
            </select>
        </form>

        {{-- Borrar filtro --}}
        <button type="button" class="btn-limpiador" onclick="limpiarBusqueda()">
            <i class="fas fa-eraser"></i>
        </button>
    </div>


    <div id="resultados">
        @include('partials.lista', ['planteles' => $planteles])
    </div>

<!--Mostrar modal para confirmacion de eliminar plantel-->
<div id="modalConfirmacion" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <h5><i class="fas fa-exclamation-triangle"></i> Confirmación</h5>
        <p id="mensajeConfirmacion">¿Estás seguro de continuar?</p>
        <div class="modal-actions">
            <button id="btnCancelar" class="btn-custom btn-cancelar">Cancelar</button>
            <a id="btnEliminar" class="btn-custom btn-danger">Eliminar</a>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    const RUTA_BUSCAR_PLANTELES = "{{ route('planteles.buscar') }}";
</script>


<script>
    const CSRF_TOKEN = "{{ csrf_token() }}";
</script>
<script src="{{ asset('js/modal-confirmacion.js') }}"></script>



<!--Limpia los datos de la accion "ver"-->
<script>
    localStorage.removeItem('pasoActivo');
</script>

<script>
    function limpiarBusqueda() {
        // Limpiar el campo de búsqueda si existe
        const inputBusqueda = document.getElementById('buscar');
        if (inputBusqueda) {
            inputBusqueda.value = '';
        }

        // Limpiar el filtro de estatus si existe
        const selectEstatus = document.querySelector('select[name="estatus"]');
        if (selectEstatus) {
            selectEstatus.value = '';
        }

        // Enviar el formulario de filtro (si existe)
        const formulario = selectEstatus?.form;
        if (formulario) {
            formulario.submit();
        }
    }
</script>

<!--Tabla expandible para versiones moviles--->
<script>
function inicializarDesplieguePlanteles() {
    console.log('[DEBUG] Iniciando despliegue de planteles...');

    const tbody = document.querySelector('#tbody-js');
    if (!tbody) {
        console.warn('[DEBUG] No se encontró el tbody con id "tbody-js".');
        return;
    }

    tbody.addEventListener('click', function (e) {
        const filaNombre = e.target.closest('.plantel-nombre');
        if (!filaNombre) return;

        const filaActual = filaNombre.closest('tr');
        let filaDetalle = filaActual;

        // Buscar hacia adelante hasta encontrar la fila .plantel-detalle
        do {
            filaDetalle = filaDetalle.nextElementSibling;
        } while (filaDetalle && !filaDetalle.classList.contains('plantel-detalle'));

        if (filaDetalle) {
            filaDetalle.classList.toggle('d-none');
            console.log('[DEBUG] Toggle aplicado a fila de detalle:', filaDetalle);

            const icono = filaNombre.querySelector('.toggle-icon i');
            if (icono) {
                icono.classList.toggle('fa-chevron-down');
                icono.classList.toggle('fa-chevron-up');
                console.log('[DEBUG] Ícono actualizado.');
            } else {
                console.warn('[DEBUG] No se encontró el ícono dentro de .toggle-icon.');
            }
        } else {
            console.warn('[DEBUG] No se encontró la fila de detalle esperada.');
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    console.log('[DEBUG] DOM completamente cargado.');
    inicializarDesplieguePlantelesDebug();
});

</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    const inputBuscar = document.getElementById('buscar');
    const contenedorResultados = document.getElementById('resultados');

    inicializarDesplieguePlanteles();

    if (inputBuscar && contenedorResultados && typeof RUTA_BUSCAR_PLANTELES !== 'undefined') {
        inputBuscar.addEventListener('keyup', function () {
            const buscar = this.value;

            fetch(`${RUTA_BUSCAR_PLANTELES}?buscar=${encodeURIComponent(buscar)}`)
                .then(response => response.json())
                .then(data => {
                    contenedorResultados.innerHTML = data.html;
                    inicializarDesplieguePlanteles(); 
                })
                .catch(error => console.error('Error en la búsqueda:', error));
        });
    }
});
</script>

@endpush