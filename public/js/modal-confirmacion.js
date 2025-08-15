function mostrarModalConfirmacion(mensaje, url) {
    document.getElementById("mensajeConfirmacion").innerText = mensaje;
    document.getElementById("btnEliminar").onclick = function () {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;

        const token = document.createElement('input');
        token.type = 'hidden';
        token.name = '_token';
        token.value = typeof CSRF_TOKEN !== 'undefined' ? CSRF_TOKEN : '';

        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';

        form.appendChild(token);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    };

    document.getElementById("modalConfirmacion").style.display = "flex";
}

document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("btnCancelar").addEventListener("click", function () {
        document.getElementById("modalConfirmacion").style.display = "none";
    });
});
