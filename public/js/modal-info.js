
function mostrarModalInformativo(mensaje) {
    document.getElementById("mensajeInformativo").innerText = mensaje;
    document.getElementById("modalInformativo").style.display = "flex";
}

document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("btnCerrarInfo").addEventListener("click", function () {
        document.getElementById("modalInformativo").style.display = "none";
    });
});
