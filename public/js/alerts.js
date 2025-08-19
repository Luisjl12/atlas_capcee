document.addEventListener('DOMContentLoaded', function () {
    const alertas = document.querySelectorAll('.alert'); 

    alertas.forEach(alerta => {
        setTimeout(() => {
            alerta.style.transition = 'opacity 0.6s ease';
            alerta.style.opacity = '0';
            setTimeout(() => alerta.remove(), 600); // Eliminar del DOM
        }, 4000);
    });
});