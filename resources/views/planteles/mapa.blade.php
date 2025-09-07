@extends('layouts.app')

@section('title', 'Mapa de Planteles')

@section('content')
<div class="mb-3">
    <input type="text" id="buscadorPlantel" class="form-control" placeholder="Buscar por CCT o nombre...">
</div>

<div class="container mt-4">
    <h3 class="mb-3">Mapa de Planteles</h3>
    <div id="map" style="height: 500px; border-radius: 8px;"></div>
</div>

{{-- Estilos de Leaflet --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

{{-- Script de Leaflet --}}
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

{{-- Estilos para íconos CSS --}}
<style>
    .custom-marker .marker-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    }

    .marker-icon.activo {
        background-color: #4CAF50;
    }

    .marker-icon.inactivo {
        background-color: #F44336;
    }

    .marker-icon.revision {
        background-color: #FFC107;
    }
</style>

<!--Script para graficar los mapas-->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var map = L.map('map').setView([19.0414, -98.2063], 9);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        var planteles = @json($planteles);
        var markers = [];

        function normalizarEstado(estado) {
            estado = estado.toLowerCase().trim();
            if (estado === 'en_revision') return 'revision';
            return estado;
        }

        function crearIconoPorEstado(estado) {
            const clase = normalizarEstado(estado);
            return L.divIcon({
                className: 'custom-marker',
                iconSize: [30, 30],
                iconAnchor: [15, 30],
                popupAnchor: [0, -30],
                html: `<i class="bi bi-geo-alt-fill marker-icon ${clase}"></i>`
            });
        }

        planteles.forEach(function(plantel) {
            const estado = (plantel.estatus_plantel || 'revision').toLowerCase();
            const icono = crearIconoPorEstado(estado);

            var marker = L.marker([plantel.lat, plantel.lng], {
                    icon: icono
                })
                .addTo(map)
                .bindPopup(
                    `<b>${plantel.nombre}</b><br>` +
                    `CCT: ${plantel.cct}<br>` +
                    `Estado: ${estado.charAt(0).toUpperCase() + estado.slice(1)}`
                );

            markers.push({
                cct: plantel.cct.toLowerCase(),
                nombre: plantel.nombre.toLowerCase(),
                marker: marker
            });
        });

        const buscador = document.getElementById('buscadorPlantel');
        buscador.addEventListener('input', function() {
            const texto = buscador.value.toLowerCase().trim();
            if (texto.length < 3) return;

            const resultado = markers.find(p =>
                p.cct.includes(texto) || p.nombre.includes(texto)
            );

            if (resultado) {
                map.setView(resultado.marker.getLatLng(), 15);
                resultado.marker.openPopup();
            }
        });

        buscador.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                buscador.dispatchEvent(new Event('input'));
            }
        });
    });
</script>
@endsection