@extends('layouts.app')

@section('title', 'Mapa de Planteles')

@section('content')
<div class="mb-3">
    <input type="text" id="buscadorPlantel" class="form-control" placeholder="Buscar por CCT o nombre...">
</div>



<div class="container mt-4">
    <h3 class="mb-3">Mapa de Planteles</h3>
    <select id="selectorRegion" class="form-select mb-3" style="max-width: 300px;">
        <option value="">Selecciona una región...</option>
        <optgroup label="Macroregiones">
            <option value="macro:Sierra Norte">Sierra Norte</option>
            <option value="macro:Sierra Nororiental">Sierra Nororiental</option>
            <option value="macro:Valle Serdán">Valle de Serdán</option>
            <option value="macro:Angelópolis">Angelópolis</option>
            <option value="macro:Valle de Atlixco y Matamoros">Valle de Atlixco y Matamoros</option>
            <option value="macro:Mixteca">Mixteca</option>
            <option value="macro:Tehuacán y Sierra Negra">Tehuacán y Sierra Negra</option>
        </optgroup>
        <optgroup label="Microregiones">
            <option value="micro:Micro Mixteca Sur">Xicotepec</option>
            <option value="micro:Micro Tehuacán Este">Micro Tehuacán Este</option>
            <!-- Agrega más microregiones -->
        </optgroup>
    </select>
    <select id="tipoMapa" class="form-select mb-3" style="max-width: 300px;">
        <option value="macro">Macroregiones</option>
        <option value="micro">Microregiones</option>
    </select>
    <div id="map" style="height: 500px; border-radius: 8px;"></div>

</div>



{{-- Estilos de Leaflet --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

{{-- Script de Leaflet --}}
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>




<script>
    window.planteles = @json($planteles);
</script>

<!--Script para graficar los mapas-->
<script src="{{ asset('js/mapa_macroregiones.js') }}"></script>



@endsection