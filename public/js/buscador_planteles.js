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
