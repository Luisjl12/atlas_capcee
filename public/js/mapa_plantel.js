document.addEventListener('DOMContentLoaded', function() {
    //  hay coordenadas válidas
    var hasCoords = typeof plantelData.lat === 'number' && typeof plantelData.lng === 'number';

    //usamos una vista general (México)
    var initialLat = hasCoords ? plantelData.lat : 23.6345;
    var initialLng = hasCoords ? plantelData.lng : -102.5528;
    var initialZoom = hasCoords ? 15 : 5;

    // Inicializamos el mapa
    var map = L.map('map').setView([initialLat, initialLng], initialZoom);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Solo agregamos el marcador si hay coordenadas
    if (hasCoords) {
        L.marker([plantelData.lat, plantelData.lng])
            .addTo(map)
            .bindPopup(
                "<b>" + plantelData.nombre 
                + "</b><br>CCT: " + plantelData.cct
                + "</b><br>Latitud: "+plantelData.lat
                + "</b><br>Longitud: "+plantelData.lng
            );
    }

    // --- Si el mapa está en una pestaña, recalcular tamaño cuando se muestre ---
    const tabMapa = document.querySelector('[data-step="6"]'); // Ajusta según tab
    const observer = new MutationObserver(() => {
        if (!tabMapa.classList.contains('d-none')) {
            setTimeout(() => map.invalidateSize(), 300); // Recalcula tamaño
        }
    });

    observer.observe(tabMapa, { attributes: true, attributeFilter: ['class'] });
});
