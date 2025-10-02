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
                    <li><a class="dropdown-item" href="#" id="btn-filtros">Filtrar por superficie</a></li>
                    <li><a class="dropdown-item" href="#" id="btn-filtros-agua">Filtrar por agua</a></li>
                    <li><a class="dropdown-item" href="#" id="btn-filtros-energia">Filtrar por energía</a></li>
                    <li><a class="dropdown-item" href="#" id="btn-filtros-drenaje">Filtrar por drenaje</a></li>
                    <li><a class="dropdown-item" href="#" id="btn-filtros-estado">Filtrar por conservación</a></li>
                    <li><a class="dropdown-item" href="#" id="btn-filtros-obras">Filtrar por obras realizadas</a></li>
                    <li><a class="dropdown-item" href="#" id="btn-filtros-seguridad">Filtrar por seguridad</a></li>
                </ul>
            </div>
        </div>
        <!---->
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


<!-- Modal de filtros -->
<div id="modal-filtros" class="modal">
    <div class="modal-content">
        <span id="cerrar-modal" class="close">&times;</span>
        <h3>Filtro por superficie</h3>

        <form id="form-filtros">
            <div class="filtro-bloque">
                <label for="filtro-macroregion">¿En qué macroregión desea filtrar?</label>
                <select id="filtro-macroregion" name="macroregion">
                    <option value="">-- Selecciona --</option>
                    @foreach($macroregiones as $macro)
                    <option value="{{ $macro->id }}">{{ $macro->nombre_macroregion }}</option>
                    @endforeach
                </select>

                <label for="filtro-microregion">¿Qué microregión le interesa?</label>
                <select id="filtro-microregion" name="microregion">
                    <option value="">-- Selecciona --</option>
                    @foreach($microregiones as $micro)
                    <option value="{{ $micro->id }}">{{ $micro->nombre_microregiones }}</option>
                    @endforeach
                </select>

                <label for="filtro-municipio">¿En qué municipio desea aplicar el filtro?</label>
                <select id="filtro-municipio" name="municipio">
                    <option value="">-- Selecciona --</option>
                    @foreach($municipios as $muni)
                    <option value="{{ $muni->id }}">{{ $muni->nombre_municipio }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filtro-bloque">
                <label for="filtro-nivel">¿Qué nivel educativo desea consultar?</label>
                <select id="filtro-nivel" name="nivel">
                    <option value="">-- Selecciona --</option>
                    @foreach($niveles as $nivel)
                    <option value="{{ $nivel->nivel }}">{{ ucfirst($nivel->nivel) }}</option>
                    @endforeach
                </select>

                <label for="filtro-superficie">¿Qué rango de superficie desea filtrar?</label>
                <select id="filtro-superficie" name="superficie">
                    <option value="">-- Selecciona --</option>
                    @foreach($rangosSuperficie as $rango)
                    <option value="{{ $rango->rango }}">{{ ucwords(str_replace('_', ' ', $rango->rango)) }} m²</option>
                    @endforeach
                </select>
            </div>

            <button type="submit">Aplicar filtros</button>
        </form>
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


@endsection