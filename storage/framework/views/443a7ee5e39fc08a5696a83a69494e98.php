
    <!--Tabla para los planteles-->
    <table class="table data-table">
        <thead class="thead-custom">
            <tr>
                <th>CCT</th>
                <th>Nombre Escuela</th>
                <th>Municipio</th>
                <th>Director Asignado</th>
                <th>Estatus</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tbody-js">
            <?php $__empty_1 = true; $__currentLoopData = $planteles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plantel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <!-- Fila principal visible solo en móvil -->
            <tr class="plantel-row d-table-row d-md-none">
                <td colspan="6" class="plantel-nombre position-relative" style="cursor: pointer;"><strong>CCT</strong>
                    <div>
                        <i class=""></i>
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
                <td><?php echo e($plantel->municipio->nombre_municipio ?? 'N/D'); ?></td>
                <td><?php echo e($plantel->nombre_director_registrado ?? 'No asignado'); ?></td>
                <td>
                    <span class="badge status-<?php echo e(strtolower($plantel->estatus_plantel)); ?>">
                        <?php echo e($plantel->estatus_plantel); ?>

                    </span>
                </td>
                <td>
                    <div class="acciones-btns d-flex align-items-center gap-1 flex-nowrap">
                        <a href="<?php echo e(route('planteles.show', $plantel->id)); ?>" class="btn btn-sm btn-info custom-tooltip">
                            <i class="fas fa-eye"></i>
                            <span class="tooltiptext">Ver Detalle</span>
                        </a>
                    </div>
                </td>
            </tr>

            <!-- Detalles expandibles solo en móvil -->
            <tr class="plantel-detalle d-none d-md-none">
                <td colspan="6">
                    <div class="detalle-container d-flex flex-wrap justify-content-between gap-3">
                        <div class="detalle-bloque flex-grow-1" style="min-width: 250px;">
                            <strong>CCT:</strong> <?php echo e($plantel->cct); ?><br>
                            <strong>Nombre:</strong><?php echo e($plantel->nombre_escuela); ?><br>
                            <strong>Municipio:</strong> <?php echo e($plantel->municipio->nombre_municipio ?? 'N/D'); ?><br>
                            <strong>Director:</strong> <?php echo e($plantel->nombre_director_registrado ?? 'No asignado'); ?>


                        </div>
                        <div class="detalle-bloque flex-grow-1" style="min-width: 250px;">
                            <strong>Estatus:</strong>
                            <span class="badge status-<?php echo e(strtolower($plantel->estatus_plantel)); ?>">
                                <?php echo e($plantel->estatus_plantel); ?>

                            </span>
                        </div>
                        <div class="w-100 mt-2"><strong>Acciones</strong>
                            <div class="acciones-btns d-flex align-items-center gap-1 flex-wrap">
                                <a href="<?php echo e(route('planteles.show', $plantel->id)); ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i><!--Ver-->
                                </a>
                                <a href="<?php echo e(route('planteles.edit', $plantel->id)); ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-pen"></i><!--Editar-->
                                </a>
                                <button type="button" class="btn btn-sm btn-danger"
                                    onclick="mostrarModalConfirmacion('¿Seguro que quieres eliminar a este plantel?', '<?php echo e(route('planteles.destroy', $plantel->id)); ?>')"><!--Eliminar-->
                                    <i class="fas fa-trash"></i>
                                </button>
                                <a href="<?php echo e(route('planteles.auditoria', $plantel->id)); ?>" class="btn btn-sm btn-secondary" title="Ver Historial de Cambios">
                                    <i class="fas fa-history"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="6" class="text-center">No se encontraron planteles.</td>
            </tr>
            <?php endif; ?>

        </tbody>
    </table><?php /**PATH /home1/bcecacef/atlasinfraescolarpueblaa.online/resources/views/partials/lista_avanzada.blade.php ENDPATH**/ ?>