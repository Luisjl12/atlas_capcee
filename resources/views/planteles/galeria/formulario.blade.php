<!--Formulario para la seccion de fotos-->
<h4>Galería Fotográfica</h4>
<div id="galeria-alert" class="alert alert-success d-none"></div>


@if (session('foto_subida'))
<div class="alert alert-success">
    Foto subida correctamente
    <img src="{{ session('foto_subida') }}" alt="Foto subida" class="img-thumbnail mt-2" width="200">
</div>
@endif
<form action="{{route ('galeria.store', $plantel->cct)}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="foto" class="form-label">Imagenes (puede elegir varias)</label>
        <input type="file" name="foto" accept="image/png, image/jpeg, image/jpg, image/webp" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="descripcion" class=form-label>Descripción para estas fotos</label>
        <input type="text" name="descripcion" id="descripcion" class="form-control">
    </div>

    {{-- Agrupación en fila --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        {{-- Botón Subir a la izquierda --}}
        <button type="submit" class="btn-custom btn-success">
            <i class="fas fa-camera"></i> Subir
        </button>

        {{-- Checkbox + Botón Eliminar alineados a la derecha --}}
        <div class="d-flex align-items-center gap-3">
            <div class="check-todas">
                <input type="checkbox" id="seleccionarTodas">
                <label class="form-check-label" for="seleccionarTodas">
                    Seleccionar Todas
                </label>
            </div>

            <button type="button" id="btnEliminarSeleccionadas" class="btn-custom btn-danger"
                data-url="{{ route('galeria.eliminarSeleccionadas') }}">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>

</form>

{{-- Contador debajo --}}
<div id="contadorSeleccionadas" class="mb-2 text-muted">
    Fotos seleccionadas: <span id="cantidadSeleccionadas">0</span>
</div>


<!-- Modal actualizado -->
<div id="modalEliminar" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <h5><i class="fas fa-exclamation-triangle"></i> Confirmación</h5>
        <p id="mensajeConfirmacion">
            ¿Estás seguro de eliminar <span id="contadorModal">0</span> foto(s) seleccionada(s)?
        </p>

        <div class="modal-actions">
            <button id="cancelarEliminar" class="btn-custom btn-cancelar">Cancelar</button>
            <button id="confirmarEliminar" class="btn-custom btn-danger">Eliminar</button>
        </div>
    </div>
</div>


<!--Script para seleccionar fotos individualmente o en lote--->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnEliminar = document.getElementById('btnEliminarSeleccionadas');
        const modal = document.getElementById('modalEliminar');
        const btnConfirmar = document.getElementById('confirmarEliminar');
        const btnCancelar = document.getElementById('cancelarEliminar');
        const contador = document.getElementById('cantidadSeleccionadas');
        const contenedorContador = document.getElementById('contadorSeleccionadas');
        const mensajeConfirmacion = document.getElementById('mensajeConfirmacion');
        const checkboxMaestro = document.getElementById('seleccionarTodas');
        const checkboxes = document.querySelectorAll('.galeria-checkbox');

        // Función para actualizar el contador visual
        function actualizarContador() {
            const seleccionadas = document.querySelectorAll('.galeria-checkbox:checked').length;
            if (contador) contador.textContent = seleccionadas;

            if (contenedorContador) {
                contenedorContador.classList.toggle('text-success', seleccionadas > 0);
                contenedorContador.classList.toggle('text-muted', seleccionadas === 0);
            }

            // Sincronizar estado del checkbox maestro
            if (checkboxMaestro) {
                const total = checkboxes.length;
                checkboxMaestro.checked = seleccionadas === total;
                checkboxMaestro.indeterminate = seleccionadas > 0 && seleccionadas < total;
            }

            // Desactivar botón si no hay seleccionadas
            if (btnEliminar) {
                btnEliminar.disabled = seleccionadas === 0;
            }
        }

        // Checkbox maestro: seleccionar/deseleccionar todos
        if (checkboxMaestro) {
            checkboxMaestro.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = checkboxMaestro.checked);
                actualizarContador();
            });
        }

        // Escuchar cambios individuales
        checkboxes.forEach(cb => {
            cb.addEventListener('change', actualizarContador);
        });

        // Inicializar contador al cargar
        actualizarContador();

        // Evento para mostrar el modal
        btnEliminar.addEventListener('click', function() {
            const seleccionadas = document.querySelectorAll('.galeria-checkbox:checked');
            const ids = Array.from(seleccionadas).map(cb => cb.value);
            const cantidad = ids.length;

            if (cantidad === 0) {
                alert('No hay fotos seleccionadas');
                return;
            }

            const contadorModal = document.getElementById('contadorModal');
            if (contadorModal) {
                contadorModal.textContent = cantidad;
            }

            modal.style.display = 'flex';

            btnCancelar.onclick = () => modal.style.display = 'none';

            btnConfirmar.onclick = () => {
                modal.style.display = 'none';
                const url = btnEliminar.getAttribute('data-url');

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            ids
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            ids.forEach(id => {
                                const card = document.getElementById(`foto-${id}`);
                                if (card) card.remove();
                            });
                            actualizarContador();
                        } else {
                            alert(data.message || 'Error al eliminar');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Error de red');
                    });
            };
        });
    });
</script>