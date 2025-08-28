<div class="row mt-4">
    @forelse ($fotos as $foto)
    <x-foto-card :foto="$foto" />
    @empty
    <div class="col-12">
        <p class="text-muted text-center">No hay fotos subidas para este plantel.</p>
    </div>
    @endforelse
</div>


{{-- Modal para mostrar detalles de la foto --}}
<div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-contenido">

            {{-- Encabezado con botones --}}
            {{-- Botón pantalla completa a la izquierda --}}
            <button id="btnPantallaCompleta" class="btn btn-outline-light" onclick="togglePantallaCompleta()" title="Pantalla completa">
                <i id="iconPantallaCompleta" class="fas fa-expand"></i>
            </button>

            {{-- Botón cerrar a la derecha --}}
            {{-- Botón cerrar personalizado --}}
            <button class="cerrar-modal" onclick="cerrarModal()">✕</button>

            {{-- Cuerpo del modal --}}
            <div class="modal-body text-center">
                <img id="modalFoto" src="" class="img-fluid rounded shadow" style="max-height:80vh; object-fit:contain;">
                <hr class="bg-secondary">
                <p><strong>CCT:</strong> <span id="modalCCT"></span></p>
                <p><strong>Plantel:</strong> <span id="modalEscuela"></span></p>
                <p><strong>Subido por el usuario:</strong> <span id="modalUsuario"></span></p>
                <p><strong>Descripción:</strong> <span id="modalDescripcion"></span></p>
                <p><strong>Fecha de subida:</strong> <span id="modalFecha"></span></p>
            </div>

        </div>
    </div>
</div>