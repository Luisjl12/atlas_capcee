@extends('layouts.app')

@section('title', 'Mapa de Puebla - Proyectos')

@section('content')
<!-- Dependencias del mapa -->
<script src="https://unpkg.com/@turf/turf@6/turf.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="container-fluid mt-4 px-4">
    
    <!-- Encabezado -->
    <a href = "{{ route('proyectos.index') }}" class = "btn-icon-only">
        <i class="fas fa-arrow-left"></i>
        <h5><i class="bi bi-geo-alt-fill"></i> Mapa de Proyectos</h5>
    </a>

    <div class="row">
        <!-- COLUMNA IZQUIERDA: Sidebar de Filtros -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body sidebar-filtros" style="max-height: 75vh; overflow-y: auto;">
                    <h5 class="card-title text-danger mb-3">Regiones</h5>
                    
                    <input type="text" id="buscadorRegion" class="form-control mb-3" placeholder="Buscar región...">

                    <div class="tipo-mapa-container mb-3">
                        <label for="tipoMapa" class="form-label text-muted small fw-bold">Tipo de mapa:</label>
                        <select id="tipoMapa" class="form-select shadow-sm">
                            <option value="macro">Macroregiones</option>
                            <option value="micro">Microregiones</option>
                        </select>
                    </div>
                    
                    <div class="lista-filtros">
                        <h6 class="text-muted border-bottom pb-2 mt-4">Macroregiones</h6>
                        <div class="grupo-macro d-grid gap-2">
                            <button class="filtro-btn btn btn-outline-danger btn-sm text-start" data-tipo="macro" data-nombre="Sierra Norte">Sierra Norte</button>
                            <button class="filtro-btn btn btn-outline-danger btn-sm text-start" data-tipo="macro" data-nombre="Sierra Nororiental">Sierra Nororiental</button>
                            <button class="filtro-btn btn btn-outline-danger btn-sm text-start" data-tipo="macro" data-nombre="Valle Serdán">Valle de Serdán</button>
                            <button class="filtro-btn btn btn-outline-danger btn-sm text-start" data-tipo="macro" data-nombre="Angelópolis">Angelópolis</button>
                            <button class="filtro-btn btn btn-outline-danger btn-sm text-start" data-tipo="macro" data-nombre="Valle de Atlixco y Matamoros">Valle de Atlixco y Matamoros</button>
                            <button class="filtro-btn btn btn-outline-danger btn-sm text-start" data-tipo="macro" data-nombre="Mixteca">Mixteca</button>
                            <button class="filtro-btn btn btn-outline-danger btn-sm text-start" data-tipo="macro" data-nombre="Tehuacán y Sierra Negra">Tehuacán y Sierra Negra</button>
                        </div>
                        
                        <h6 class="text-muted border-bottom pb-2 mt-4">Microregiones</h6>
                        <div class="grupo-micro d-grid gap-2">
                            <button class="filtro-btn btn btn-outline-secondary btn-sm text-start" data-tipo="micro" data-nombre="Xicotepec">Xicotepec</button>
                            <button class="filtro-btn btn btn-outline-secondary btn-sm text-start" data-tipo="micro" data-nombre="Huauchinango">Huauchinango</button>
                            <button class="filtro-btn btn btn-outline-secondary btn-sm text-start" data-tipo="micro" data-nombre="Zacapoaxtla">Zacapoaxtla</button>
                            <button class="filtro-btn btn btn-outline-secondary btn-sm text-start" data-tipo="micro" data-nombre="Teziutlan">Teziutlán</button>
                            <button class="filtro-btn btn btn-outline-secondary btn-sm text-start" data-tipo="micro" data-nombre="Tlatlauquitepec">Tlatlauquitepec</button>
                            <button class="filtro-btn btn btn-outline-secondary btn-sm text-start" data-tipo="micro" data-nombre="Libres">Libres</button>
                            <button class="filtro-btn btn btn-outline-secondary btn-sm text-start" data-tipo="micro" data-nombre="Ciudad Serdan">Ciudad Serdán</button>
                            <button class="filtro-btn btn btn-outline-secondary btn-sm text-start" data-tipo="micro" data-nombre="tecamachalco">Tecamachalco</button>
                            <button class="filtro-btn btn btn-outline-secondary btn-sm text-start" data-tipo="micro" data-nombre="Acatzingo">Acatzingo</button>
                            <button class="filtro-btn btn btn-outline-secondary btn-sm text-start" data-tipo="micro" data-nombre="Tepeaca">Tepeaca</button>
                            <button class="filtro-btn btn btn-outline-secondary btn-sm text-start" data-tipo="micro" data-nombre="Puebla Capital">Puebla</button>
                            <button class="filtro-btn btn btn-outline-secondary btn-sm text-start" data-tipo="micro" data-nombre="Amozoc">Amozoc</button>
                            <button class="filtro-btn btn btn-outline-secondary btn-sm text-start" data-tipo="micro" data-nombre="Cholula">Cholula</button>
                            <button class="filtro-btn btn btn-outline-secondary btn-sm text-start" data-tipo="micro" data-nombre="Huejotzingo">Huejotzingo</button>
                            <button class="filtro-btn btn btn-outline-secondary btn-sm text-start" data-tipo="micro" data-nombre="Texmelucan">San Martín Texmelucan</button>
                            <button class="filtro-btn btn btn-outline-secondary btn-sm text-start" data-tipo="micro" data-nombre="Atlixco">Atlixco</button>
                            <button class="filtro-btn btn btn-outline-secondary btn-sm text-start" data-tipo="micro" data-nombre="Izucar">Izúcar</button>
                            <button class="filtro-btn btn btn-outline-secondary btn-sm text-start" data-tipo="micro" data-nombre="Acatlan">Acatlán</button>
                            <button class="filtro-btn btn btn-outline-secondary btn-sm text-start" data-tipo="micro" data-nombre="Chiautla">Chiautla</button>
                            <button class="filtro-btn btn btn-outline-secondary btn-sm text-start" data-tipo="micro" data-nombre="Tepexi">Tepexi de Rodríguez</button>
                            <button class="filtro-btn btn btn-outline-secondary btn-sm text-start" data-tipo="micro" data-nombre="Tehuacan">Tehuacán</button>
                            <button class="filtro-btn btn btn-outline-secondary btn-sm text-start" data-tipo="micro" data-nombre="Ajalpan">Ajalpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: Mapa y Buscador de Planteles -->
        <div class="col-md-9"> 

            <!-- Loader de carga -->
            <div id="loader" class="text-center mb-3" style="display: none;">
                <div class="spinner-border text-danger" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <div class="text-muted small mt-1">Cargando mapa...</div>
            </div>
            
            <!-- Contenedor del Mapa -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-3"> <!-- Simplificado: p-3 en lugar de p-0 y borramos el container interno -->
                    
                    <h5 class="mb-3">Búsqueda por folio</h5>

                    <div class="input-group mb-3">
                        <input type="text" id="folioInput" class="form-control" placeholder="Escribe un folio...">
                        <button id="btnBuscar" class="btn btn-danger">Buscar</button>
                    </div>

                    <!-- NUEVO CONTENEDOR RELATIVO AL MAPA -->
                    <div style="position: relative;">
                        
                        <!-- Botón flotante y Panel de Filtros -->
                        <div class="filtro-flotante" style="position: absolute; top: 15px; left: 60px; z-index: 1000;">
                            
                            <button id="btnFiltros" class="btn btn-danger" style="border-radius: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                                <i class="bi bi-funnel-fill"></i> Filtros
                            </button>
                            
                            <!-- Panel desplegable con los selectores -->
                            <div id="panelFiltros" class="opciones-filtro" style="display: none; position: absolute; top: 100%; left: 0; margin-top: 5px; background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.2); width: 200px;">
                                
                                <div class="mb-2">
                                    <label class="form-label mb-1" style="font-size: 12px; font-weight: bold; color: #555;">Año del proyecto:</label>
                                    <select id="filtroAnio" class="form-select form-select-sm">
                                        <option value="">Todos los años</option>
                                        <option value="2025">2025</option>
                                        <option value="2026">2026</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label mb-1" style="font-size: 12px; font-weight: bold; color: #555;">Módulo:</label>
                                    <select id="filtroModulo" class="form-select form-select-sm">
                                        <option value="">Todos los módulos</option>
                                        <option value="obra">Obra</option>
                                        <option value="mobiliario">Mobiliario</option>
                                    </select>
                                </div>

                            <button id="btnAplicarFiltros" class="btn w-100" style="background-color: #cc0000 !important; color: white !important; border: none !important; font-weight: bold; padding: 6px;">Aplicar</button>
                            
                            <button id="btnLimpiarFiltros" class="btn btn-sm btn-outline-secondary w-100 mt-2" style="font-size: 12px;">
                                <i class="fas fa-undo"></i> Borrar Filtros
                            </button>
                            </div>
                        </div>

                        <div id="map" style="height: 700px; border:1px solid #ccc; border-radius:8px;"></div>
                        
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="{{ asset('js/mapa_proyectos.js') }}"></script>
<script src="{{ asset('js/display-nombre.js') }}"></script>

@endsection