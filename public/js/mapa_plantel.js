document.addEventListener('DOMContentLoaded', function() {
    // Inicializamos el mapa
    var map = L.map('map').setView([plantelData.lat, plantelData.lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    L.marker([plantelData.lat, plantelData.lng])
        .addTo(map)
        .bindPopup(
            "<b>" + plantelData.nombre 
            + "</b><br>CCT: " + plantelData.cct
            + "</b><br>Latitud: "+plantelData.lat
            + "</b><br>Longitud: "+plantelData.lng
        
        );

    // --- Si el mapa está en una pestaña, recalcular tamaño cuando se muestre ---
    const tabMapa = document.querySelector('[data-step="6"]'); // Ajusta según tu tab
    const observer = new MutationObserver(() => {
        if (!tabMapa.classList.contains('d-none')) {
            setTimeout(() => map.invalidateSize(), 300); // Recalcula tamaño
        }
    });

    observer.observe(tabMapa, { attributes: true, attributeFilter: ['class'] });
});
