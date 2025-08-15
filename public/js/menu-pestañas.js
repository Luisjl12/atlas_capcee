document.addEventListener('DOMContentLoaded', function () {
    const secciones = document.querySelectorAll('.step-section');
    const pestañas = document.querySelectorAll('.nav-tab');

    function mostrarSeccion(numero) {
        secciones.forEach((seccion, i) => {
            seccion.classList.toggle('active', i === numero);
            seccion.classList.toggle('d-none', i !== numero);
        });

        pestañas.forEach((pestana, i) => {
            pestana.classList.toggle('active', i === numero);
        });

        localStorage.setItem('pasoActivo', numero);
    }

    const pasoGuardado = parseInt(localStorage.getItem('pasoActivo')) || 0;
    mostrarSeccion(pasoGuardado);

    pestañas.forEach((pestana, i) => {
        pestana.addEventListener('click', () => {
            mostrarSeccion(i);
        });
    });
});
