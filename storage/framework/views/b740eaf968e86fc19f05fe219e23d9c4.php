

<?php $__env->startSection('title', 'Reporte de Estatus de Planteles'); ?>

<?php $__env->startSection('content'); ?>

        <div class="card-header-custom">
            <a href="<?php echo e(route('reportes.index')); ?>" class="btn-icon-only">
                <i class="fas fa-arrow-left"></i>
                <h2><i class="fas fa-check-circle"></i> Reporte de Estatus de Planteles</h2>
            </a>
            <div class="report-actions">
                <a href="<?php echo e(route('reportes.estatus.csv')); ?>" class="btn-custom btn-success me-2">
                    <i class="fas fa-file-excel"></i> Exportar CSV
                </a>
               
            </div>
        </div>

        <?php
        // Agrupamos por estatus y contamos
        $estatusAgrupado = $planteles->groupBy('estatus_plantel')->map->count();

        $totalGeneral = $planteles->count();

        // Preparamos datos para Chart.js
        $labels = $estatusAgrupado->keys();
        $data = $estatusAgrupado->values();
        ?>

        <?php if($totalGeneral === 0): ?>
        <p class="text-center mt-4">No hay datos disponibles para este reporte.</p>
        <?php else: ?>
        <div class="report-grid">
            <div class="reporte-flex">
                
                <div class="estatus-cards">
                    <div class="card-estado activo">
                        <i class="fas fa-check"></i>
                        <h3>ACTIVO</h3>
                        <p class="cantidad"><?php echo e($estatusAgrupado['ACTIVO'] ?? 0); ?></p>
                        <span>Planteles</span>
                    </div>
                    <div class="card-estado inactivo">
                        <i class="fas fa-times"></i>
                        <h3>INACTIVO</h3>
                        <p class="cantidad"><?php echo e($estatusAgrupado['INACTIVO'] ?? 0); ?></p>
                        <span>Planteles</span>
                    </div>
                    <div class="card-estado revision">
                        <i class="fas fa-hourglass-half"></i>
                        <h3>EN REVISIÓN</h3>
                        <p class="cantidad"><?php echo e($estatusAgrupado['EN_REVISION'] ?? 0); ?></p>
                        <span>Planteles</span>
                    </div>

                    <div class="total-text">
                        <strong>Total de planteles: <?php echo e($totalGeneral); ?></strong>
                    </div>
                </div>

                
                <div class="chart-wrapper">
                    <canvas id="graficaEstatus"></canvas>
                </div>
            </div>
        </div>
        <?php endif; ?>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('graficaEstatus')?.getContext('2d');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['ACTIVO', 'INACTIVO', 'EN REVISIÓN'],
                    datasets: [{
                        label: 'Planteles',
                       data: [
                        <?php echo e($estatusAgrupado['ACTIVO'] ?? 0); ?>,
                        <?php echo e($estatusAgrupado['INACTIVO'] ?? 0); ?>,
                        <?php echo e($estatusAgrupado['EN_REVISION'] ?? 0); ?>

                    ],
                        backgroundColor: ['#d1fae5', '#fee2e2', '#ffe8b3'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 15,
                                padding: 15
                            }
                        }
                    }
                }
            });
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home1/bcecacef/atlasinfraescolarpueblaa.online/resources/views/reportes/reporte_estatus.blade.php ENDPATH**/ ?>