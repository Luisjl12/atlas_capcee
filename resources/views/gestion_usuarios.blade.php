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

    {{-- Mensaje de éxito --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <strong>¡Éxito!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    @endif

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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!--Script para confirmar eliminacion-->
<script>
    function mostrarModalConfirmacion(mensaje, url) {
        document.getElementById("mensajeConfirmacion").innerText = mensaje;
        document.getElementById("btnEliminar").onclick = function() {
            // Crear un formulario dinámicamente para enviar el método DELETE
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;

            const token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = '{{ csrf_token() }}';

            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';

            form.appendChild(token);
            form.appendChild(method);
            document.body.appendChild(form);
            form.submit();
        };

        document.getElementById("modalConfirmacion").style.display = "flex";
    }

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("btnCancelar").addEventListener("click", function() {
            document.getElementById("modalConfirmacion").style.display = "none";
        });
    });
</script>

<!--Script para menu expandible-->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filas = document.querySelectorAll('.fila-usuario');
        filas.forEach(fila => {
            fila.addEventListener('click', function() {
                fila.classList.toggle('activa');
            });
        });

    });
</script>
@endpush