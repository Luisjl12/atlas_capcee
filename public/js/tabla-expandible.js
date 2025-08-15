document.addEventListener('DOMContentLoaded', function() {
        const filas = document.querySelectorAll('.fila-usuario');
        filas.forEach(fila => {
            fila.addEventListener('click', function() {
                fila.classList.toggle('activa');
            });
        });

    });