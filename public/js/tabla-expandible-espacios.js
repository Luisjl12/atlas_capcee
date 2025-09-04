
document.addEventListener('DOMContentLoaded', function() {
    const filas = document.querySelectorAll('.espacio-nombre');
    filas.forEach((fila, index) => {
    fila.addEventListener('click', function() {
    const detalle = document.querySelectorAll('.espacio-detalle')[index];
    const icono = fila.querySelector('.toggle-icon i');

    detalle.classList.toggle('d-none');

    if (detalle.classList.contains('d-none')) {
             icono.classList.remove('fa-chevron-up');
             icono.classList.add('fa-chevron-down');
            } else {
                icono.classList.remove('fa-chevron-down');
                icono.classList.add('fa-chevron-up');
            }
        });
    });
});
 