function inicializarDesplieguePlanteles() {
    const filas = document.querySelectorAll('.plantel-nombre');

    filas.forEach(fila => {
        fila.addEventListener('pointerdown', function () {
            const detalle = fila.closest('tr').nextElementSibling;
            const icono = fila.querySelector('.toggle-icon i');

            if (!detalle || !detalle.classList.contains('plantel-detalle')) return;

            detalle.classList.toggle('d-none');

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


