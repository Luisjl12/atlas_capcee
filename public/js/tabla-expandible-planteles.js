function inicializarDesplieguePlantelesDebug() {
    console.log('[DEBUG] Iniciando despliegue de planteles...');

    const tbody = document.querySelector('#tbody-js');
    if (!tbody) {
        console.warn('[DEBUG] No se encontró el tbody con id "tbody-js".');
        return;
    }

    tbody.addEventListener('click', function (e) {
        const filaNombre = e.target.closest('.plantel-nombre');
        if (!filaNombre) return;

        const filaActual = filaNombre.closest('tr');
        let filaDetalle = filaActual;

        // Buscar hacia adelante hasta encontrar la fila .plantel-detalle
        do {
            filaDetalle = filaDetalle.nextElementSibling;
        } while (filaDetalle && !filaDetalle.classList.contains('plantel-detalle'));

        if (filaDetalle) {
            filaDetalle.classList.toggle('d-none');
            console.log('[DEBUG] Toggle aplicado a fila de detalle:', filaDetalle);

            const icono = filaNombre.querySelector('.toggle-icon i');
            if (icono) {
                icono.classList.toggle('fa-chevron-down');
                icono.classList.toggle('fa-chevron-up');
                console.log('[DEBUG] Ícono actualizado.');
            } else {
                console.warn('[DEBUG] No se encontró el ícono dentro de .toggle-icon.');
            }
        } else {
            console.warn('[DEBUG] No se encontró la fila de detalle esperada.');
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    console.log('[DEBUG] DOM completamente cargado.');
    inicializarDesplieguePlantelesDebug();
});



