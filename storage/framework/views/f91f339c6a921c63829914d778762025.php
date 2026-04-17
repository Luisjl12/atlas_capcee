
<?php $__env->startSection('title', 'Editar Usuario'); ?>

<?php $__env->startSection('content'); ?>


    <div class="card-header-custom">
        <a href="<?php echo e(route('usuarios.index')); ?>" class="btn-icon-only">
            <i class="fas fa-arrow-left"></i>
            <h2><i class="fas fa-user-edit"></i> Editar Usuario: <?php echo e($usuario->nombre_completo); ?></h2>
        </a>
    </div>

    <?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

  
        <form method="POST" action="<?php echo e(route('usuarios.update', $usuario->id)); ?>" class="form-ficha-base">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="card-body-custom p-4">
            <h4>Datos del Usuario</h4>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Nombre Completo: *</label>
                    <input type="text" name="nombre_completo" value="<?php echo e($usuario->nombre_completo); ?>" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Correo: *</label>
                    <input type="email" name="correo_electronico" value="<?php echo e($usuario->correo_electronico); ?>" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Contraseña:</label>
                    <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para no cambiar">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Telefono: *</label>
                    <input type="text" name="telefono_contacto" value="<?php echo e($usuario->telefono_contacto); ?>" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Rol: *</label>
                    <select name="role_id" class="form-select" required>
                        <option value="">Seleccione un Rol</option>
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rol): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($rol->id); ?>" <?php echo e($usuario->role_id == $rol->id ? 'selected' : ''); ?>>
                            <?php echo e($rol->nombre_rol); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Estatus: *</label>
                    <select name="estado" class="form-select" required>
                        <option value="ACTIVO" <?php echo e($usuario->estado == 'ACTIVO' ? 'selected' : ''); ?>>ACTIVO</option>
                        <option value="INACTIVO" <?php echo e($usuario->estado == 'INACTIVO' ? 'selected' : ''); ?>>INACTIVO</option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-start gap-2">
                <button type="submit" class="btn-custom btn-primary">
                    <i class="fas fa-save"></i> Actualizar
                </button>
            </div>
        </form>
        </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home1/bcecacef/atlasinfraescolarpueblaa.online/resources/views/edit.blade.php ENDPATH**/ ?>