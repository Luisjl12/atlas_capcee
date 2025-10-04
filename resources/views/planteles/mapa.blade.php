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

        <!-- Botón flotante con menú de filtros -->
        <div style="position: absolute; top: 20px; right: 20px; z-index: 1000;">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Aplicar filtros
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <h6 class="dropdown-header">Infraestructura</h6>
                    </li>
                    <li><a class="dropdown-item" href="#" id="btn-filtros-superficie">Superficie</a></li>
                    <li><a class="dropdown-item" href="#" id="btn-filtros-obras">Obras recientes</a></li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <h6 class="dropdown-header">Accesibilidad</h6>
                    </li>
                    <li><a class="dropdown-item" href="#" id="btn-filtros-agua">Hidráulica</a></li>
                    <li><a class="dropdown-item" href="#" id="btn-filtros-energia">Energética</a></li>
                    <li><a class="dropdown-item" href="#" id="btn-filtros-drenaje">Drenaje</a></li>
                    <li><a class="dropdown-item" href="#" id="btn-filtros-accesibilidad">General</a></li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <h6 class="dropdown-header">Condiciones</h6>
                    </li>
                    <li><a class="dropdown-item" href="#" id="btn-filtros-estado">Estado de conservación</a></li>
                    <li><a class="dropdown-item" href="#" id="btn-filtros-sanitarios">Sanitarios</a></li>
                    <li><a class="dropdown-item" href="#" id="btn-filtros-seguridad">Seguridad</a></li>
                </ul>

            </div>
        </div>

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

        <!--Leyenda flotante sobre el mapa-->
        <div class="contenedor-leyenda">
            <!-- Leyenda de filtros para superficie -->
            <div id="leyenda-superficie" class="leyenda-superficie" style="display: none;">
                <strong>Filtros activos:</strong>
                <ul style="margin: 0; padding-left: 18px;">
                    <li><b>Superficie:</b> <span class="leyenda-badge" id="leyenda-superficie-texto"></span></li>
                    <li><b>Nivel:</b> <span class="leyenda-badge" id="leyenda-nivel-texto"></span></li>
                    <li><b>Región:</b> <span class="leyenda-badge" id="leyenda-region-texto"></span></li>
                </ul>
            </div>

            <!--Leyenda de filtros para obras nuevas -->
            <div id="leyenda-obras" class="leyenda-obras" style="display: none;">
                <strong>Obras filtradas:</strong>
                <ul style="margin: 0; padding-left: 18px;">
                    <li><b>Nivel:</b> <span class="leyenda-badge" id="leyenda-obras-nivel"></span></li>
                    <li><b>Región:</b> <span class="leyenda-badge" id="leyenda-obras-region"></span></li>
                    <li><b>Intervenciones:</b> <span class="leyenda-badge" id="leyenda-obras-tipo"></span></li>
                </ul>
            </div>


            <!-- Contador de planteles -->
            <div id="contador-planteles" class="contador-planteles" style="display: none;">
                <strong>Planteles encontrados:</strong> <span class="leyenda-badge" id="contador-planteles-numero">0</span>
            </div>
        </div>


        <div id="map" style="height: 500px; border-radius: 8px;"></div>
    </div>
</div>



{{-- Estilos de Leaflet --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

{{-- Script de Leaflet --}}
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>

<script src="https://unpkg.com/@turf/turf@6.5.0/turf.min.js"></script>



<!--Script para graficar los mapas-->
<script src="{{ asset('js/mapa_macroregiones.js') }}"></script>

<!--Script para los filtros-->
<script src="{{ asset('js/filtrosPlanteles.js') }}"></script>

<!--Script para el filtro de agua-->
<script src="{{ asset('js/filtro_agua.js') }}"></script>

<!--script para el filtro de energia-->
<script src="{{ asset('js/filtro_energia.js') }}"></script>

<!--script para el filtro de drenaje-->
<script src="{{ asset('js/filtro_drenaje.js') }}"></script>

<!--script para filtrar por estado-->
<script src="{{ asset('js/filtro_estado.js') }}"></script>

<!--script para filtrar por obras realizadas-->
<script src="{{ asset('js/filtro_obras.js') }}"></script>

<!--scrip para filtrar por seguridad-->
<script src="{{ asset('js/filtro_seguridad.js') }}"></script>

<!--script para filtrar por accesibilidad-->
<script src="{{ asset('js/filtro_accesibilidad.js') }}"></script>

<!--script para filtrar por número y estado de sanitarios-->
<script src="{{ asset('js/filtro_sanitarios.js') }}"></script>




<x-modal-filtros
    id="modal-agua"
    formId="form-agua"
    titulo="Filtro por suministro de agua">
    @include('partials.filtros_agua', [
    'macroregiones' => $macroregiones,
    'microregiones' => $microregiones,
    'municipios' => $municipios,
    'niveles' => $niveles
    ])
</x-modal-filtros>

<x-modal-filtros
    id="modal-energia"
    formId="form-energia"
    titulo="Filtro por suministro de energía">
    @include('partials/filtro_energia', [
    'macroregiones' => $macroregiones,
    'microregiones' => $microregiones,
    'municipios' => $municipios,
    'niveles' => $niveles
    ])
</x-modal-filtros>

<x-modal-filtros
    id="modal-drenaje"
    formId="form-drenaje"
    titulo="Filtro por drenaje">
    @include('partials.filtro_drenaje', [
    'macroregiones' => $macroregiones,
    'microregiones' => $microregiones,
    'municipios' => $municipios,
    'niveles' => $niveles
    ])
</x-modal-filtros>


<x-modal-filtros
    id="modal-instalaciones"
    formId="form-instalaciones"
    titulo="Filtro por estado de instalaciones">

    @include('partials.filtro_estado', [
    'macroregiones' => $macroregiones,
    'microregiones' => $microregiones,
    'municipios' => $municipios,
    'niveles' => $niveles
    ])
</x-modal-filtros>

<x-modal-filtros
    id="modal-obras"
    formId="form-obras"
    titulo="Filtro por obras realizadas en los últimos 5 años">

    @include('partials.filtro_obras', [
    'macroregiones' => $macroregiones,
    'microregiones' => $microregiones,
    'municipios' => $municipios,
    'niveles' => $niveles
    ])
</x-modal-filtros>

<x-modal-filtros
    id="modal-seguridad"
    formId="form-seguridad"
    titulo="Filtro por condiciones de seguridad del inmueble">

    @include('partials.filtro_seguridad', [
    'macroregiones' => $macroregiones,
    'microregiones' => $microregiones,
    'municipios' => $municipios,
    'niveles' => $niveles
    ])
</x-modal-filtros>

<x-modal-filtros
    id="modal-accesibilidad"
    formId="form-accesibilidad"
    titulo="Filtro por accesibilidad el inmueble">
    @include('partials.filtro_accesibilidad', [
    'macroregiones' => $macroregiones,
    'microregiones' => $microregiones,
    'municipios' => $municipios,
    'niveles' => $niveles
    ])
</x-modal-filtros>

<x-modal-filtros
    id="modal-sanitarios"
    formId="form-sanitarios"
    titulo="Filtro por número y estado de sanitarios">
    @include('partials.filtro_sanitarios', [
    'macroregiones' => $macroregiones,
    'microregiones' => $microregiones,
    'municipios' => $municipios,
    'niveles' => $niveles
    ])
</x-modal-filtros>

<x-modal-filtros
    id="modal-superficie"
    formId="form-superficie"
    titulo="Filtro por superficie del inmueble">
    @include('partials.filtro_superficie', [
    'macroregiones' => $macroregiones,
    'microregiones' => $microregiones,
    'municipios' => $municipios,
    'niveles' => $niveles,
    'rangosSuperficie' => $rangosSuperficie
    ])
</x-modal-filtros>

@endsection