

<?php $__env->startSection('content'); ?>
<!--Interfaz para panel principal o index de supervision-->

            <?php
            use App\Helpers\RoleHelper;
            ?>
            <div class="card-header-custom">
                <a href="<?php echo e(RoleHelper::gestionSupervision(session('role_id'))); ?>" class="btn-icon-only">
                    <i class="fas fa-arrow-left"></i>
                    <h2><i class="fas fa-tasks"></i> Panel de Supervisión</h2>
                </a>
            </div>
          
                <p>A continuación se muestra el resumen del avance de captura de información por cada CORDE.</p>
                <div class="table-responsive mt-3 data-table-container">
                    <table class="table table-hover data-table">
                        <thead class="thead-custom">
                            <tr>
                                <th>CORDE</th>
                                <th>Total de Planteles</th>
                                <th>Avance Promedio</th>
                                <th>Última Actualización</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $datos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dato): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <!-- Fila principal visible solo en móvil -->
                            <tr class="corde-row d-table-row d-md-none">
                                <td colspan="5" class="corde-nombre position-relative" style="cursor: pointer;">
                                    <div>
                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                        <?php echo e($dato->nombre_corde); ?>

                                    </div>
                                    <div class="toggle-icon position-absolute top-0 end-0 p-2">
                                        <i class="fas fa-chevron-down text-muted"></i>
                                    </div>
                                </td>
                            </tr>

                            <!-- Fila completa visible solo en escritorio -->
                            <tr class="d-none d-md-table-row">
                                <td><?php echo e($dato->nombre_corde); ?></td>
                                <td><?php echo e($dato->total_planteles); ?></td>
                                <td><?php echo e($dato->avance_promedio ? number_format($dato->avance_promedio, 2) : 'N/A'); ?></td>
                                <td><?php echo e($dato->ultima_actualizacion ? \Carbon\Carbon::parse($dato->ultima_actualizacion)->format('d/m/Y') : 'Sin registro'); ?></td>
                                <td>
                                    <div class="acciones-btns d-flex align-items-center gap-1 flex-nowrap">
                                        <a href="<?php echo e(route('supervision.show', ['id' => $dato->id])); ?>" class="btn-custom btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Ver Detalle
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <!-- Detalles expandibles solo en móvil -->
                            <tr class="corde-detalle d-none d-md-none">
                                <td colspan="5">
                                    <div class="detalle-container d-flex flex-wrap justify-content-between gap-3">
                                        <div class="detalle-bloque flex-grow-1" style="min-width: 250px;">
                                            <strong>Total de Planteles:</strong> <?php echo e($dato->total_planteles); ?><br>
                                            <strong>Avance Promedio:</strong> <?php echo e($dato->avance_promedio ? number_format($dato->avance_promedio, 2) : 'N/A'); ?>

                                        </div>
                                        <div class="detalle-bloque flex-grow-1" style="min-width: 250px;">
                                            <strong>Última Actualización:</strong>
                                            <?php echo e($dato->ultima_actualizacion ? \Carbon\Carbon::parse($dato->ultima_actualizacion)->format('d/m/Y') : 'Sin registro'); ?>

                                        </div>
                                        <div class="w-100 mt-2">
                                            <a href="<?php echo e(route('supervision.show', ['id' => $dato->id])); ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Ver Detalle
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>
                    </table>
                </div>
            

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

<script src="<?php echo e(asset('js/tabla-expandible-supervision.js')); ?>"></script>


<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home1/bcecacef/atlasinfraescolarpueblaa.online/resources/views/panel_supervision/supervision.blade.php ENDPATH**/ ?>