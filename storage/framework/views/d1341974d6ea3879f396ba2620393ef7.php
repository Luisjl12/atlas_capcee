<script>
    function verDetallesFoto(src, cct, nombreEscuela, nombreUsuario, descripcion, fechaSubida) {
        document.getElementById('modalFoto').src = src;
        document.getElementById('modalCCT').innerText = cct;
        document.getElementById('modalEscuela').innerText = nombreEscuela;
        document.getElementById('modalUsuario').innerText = nombreUsuario;
        document.getElementById('modalDescripcion').innerText = descripcion;
        document.getElementById('modalFecha').innerText = fechaSubida;

        const modalBootstrap = new bootstrap.Modal(document.getElementById('fotoModal'));
        modalBootstrap.show();
    }

    function activarPantallaCompleta() {
        const img = document.getElementById('modalFoto');
        if (img.requestFullscreen) {
            img.requestFullscreen();
        } else if (img.webkitRequestFullscreen) {
            img.webkitRequestFullscreen();
        } else if (img.msRequestFullscreen) {
            img.msRequestFullscreen();
        }
    }

    document.addEventListener('fullscreenchange', () => {
        const btn = document.querySelector('[onclick="activarPantallaCompleta()"]');
        btn.style.display = document.fullscreenElement ? 'none' : 'block';
    });
</script>

<script>
    let enPantallaCompleta = false;

    function togglePantallaCompleta() {
        const img = document.getElementById('modalFoto');
        const icon = document.getElementById('iconPantallaCompleta');

        if (!enPantallaCompleta) {
            if (img.requestFullscreen) {
                img.requestFullscreen();
            } else if (img.webkitRequestFullscreen) {
                img.webkitRequestFullscreen();
            } else if (img.msRequestFullscreen) {
                img.msRequestFullscreen();
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
    }

    document.addEventListener('fullscreenchange', () => {
        const icon = document.getElementById('iconPantallaCompleta');
        enPantallaCompleta = !!document.fullscreenElement;

        if (enPantallaCompleta) {
            icon.classList.remove('fa-expand');
            icon.classList.add('fa-compress');
        } else {
            icon.classList.remove('fa-compress');
            icon.classList.add('fa-expand');
        }
    });
</script><?php /**PATH /home1/bcecacef/atlasinfraescolarpueblaa.online/resources/views/planteles/galeria/scripts.blade.php ENDPATH**/ ?>