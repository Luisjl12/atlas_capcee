//Script para mostrar los filtros del buscador avanzado 

 document.getElementById("toggleFiltros").addEventListener("click", function() {
        const panel = document.getElementById("panelFiltros");
        panel.style.display = (panel.style.display === "none" || panel.style.display === "") ? "block" : "none";
    });