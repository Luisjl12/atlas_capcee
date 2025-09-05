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

<script>
    var map = L.map('map').setView([19.0414, -98.2063], 9);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var planteles = @json($planteles);


    planteles.forEach(function(plantel) {
        L.marker([plantel.lat, plantel.lng])
            .addTo(map)
            .bindPopup("<b>" + plantel.nombre +
                "</b><br>CCT: " + plantel.cct + "</b>"
            );

    });
</script>
@endsection