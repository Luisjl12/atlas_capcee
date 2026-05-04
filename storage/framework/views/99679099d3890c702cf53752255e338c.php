

<?php $__env->startSection('title', 'Mapa de Puebla - Proyectos'); ?>

<?php $__env->startSection('content'); ?>
<!-- Dependencias del mapa -->
<script src="https://unpkg.com/@turf/turf@6/turf.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="container-fluid mt-4 px-4"> <!-- Cambiado a container-fluid para dar más espacio a la pantalla dividida -->
    
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>
            <i class="fas fa-map-marked-alt me-2"></i> Mapa de proyectos
        </h5>
        <a href="<?php echo e(url('/admin')); ?>" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

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
            <!-- Buscador de planteles independiente (opcional, si lo sigues ocupando) -->
            <div class="input-group shadow-sm mb-3">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" id="buscadorProyecto" class="form-control border-start-0" placeholder="Buscar proyecto por Folio PPI...">
            </div>

            <!-- Loader de carga -->
            <div id="loader" class="text-center mb-3" style="display: none;">
                <div class="spinner-border text-danger" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <div class="text-muted small mt-1">Renderizando mapa...</div>
            </div>

            <!-- Contenedor del Mapa -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div id="map" style="height: 70vh; width: 100%; border-radius: 8px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo e(asset('js/mapa_proyectos.js')); ?>"></script>
<script src="<?php echo e(asset('js/display-nombre.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/mapaProyectos.blade.php ENDPATH**/ ?>