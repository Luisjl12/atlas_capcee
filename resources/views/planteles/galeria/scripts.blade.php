<script>
    function verDetallesFoto(src, cct, nombreEscuela, nombreUsuario, descripcion, fechaSubida) {
        console.log(" Datos recibidos al hacer clic en la imagen:");
        console.log("URL de la imagen:", src);
        console.log("CCT:", cct);
        console.log("Nombre de la escuela:", nombreEscuela);
        console.log("Usuario que subió la foto:", nombreUsuario);


        document.getElementById('modalFoto').src = src;
        document.getElementById('modalCCT').innerText = cct;
        document.getElementById('modalEscuela').innerText = nombreEscuela;
        document.getElementById('modalUsuario').innerText = nombreUsuario;
        document.getElementById('modalDescripcion').innerText = descripcion;
        document.getElementById('modalFecha').innerText = fechaSubida;
    }
</script>