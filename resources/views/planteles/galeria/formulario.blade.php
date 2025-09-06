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
    <button type="submit" class="btn btn-success"><i class="fas fa-camera"> Subir</i></button>


</form>
<div id="contadorSeleccionadas" class="mb-2 text-muted">
    Fotos seleccionadas: <span id="cantidadSeleccionadas">0</span>
</div>

<!--Boton de eliminar-->

<button type="button" id="btnEliminarSeleccionadas" class="btn btn-danger"
    data-url="{{ route('galeria.eliminarSeleccionadas') }}">
    <i class="fas fa-trash"></i>
</button>


<!-- Modal actualizado -->
<div id="modalEliminar" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <h3><i class="fas fa-exclamation-triangle"></i> Confirmación</h3>
        <p id="mensajeConfirmacion">¿Estás seguro de eliminar las fotos seleccionadas?</p>
        <div class="modal-actions">
            <button id="cancelarEliminar" class="btn-cancelar">Cancelar</button>
            <button id="confirmarEliminar" class="btn btn-danger">Eliminar</button>
        </div>
    </div>
</div>

<style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .modal-content {
        background: white;
        padding: 30px 40px;
        border-radius: 12px;
        text-align: center;
        max-width: 420px;
        width: 90%;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
    }

    .modal-content h3 {
        margin-bottom: 10px;
        font-size: 1.4rem;
        color: #272525ff;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .modal-content p {
        font-size: 1.1rem;
        margin: 15px 0;
        line-height: 1.5;
    }

    .modal-actions {
        margin-top: 25px;
        display: flex;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }

    .btn-cancelar,


    .btn-cancelar {
        background-color: #6c757d;
        color: white;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    @media (max-width: 480px) {
        .modal-actions {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnEliminar = document.getElementById('btnEliminarSeleccionadas');
        const modal = document.getElementById('modalEliminar');
        const btnConfirmar = document.getElementById('confirmarEliminar');
        const btnCancelar = document.getElementById('cancelarEliminar');
        const contador = document.getElementById('cantidadSeleccionadas');
        const contenedorContador = document.getElementById('contadorSeleccionadas');
        const mensajeConfirmacion = document.getElementById('mensajeConfirmacion');

        // Función para actualizar el contador
        function actualizarContador() {
            const seleccionadas = document.querySelectorAll('.galeria-checkbox:checked').length;
            contador.textContent = seleccionadas;

            contenedorContador.classList.toggle('text-success', seleccionadas > 0);
            contenedorContador.classList.toggle('text-muted', seleccionadas === 0);
        }

        // Escuchar cambios en los checkboxes
        const checkboxes = document.querySelectorAll('.galeria-checkbox');
        checkboxes.forEach(cb => {
            cb.addEventListener('change', actualizarContador);
        });

        // Inicializar contador al cargar
        actualizarContador();

        // Evento para mostrar el modal
        btnEliminar.addEventListener('click', function() {
            const seleccionadas = document.querySelectorAll('.galeria-checkbox:checked');
            const ids = Array.from(seleccionadas).map(cb => cb.value);

            if (ids.length === 0) {
                alert('No hay fotos seleccionadas');
                return;
            }

            // Actualizar mensaje del modal con cantidad
            mensajeConfirmacion.textContent = `¿Estás seguro de eliminar las ${ids.length} foto(s) seleccionadas?`;

            // Mostrar modal
            modal.style.display = 'flex';

            // Cancelar
            btnCancelar.onclick = () => modal.style.display = 'none';

            // Confirmar
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
                            actualizarContador(); // Actualizar después de eliminar
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