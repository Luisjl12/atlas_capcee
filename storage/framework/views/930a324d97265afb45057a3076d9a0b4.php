<div class="mt-3 data-table-container">
    <!--Tabla para los planteles-->
    <table class="table data-table">
        <thead class="thead-custom">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Estatus</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tbody-js">
            <?php $__empty_1 = true; $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <!-- Fila principal visible solo en móvil -->
            <tr class="usuario-row d-table-row d-md-none">
                <td colspan="6" class="usuario-nombre position-relative" style="cursor: pointer;"><strong>Nombre:</strong>
                    <div>

                        <?php echo e($usuario->nombre_completo); ?>

                    </div>
                    <div class="toggle-icon position-absolute top-0 end-0 p-2">
                        <i class="fas fa-chevron-down text-muted"></i>
                    </div>
                </td>
            </tr>

            <!-- Fila completa visible solo en escritorio -->
            <tr class="d-none d-md-table-row">
                <td><?php echo e($usuario->id); ?></td>
                <td><?php echo e($usuario->nombre_completo); ?></td>
                <td><?php echo e($usuario->correo_electronico); ?></td>
                <td><?php echo e($usuario->rol->nombre_rol ?? 'N/D'); ?></td>
                <td>
                    <span class="badge status-<?php echo e(strtolower($usuario->estado)); ?>"><?php echo e($usuario->estado); ?></span>
                </td>
                <td>
                    <div class="acciones-btns">
                        <a href="<?php echo e(route('usuarios.edit', $usuario->id)); ?>" class="btn btn-sm btn-vino custom-tooltip">
                            <i class="fas fa-pen"></i>
                            <span class="tooltiptext">Editar</span>
                        </a>
                        <?php if(auth()->id() !== $usuario->id): ?>
                        <button type="button" class="btn btn-sm btn-danger"
                            onclick="mostrarModalConfirmacion('¿Seguro que quieres eliminar a <?php echo e($usuario->nombre_completo); ?>?', '<?php echo e(route('usuarios.destroy', $usuario->id)); ?>')">
                            <i class="fas fa-trash"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>

            <!-- Detalles expandibles solo en móvil -->
            <tr class="usuario-detalle d-none d-md-none">
                <td colspan="6">
                    <div class="detalle-container d-flex flex-wrap justify-content-between gap-3">
                        <div class="detalle-bloque flex-grow-1" style="min-width: 250px;">
                            <strong>Nombre:</strong> <?php echo e($usuario->nombre_completo); ?><br>
                            <strong>ID:</strong> <?php echo e($usuario->id); ?><br>
                            <strong>Correo:</strong> <?php echo e($usuario->correo_electronico); ?><br>
                            <strong>Rol:</strong> <?php echo e($usuario->rol->nombre_rol ?? 'N/D'); ?>

                        </div>
                        <div class="detalle-bloque flex-grow-1" style="min-width: 250px;">
                            <strong>Estatus:</strong>
                            <span class="badge status-<?php echo e(strtolower($usuario->estado)); ?>"><?php echo e($usuario->estado); ?></span>
                        </div>
                        <div class="w-100 mt-2">
                            <div class="acciones-btns">
                                <a href="<?php echo e(route('usuarios.edit', $usuario->id)); ?>" class="btn btn-sm btn-vino">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <?php if(auth()->id() !== $usuario->id): ?>
                                <button type="button" class="btn btn-sm btn-danger"
                                    onclick="mostrarModalConfirmacion('¿Seguro que quieres eliminar a <?php echo e($usuario->nombre_completo); ?>?', '<?php echo e(route('usuarios.destroy', $usuario->id)); ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="6" class="text-center">No hay usuarios registrados.</td>
            </tr>
            <?php endif; ?>

        </tbody>
    </table>
</div><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/partials/tabla_usuarios.blade.php ENDPATH**/ ?>