@extends('layouts.app')

@section('title', 'Mapa de Planteles')

@section('content')
<div class="mb-3">
    <input type="text" id="buscadorPlantel" class="form-control" placeholder="Buscar por CCT o nombre...">
</div>




<div class="container mt-4">
    <h3 class="mb-3">Mapa de Planteles</h3>
    <div id="controlesMapa" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
        <div>
            <label for="tipoMapa">Tipo de mapa:</label>
            <select id="tipoMapa" class="form-select mb-3" style="max-width: 300px;">
                <option value="macro">Macroregiones</option>
                <option value="micro">Microregiones</option>
            </select>
        </div>
        <div>
            <label for="selectorRegion">Selecciona región:</label>
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
                    <option value="micro:Xicotepec">Xicotepec</option>
                    <option value="micro:Huauchinango">Huauchinango</option>
                    <option value="micro:Cuautempan">Cuautempan</option>
                    <option value="micro:Zacapoaxtla">Zacapoaxtla</option>
                    <option value="micro:Teziutlan">Teziutlan</option>
                    <option value="micro:Tlatlauquitepec">Tlatlauquitepec</option>
                    <option value="micro:Libres">Libres</option>
                    <option value="micro:Ciudad Serdan">Ciudad Serdan</option>
                    <option value="micro:tecamachalco">tecamachalco</option>
                    <option value="micro:Acatzingo">Acatzingo</option>
                    <option value="micro:Tepeaca">Tepeaca</option>
                    <option value="micro:Puebla Capital">Puebla Capital</option>
                    <option value="micro:Amozoc">Amozoc</option>
                    <option value="micro:Cholula">Cholula</option>
                    <option value="micro:Huejotzingo">Huejotzingo</option>
                    <option value="micro:Texmelucan">Texmelucan</option>
                    <option value="micro:Atlixco">Atlixco</option>
                    <option value="micro:Izucar">Izucar</option>
                    <option value="micro:Acatlan">Acatlan</option>
                    <option value="micro:Chiautla">Chiautla</option>
                    <option value="micro:Tepexi">Tepexi</option>
                    <option value="micro:Tehuacan">Tehuacan</option>
                    <option value="micro:Ajalpan">Ajalpan</option>
                </optgroup>
            </select>
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
        Cargando región...
    </div>


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