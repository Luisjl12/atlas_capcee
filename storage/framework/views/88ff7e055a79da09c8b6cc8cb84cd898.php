

<?php $__env->startSection('title', 'Reporte: Conteo de Planteles por Municipio'); ?>

<?php $__env->startSection('content'); ?>

        <div class="card-header-custom">
            <a href="<?php echo e(route('reportes.index')); ?>" class="btn-icon-only">
                <i class="fas fa-arrow-left"></i>
                <h2><i class="fas fa-city"></i> Reporte: Conteo de Planteles por Municipio</h2>
            </a>
            <div class="report-actions">
                <a href="<?php echo e(route('reportes.municipios.exportar')); ?>" class="btn-custom btn-success me-2">
                    <i class="fas fa-file-excel"></i> Exportar a CSV
                </a>
                
            </div>
        </div>
        
        
        <div class="buscador-container mb-3">
            <div class="position-relative">
                <i class="fas fa-search position-absolute"
                style="top: 50%; left: 15px; transform: translateY(-50%); color: var(--color-vino-primario);"></i>
                <input type="text" id="buscar" class="form-control ps-5" placeholder="Buscar municipio por nombre">
            </div>
        </div>

        
        <?php if($datos->isEmpty()): ?>
        <p class="text-center mt-4">No hay datos disponibles para este reporte.</p>
        <?php else: ?>
        <?php
        $agrupados = $datos->groupBy('municipio');
        $totalGeneral = 0;
        ?>

        <div class="municipios-grid">
            <?php $__currentLoopData = $agrupados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $municipio => $localidades): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
            $municipioTotal = $localidades->sum('total_planteles');
            $totalGeneral += $municipioTotal;
            ?>

            <div class="municipio-card" data-nombre="<?php echo e(strtolower($municipio)); ?>">
                <div class="card-bar"></div>
                <div class="card-content">
                    <div class="card-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="card-text">
                        <h3><?php echo e($municipio); ?></h3>
                        <p><strong><?php echo e($municipioTotal); ?> Planteles Registrados</strong></p>

                        <?php $__currentLoopData = $localidades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p><strong>Localidad: </strong><?php echo e($loc->localidad); ?></p>
                        <ul class="">
                            <p><strong>Nombre del plantel:</strong></p>
                            <?php $__currentLoopData = explode(', ', $loc->nombre_planteles); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plantel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($plantel); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <div class="municipio-card total-card" data-general="true">
                <div class="card-bar total-bar"></div>
                <div class="card-content">
                    <div class="card-icon">
                        <i class="fas fa-list-ul"></i>
                    </div>
                    <div class="card-text">
                        <h3>TOTAL GENERAL</h3>
                        <p><?php echo e($totalGeneral); ?> Planteles</p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('buscar');
    const cards = document.querySelectorAll('.municipio-card');

    input.addEventListener('input', () => {
        const filtro = input.value.trim().toLowerCase();

        cards.forEach(card => {
            const esGeneral = card.dataset.general === 'true';
            const nombreMunicipio = (card.dataset.nombre || '').toLowerCase();

            if (esGeneral || nombreMunicipio.includes(filtro)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
</script>




<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home1/bcecacef/atlasinfraescolarpueblaa.online/resources/views/reportes/reporte_municipio.blade.php ENDPATH**/ ?>