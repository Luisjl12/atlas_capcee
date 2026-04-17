function inicializarDesplieguePlanteles() {
    const filas = document.querySelectorAll('.plantel-nombre');

    filas.forEach((fila) => {
        const detalle = fila.nextElementSibling;
        const icono = fila.querySelector('.toggle-icon i');

        fila.addEventListener('click', function () {
            if (detalle) {
                detalle.classList.toggle('d-none');

                if (detalle.classList.contains('d-none')) {
                    icono?.classList.remove('fa-chevron-up');
                    icono?.classList.add('fa-chevron-down');
                } else {
                    icono?.classList.remove('fa-chevron-down');
                    icono?.classList.add('fa-chevron-up');
                }
            }
        });
    });
}
