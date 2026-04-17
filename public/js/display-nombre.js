document.addEventListener('DOMContentLoaded', function() {
    const span = document.getElementById('nombre-usuario');
    const partes = span.innerText.trim().split(" ");
    span.innerText = partes[0] || ''; 
});
