@extends('layouts.app')

@section('title', 'Mapa de Planteles')

@section('content')

<div class="card-header-custom">
    <a href="{{ route('dashboard.admin') }}" class="btn-icon-only">
        <i class="fas fa-arrow-left" style="font-size:1.5rem;"></i>
        <h2><i class="bi bi-geo-alt-fill"></i> Mapa de Planteles</h2>
    </a>
</div>

<div style="display: flex; gap: 20px;">
    <div class="sidebar-filtros">
        <h4>Regiones</h4>
        <input type="text" id="buscadorRegion" placeholder="Buscar región...">

        <div class="tipo-mapa-container" style="margin-bottom: 15px;">
            <label for="tipoMapa">Tipo de mapa:</label>
            <select id="tipoMapa" class="form-select">
                <option value="macro">Macroregiones</option>
                <option value="micro">Microregiones</option>
            </select>
        </div>
        <div class="lista-filtros">
            <h6>Macroregiones</h6>
            <div class="grupo-macro">
                <button class="filtro-btn" data-tipo="macro" data-nombre="Sierra Norte">Sierra Norte</button>
                <button class="filtro-btn" data-tipo="macro" data-nombre="Sierra Nororiental">Sierra
                    Nororiental</button>
                <button class="filtro-btn" data-tipo="macro" data-nombre="Valle Serdán">Valle de Serdán</button>
                <button class="filtro-btn" data-tipo="macro" data-nombre="Angelópolis">Angelópolis</button>
                <button class="filtro-btn" data-tipo="macro" data-nombre="Valle de Atlixco y Matamoros">Valle de Atlixco
                    y Matamoros</button>
                <button class="filtro-btn" data-tipo="macro" data-nombre="Mixteca">Mixteca</button>
                <button class="filtro-btn" data-tipo="macro" data-nombre="Tehuacán y Sierra Negra">Tehuacán y Sierra
                    Negra</button>
            </div>
            <hr>
            <h6>Microregiones</h6>
            <div class="grupo-micro">
                <button class="filtro-btn" data-tipo="micro" data-nombre="Xicotepec">Xicotepec</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Huauchinango">Huauchinango</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Zacapoaxtla">Zacapoaxtla</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Teziutlan">Teziutlan</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Tlatlauquitepec">Tlatlauquitepec</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Libres">Libres</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Ciudad Serdan">Ciudad Serdan</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="tecamachalco">Tecamachalco</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Acatzingo">Acatzingo</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Tepeaca">Tepeaca</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Puebla Capital">Puebla Capital</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Amozoc">Amozoc</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Cholula">Cholula</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Huejotzingo">Huejotzingo</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Texmelucan">Texmelucan</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Atlixco">Atlixco</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Izucar">Izucar</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Acatlan">Acatlan</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Chiautla">Chiautla</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Tepexi">Tepexi</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Tehuacan">Tehuacan</button>
                <button class="filtro-btn" data-tipo="micro" data-nombre="Ajalpan">Ajalpan</button>
            </div>
        </div>
    </div>

    <div style="flex:1; position: relative;">
        <div id="loader" style="
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255,255,255,0.9);
            padding: 20px 30px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            z-index: 9999;
        ">
            Cargando mapa...
        </div>

        <div id="map" style="height: 500px; border-radius: 8px;"></div>
    </div>
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