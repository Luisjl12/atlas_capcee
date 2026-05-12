

<?php $__env->startSection('content'); ?>
    <div class="card-header-custom">
        <a href="<?php echo e(route('panel.supervision')); ?>" class="btn-icon-only">
            <i class="fas fa-arrow-left "></i>
            <h2><i class="fas fa-chart-line"></i> Detalle de Supervisión: CORDE <?php echo e($corde->nombre_corde); ?></h2>
        </a>
    </div>
    <div class="card-body-custom p-4 mt-3">
        <h6>Gráfica de Avance por Plantel </h6>
        <div class="chart-container">
            <canvas id="graficaAvanceCorde"></canvas>
        </div>
    </div>
    <table class="table table-hover data-table">
        <thead class="thead-custom">
            <tr>
                <th>CCT</th>
                <th>Nombre Escuela</th>
                <th>Avance (%)</th>
                <th>Última Actualización</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $planteles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plantel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <!-- Fila principal visible solo en móvil -->
            <tr class="plantel-row d-table-row d-md-none">
                <td colspan="4" class="plantel-cct position-relative" style="cursor: pointer;">
                    <div>
                        <i class="fas fa-school text-primary me-2"></i>
                        <?php echo e($plantel->cct); ?>

                    </div>
                    <div class="toggle-icon position-absolute top-0 end-0 p-2">
                        <i class="fas fa-chevron-down text-muted"></i>
                    </div>
                </td>
            </tr>

            <!-- Fila completa visible solo en escritorio -->
            <tr class="d-none d-md-table-row">
                <td><?php echo e($plantel->cct); ?></td>
                <td><?php echo e($plantel->nombre_escuela); ?></td>
                <td><?php echo e(number_format($plantel->porcentaje_avance_captura, 2)); ?></td>
                <td><?php echo e(\Carbon\Carbon::parse($plantel->fecha_ultima_actualizacion_general)->format('d/m/Y')); ?></td>
            </tr>

            <!-- Detalles expandibles solo en móvil -->
            <tr class="plantel-detalle d-none d-md-none">
                <td colspan="4">
                    <div class="detalle-container d-flex flex-column gap-2">
                        <div><strong>Nombre Escuela:</strong> <?php echo e($plantel->nombre_escuela); ?></div>
                        <div><strong>Avance (%):</strong> <?php echo e(number_format($plantel->porcentaje_avance_captura, 2)); ?></div>
                        <div><strong>Última Actualización:</strong> <?php echo e(\Carbon\Carbon::parse($plantel->fecha_ultima_actualizacion_general)->format('d/m/Y')); ?></div>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="4">Este CORDE no tiene planteles asignados.</td>
            </tr>
            <?php endif; ?>

        </tbody>
    </table>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('graficaAvanceCorde')?.getContext('2d');
        if (ctx) {
            const labels = <?php echo json_encode($labels_grafica, 15, 512) ?>;
            const data = <?php echo json_encode($data_grafica, 15, 512) ?>;
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '% Avance',
                        data: data,
                        backgroundColor: 'rgba(154, 42, 42, 0.7)',
                        borderColor: 'rgba(100, 30, 22, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'CCT del plantel',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            }
                        },

                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: (v) => v + '%'
                            },
                            tittle: {
                                display: true,
                                text: 'Porcentaje de Avance'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: (c) => (c.dataset.label || '') + ': ' + c.parsed.y.toFixed(2) + '%'
                            }
                        }
                    }
                }
            });
        }
    });
</script>

<script src="<?php echo e(asset('js/tabla-expandible-detalle-supervision.js')); ?>"></script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/panel_supervision/detalle.blade.php ENDPATH**/ ?>