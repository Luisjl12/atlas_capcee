<!-- Modal -->
<div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de la Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalFoto" src="" class="img-fluid mb-3">
                <p><strong>CCT:</strong> <span id="modalCCT"></span></p>
                <p><strong>Escuela:</strong> <span id="modalEscuela"></span></p>
                <p><strong>Subido por:</strong> <span id="modalUsuario"></span></p>
                <p><strong>Descripcion:</strong> <span id="modalDescripcion"></span></p>
                <p><strong>Fecha y hora de subida:</strong> <span id="modalFecha"></span></p>
            </div>
        </div>
    </div>
</div>

<script>
    function verDetallesFoto(src, cct, nombreEscuela, nombreUsuario, descripcion, fechaSubida) {

        document.getElementById('modalFoto').src = src;
        document.getElementById('modalCCT').innerText = cct;
        document.getElementById('modalEscuela').innerText = nombreEscuela;
        document.getElementById('modalUsuario').innerText = nombreUsuario;
        document.getElementById('modalDescripcion').innerText = descripcion;
        document.getElementById('modalFecha').innerText = fechaSubida;
    }
</script>