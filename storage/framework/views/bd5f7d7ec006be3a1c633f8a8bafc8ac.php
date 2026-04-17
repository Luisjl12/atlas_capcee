

<?php $__env->startSection('title', 'Buscador Avanzado'); ?>

<?php $__env->startSection('content'); ?>

    <?php
    use App\Helpers\RoleHelper;
    ?>

    <div class="card-header-custom">
        <a href="<?php echo e(RoleHelper::dashboardRoute(session('role_id'))); ?>"
            class="btn-icon-only">
            <i class="fas fa-arrow-left"></i>
            <h2><i class="fas fa-search me-3"></i>Buscador Avanzado de Planteles</h2>
        </a>
    </div>

        <div class="data-table-container">

            <!-- Botón para abrir modal -->
            <p id="toggleFiltros" class="p-toggle-filtro">
                <i class="fas fa-filter"></i> Clic para una búsqueda avanzada
            </p>

            <!-- Modal de filtros -->
            <div id="modalFiltros" class="modal-filtros" style="display: none;">
                <div class="modal-content-avanzado">
                    <div class="modal-header">
                        <button class="close-modal" id="closeModal">&times;</button>
                    </div>

                    <form method="GET" action="<?php echo e(route('busqueda.avanzada')); ?>" class="filters-form">

                        
                        <div class="filter-section">
                            <label for="busqueda">Buscar</label>
                            <input type="text" id="busqueda" name="busqueda"
                                placeholder="Buscar por CCT o nombre"
                                value="<?php echo e(request('busqueda')); ?>">
                        </div>

                        <hr>

                        
                        <div class="filter-section">
                            <label for="id_municipio">Municipio</label>
                            <select id="id_municipio" name="id_municipio">
                                <option value="">Todos</option>
                                <?php $__currentLoopData = $municipios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $municipio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($municipio->id); ?>"
                                    <?php echo e(request('id_municipio') == $municipio->id ? 'selected' : ''); ?>>
                                    <?php echo e($municipio->nombre_municipio); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        
                        <div class="filter-section">
                            <label>Nivel Educativo</label>
                            <div class="chip-group">
                                <label>
                                    <input type="radio" name="nivel_educativo" value=""
                                        <?php echo e(request('nivel_educativo') == '' ? 'checked' : ''); ?>>
                                    <span>Todos</span>
                                </label>
                                <?php $__currentLoopData = $nivelesEducativos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nivel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                                    <?php if(!empty($nivel)): ?>
                                <label>
                                    <input type="radio" name="nivel_educativo" value="<?php echo e($nivel); ?>"
                                    <?php echo e(request('nivel_educativo') == $nivel ? 'checked' : ''); ?>>
                                    <span><?php echo e($nivel); ?></span>
                                </label>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <hr>

                        
                        <div class="filter-section">
                            <label>Sostenimiento</label>
                            <div class="chip-group">
                                <label>
                                    <input type="radio" name="sostenimiento" value=""
                                        <?php echo e(request('sostenimiento') == '' ? 'checked' : ''); ?>>
                                    <span>Todos</span>
                                </label>
                                <label>
                                    <input type="radio" name="sostenimiento" value="Federal"
                                        <?php echo e(request('sostenimiento') == 'Federal' ? 'checked' : ''); ?>>
                                    <span>Federal</span>
                                </label>
                                <label>
                                    <input type="radio" name="sostenimiento" value="Estatal"
                                        <?php echo e(request('sostenimiento') == 'Estatal' ? 'checked' : ''); ?>>
                                    <span>Estatal</span>
                                </label>
                                <label>
                                    <input type="radio" name="sostenimiento" value="Particular"
                                        <?php echo e(request('sostenimiento') == 'Particular' ? 'checked' : ''); ?>>
                                    <span>Particular</span>
                                </label>
                                <label>
                                    <input type="radio" name="sostenimiento" value="Municipal"
                                        <?php echo e(request('sostenimiento') == 'Municipal' ? 'checked' : ''); ?>>
                                    <span>Municipal</span>
                                </label>
                            </div>
                        </div>

                        <hr>

                        
                        <div class="filter-section">
                            <label>Alarma Sísmica</label>
                            <div class="chip-group">
                                <label>
                                    <input type="radio" name="alarma_sismica" value=""
                                        <?php echo e(request('alarma_sismica') == '' ? 'checked' : ''); ?>>
                                    <span>Indiferente</span>
                                </label>
                                <label>
                                    <input type="radio" name="alarma_sismica" value="1"
                                        <?php echo e(request('alarma_sismica') == '1' ? 'checked' : ''); ?>>
                                    <span>Sí</span>
                                </label>
                                <label>
                                    <input type="radio" name="alarma_sismica" value="0"
                                        <?php echo e(request('alarma_sismica') == '0' ? 'checked' : ''); ?>>
                                    <span>No</span>
                                </label>
                            </div>
                        </div>

                        
                        <div class="modal-footer">
                            <button type="submit" class="btn-custom show">Mostrar Planteles</button>
                            <a href="<?php echo e(route('busqueda.avanzada')); ?>" class="btn-limpiador">
                                <i class="fas fa-eraser"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            
            <p class="mt-3"><strong><?php echo e($planteles->total()); ?></strong> planteles encontrados.</p>

            
            <?php echo $__env->make('partials.lista_avanzada', ['planteles' => $planteles], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        
    
    <?php if(method_exists($planteles, 'links')): ?>
    <nav class="pagination-container" aria-label="Navegación de páginas">
        <ul class="pagination">
            <li class="page-item ">
                <?php echo e($planteles->links('vendor.pagination.mi_paginacion')); ?>

            </li>
        </ul>
    </nav>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.getElementById("toggleFiltros").addEventListener("click", () => {
        document.getElementById("modalFiltros").style.display = "flex";
    });
    document.getElementById("closeModal").addEventListener("click", () => {
        document.getElementById("modalFiltros").style.display = "none";
    });
    window.addEventListener("click", (e) => {
        if (e.target.id === "modalFiltros") {
            document.getElementById("modalFiltros").style.display = "none";
        }
    });
</script>
<script src="<?php echo e(asset('js/tabla-expandible-avanzado.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home1/bcecacef/atlasinfraescolarpueblaa.online/resources/views/busqueda/avanzada.blade.php ENDPATH**/ ?>