function inicializarDesplieguePlanteles() {
    const filas = document.querySelectorAll('.plantel-nombre');

    filas.forEach(fila => {
        fila.addEventListener('pointerdown', function () {
            const filaActual = fila.closest('tr');
            let siguiente = filaActual.nextElementSibling;

            // Saltar la fila intermedia (escritorio)
            if (siguiente && !siguiente.classList.contains('plantel-detalle')) {
                siguiente = siguiente.nextElementSibling;
            }

            const detalle = siguiente;
            const icono = fila.querySelector('.toggle-icon i');

            if (!detalle || !detalle.classList.contains('plantel-detalle')) return;

            // Mostrar u ocultar detalle
            detalle.classList.toggle('d-none');

            // Cambiar ícono
            if (detalle.classList.contains('d-none')) {
                icono?.classList.remove('fa-chevron-up');
                icono?.classList.add('fa-chevron-down');
            } else {
                icono?.classList.remove('fa-chevron-down');
                icono?.classList.add('fa-chevron-up');
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', inicializarDesplieguePlanteles);
