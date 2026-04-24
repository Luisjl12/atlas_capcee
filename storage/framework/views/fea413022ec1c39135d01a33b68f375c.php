

<?php $__env->startSection('title', 'Reporte Escuelas al 100'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark"><i class="fas fa-certificate text-primary"></i> Listado: Escuelas al 100</h2>
        <div>
            <a href="<?php echo e(route('reportes.index')); ?>" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <button class="btn btn-success btn-sm"><i class="fas fa-file-excel"></i> Exportar a Excel</button>
        </div>
    </div>

    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body bg-light">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="municipio" class="form-control" placeholder="Buscar por Municipio..." value="<?php echo e(request('municipio')); ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background-color: #005088; color: white;">
                    <tr>
                        <th>Microrregión</th>
                        <th>Municipio</th>
                        <th>Localidad</th>
                        <th>Plantel</th>
                        <th>CCT</th>
                        <th>Meta</th>
                        <th>Monto Contratado</th>
                        <th class="text-center">Avance Físico</th>
                        <th class="text-center">Mapa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $datos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $escuela): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="fw-bold"><?php echo e($escuela->microrregion ?? 'N/A'); ?></td>
                        <td><?php echo e($escuela->municipio ?? 'N/A'); ?></td>
                        <td><?php echo e($escuela->localidad ?? 'N/A'); ?></td>
                        <td><?php echo e($escuela->plantel ?? 'N/A'); ?></td>
                        <td><code class="text-dark"><?php echo e($escuela->cct ?? 'N/A'); ?></code></td>
                        
                        <td><small><?php echo e($escuela->meta ?? 'Sin definir'); ?></small></td>
                        <td class="text-end fw-bold">$<?php echo e(number_format((float)($escuela->monto ?? 0), 2)); ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo e($escuela->avance_final ?? 0); ?>%"></div>
                                </div>
                                <span class="small fw-bold"><?php echo e($escuela->avance_final ?? 0); ?>%</span>
                            </div>
                        </td>
                        
                        <td class="text-center">
                            <?php if($escuela->latitud && $escuela->longitud): ?>
                                <a target="_blank" href="<?php echo e(route('mapa.individual', $escuela->id)); ?>" class="btn btn-sm" style="background-color: #3F51B5; color: white;" title="Ver en Mapa">
                                    <i class="fas fa-map-marker-alt"></i> Mapa
                                </a>
                            <?php else: ?>
                                <button class="btn btn-sm btn-secondary" disabled title="Sin coordenadas"><i class="fas fa-map-marker-alt"></i></button>
                            <?php endif; ?>
                        </td>
                        
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fa-3x mb-3"></i><br>
                            No se encontraron registros en el programa Escuelas al 100.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/reportes/escuelas100.blade.php ENDPATH**/ ?>