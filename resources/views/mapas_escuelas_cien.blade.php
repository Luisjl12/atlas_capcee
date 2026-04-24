<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Planteles</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; }
        #mapa_capcee { height: 100vh; width: 100%; }
    </style>
</head>
<body>
    <div id="mapa_capcee"></div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        var map = L.map('mapa_capcee').setView([19.0414, -98.2063], 9);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        var escuelas = @json($escuelas);

        escuelas.forEach(function(escuela) {
            var lat = parseFloat(escuela.latitud);
            var lng = parseFloat(escuela.longitud);

            if (!isNaN(lat) && !isNaN(lng)) {
                L.marker([lat, lng]).addTo(map)
                    .bindPopup(`
                        <div style="font-family: sans-serif;">
                            <h4 style="margin: 0 0 5px 0; color: #1a56db;">${escuela.plantel}</h4>
                            <p><strong>CCT:</strong> ${escuela.cct}</p>
                            <p><strong>Meta:</strong> ${escuela.meta}</p>
                            <hr>
                            <small>Coord: ${lat}, ${lng}</small>
                        </div>
                    `);
            }
        });
    </script>
</body>
</html>