

<?php $__env->startSection('title', 'Mi perfil'); ?>

<?php $__env->startSection('content'); ?>
<?php
$usuario = \App\Models\Usuario::find(session('id'));
?>


        <?php
        use App\Helpers\RoleHelper;
        ?>
        <div class="card-header-custom">
            <a href="<?php echo e(RoleHelper::dashboardRoute(session('role_id'))); ?>" class="btn-icon-only">
                <i class="fas fa-arrow-left"></i>
                <h2><i class="fas fa-user-edit"></i> Mi Perfil</h2>
            </a>
        </div>

        <?php if(session('success')): ?>
        <div class="alert alert-success mt-3"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
        <div class="alert alert-danger mt-3"><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <?php if($usuario): ?>
        <div class="row profile-container mt-3">

            <!-- Información Personal -->
            <div class="profile-card">
                <div class="card-header border-bottom border-1">
                    <h3 style="color:var(--color-vino-terciario);">Información Personal</h3>
                    <button type="button" class="btn-edit" onclick="toggleEdit('info')">
                        <i class="fas fa-pen"></i> Editar
                    </button>
                </div>

                <form action="<?php echo e(route('perfil.actualizarDatos')); ?>" method="POST" id="form-info">
                    <?php echo csrf_field(); ?>
                    <div class="info-row">
                        <div class="form-group">
                            <label>Nombre Completo</label>
                            <input type="text" name="nombre_completo" id="nombre_completo" class="form-control"
                                value="<?php echo e(old('nombre_completo', $usuario->nombre_completo)); ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label>Teléfono de Contacto</label>
                            <div class="input-group">
                                <input type="tel" name="telefono_contacto" id="telefono_contacto" class="form-control"
                                    value="<?php echo e(old('telefono_contacto', $usuario->telefono_contacto)); ?>" disabled>
                                <button type="button" class="btn btn-secondary" disabled>Validar</button>
                            </div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="form-group full">
                            <label>Correo Electrónico (no se puede cambiar)</label>
                            <input type="email" value="<?php echo e($usuario->correo_electronico); ?>" disabled>
                        </div>
                    </div>

                    <button type="submit" id="guardar-info" class="btn-custom btn-profile" style="display: none;">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </form>
            </div>

            <!-- Cambiar Contraseña -->
            <div class="profile-card">
                <div class="card-header border-bottom border-1">
                    <h3 style="color:var(--color-vino-terciario);">Cambiar Contraseña</h3>
                    <button type="button" class="btn-edit" onclick="toggleEdit('pass')">
                        <i class="fas fa-pen"></i> Editar
                    </button>
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

                <form action="<?php echo e(route('perfil.actualizar-password')); ?>" method="POST" id="form-pass">
                    <?php echo csrf_field(); ?>
                    <div class="info-row">
                        <div class="form-group">
                            <label>Contraseña Actual</label>
                            <input type="password" name="password_actual" id="password_actual" class="form-control" disabled>
                            <?php $__errorArgs = ['password_actual'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <small class="text-danger"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label>Nueva Contraseña</label>
                            <input type="password" name="nueva_password" id="nueva_password" class="form-control" disabled>
                            <?php $__errorArgs = ['nueva_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <small class="text-danger"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="form-group full">
                            <label>Confirmar Nueva Contraseña</label>
                            <input type="password" name="nueva_password_confirmation" id="confirmar_password" class="form-control" disabled>
                        </div>
                    </div>

                    <button type="submit" id="guardar-pass" class="btn-custom btn-profile" style="display: none;">
                        <i class="fas fa-key"></i> Cambiar Contraseña
                    </button>
                </form>
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-danger mt-3">
            No se pudo encontrar la información del usuario.
        </div>
        <?php endif; ?>
<?php $__env->stopSection(); ?>

<!--Referencia del script-->
<script src="<?php echo e(asset('js/info_perfil.js')); ?>"></script>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/perfil.blade.php ENDPATH**/ ?>