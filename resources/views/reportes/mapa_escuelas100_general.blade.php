@extends('layouts.app')

@section('title', 'Mapa Global - Escuelas al 100')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 style="color: #691C32; font-weight: bold;">
            <i class="fas fa-globe-americas"></i> Mapa: Programa Escuelas al 100
        </h2>
        <a href="{{ url('/admin') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Volver al Dashboard
        </a>
    </div>

    <div class="card shadow-lg border-0">
        <div class="card-body p-0">
            <div id="map" style="height: 80vh; width: 100%; border-radius: 8px; z-index: 1; border: 2px solid #691C32;"></div>
        </div>
    </div>
</div>

<style>
    /* Forzar el fondo del contenedor del mapa a blanco puro */
    #map {
        background-color: #ffffff !important;
        border: 3px solid #691C32; /* Borde guinda fuerte al contenedor */
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    /* Opcional: Quitar el borde si el mapa está dentro de una tarjeta (card) */
    .leaflet-container {
        background: #ffffff !important;
        border: none !important; 
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const planteles = @json($planteles);
    const regiones = { /* ... tu objeto de regiones ... */ };

    // 1. Inicializar mapa en blanco
    window.map = L.map('map', {
        center: [19.0414, -98.2063],
        zoom: 7,           // Zoom inicial
        minZoom: 7,        // No permite alejarse más allá de ver el estado completo
        maxZoom: 18,       // Permite acercarse hasta ver calles, pero no píxeles borrosos
        zoomControl: true,
        attributionControl: false
    }).setView([19.0414, -98.2063], 8);

    // 2. Cargar Municipios (La rejilla interna)
    // 2. Cargar Municipios (División interna reforzada)
fetch("{{ asset('geojson/municipios_limpios_dos.json') }}")
    .then(res => res.json())
    .then(municipiosData => {
        L.geoJSON(municipiosData, {
            style: function(feature) {
                return {
                    color: '#db0a0a',      // Gris medio (antes era muy claro)
                    weight: 1.2,           // Grosor aumentado para que sea visible
                    opacity: 0.6,          // Más opaco
                    fillColor: '#FFFFFF',  // Fondo blanco puro
                    fillOpacity: 1,
                    clickable: false
                };
            }
        }).addTo(map);

        // 3. Cargar el Contorno del Estado (Borde "Maestro")
        return fetch("{{ asset('geojson/puebla_limpio_dos.json') }}");
    })
    .then(res => res.json())
    .then(estadoData => {
        const contornoEstado = L.geoJSON(estadoData, {
            style: {
                color: '#691C32',      // Guinda Institucional CAPCEE
                weight: 5,             // Grosor fuerte (línea maestra)
                opacity: 1,
                fillOpacity: 0,
                clickable: false
            }
        }).addTo(map);

        // TRUCO VISUAL: Añadir un "Glow" al contorno para que no se pierda nada
        contornoEstado.getElement().style.filter = "drop-shadow(0px 0px 3px rgba(105, 28, 50, 0.5))";
        
        map.fitBounds(contornoEstado.getBounds(), { padding: [20, 20] });
    });

    // 4. Grupo de Marcadores (Tus pines guindas)
    const markersGroup = L.featureGroup();
    const iconoVino = L.divIcon({
        className: 'custom-pin',
        html: '<i class="fas fa-map-marker-alt" style="color: #691C32; font-size: 30px; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);"></i>',
        iconSize: [30, 42],
        iconAnchor: [15, 42],
        popupAnchor: [0, -35]
    });

    planteles.forEach(p => {
        if (p.latitud && p.longitud) {
            let monto = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(p.monto);
            const marker = L.marker([parseFloat(p.latitud), parseFloat(p.longitud)], { icon: iconoVino })
                .bindPopup(`
                    <div style='text-align: center;'>
                        <b style='color: #691C32;'>${p.plantel}</b><br>
                        <small>${p.municipio}</small><hr style='margin:5px'>
                        <span style='color: green;'>Inv: ${monto}</span>
                    </div>
                `);
            markersGroup.addLayer(marker);
        }
    });

    markersGroup.addTo(map);
});
</script>
@endsection