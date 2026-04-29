
<?php $__env->startSection('title', 'Crear nuevo usuario'); ?>


<?php $__env->startSection('content'); ?>
<div class="card-header-custom">
    <a href="<?php echo e(route('usuarios.index')); ?>" class="btn-icon-only">
        <i class="fas fa-arrow-left "></i>
        <h2><i class="fas fa-user-plus"></i> Agregar Nuevo Usuario</h2>
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
    <div class="alert alert-success">
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    
   
            <form method="POST" action="<?php echo e(route('usuarios.store')); ?>" class="form-ficha-base">
                <?php echo csrf_field(); ?>

            <div class="card-body-custom p-4">
                <h4>Datos del Usuario</h4>
           
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Nombre Completo *</label>
                        <input type="text" name="nombre_completo" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Correo Electrónico *</label>
                        <input type="email" name="correo_electronico" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Contraseña *</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono de contacto *</label>
                        <input type="text" name="telefono_contacto" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Rol *</label>
                        <select name="role_id" class="form-select" required>
                            <option value="">Seleccione un Rol</option>
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rol): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($rol->id); ?>"><?php echo e($rol->nombre_rol); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Estatus *</label>
                        <select name="estado" class="form-select" required>
                            <option value="">Seleccione estatus</option>
                            <option value="ACTIVO">ACTIVO</option>
                            <option value="INACTIVO">INACTIVO</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-start gap-2">
                    <button type="submit" class="btn-custom btn-primary">
                        <i class="fas fa-save"></i> Crear
                    </button>
                </div>
            </form>
            </div>
    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/create.blade.php ENDPATH**/ ?>