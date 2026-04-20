
<!--Interfaz para la gestion de usuarios-->

<?php $__env->startSection('title', 'Gestionar Usuarios'); ?>

<?php $__env->startSection('content'); ?>


<?php if(session('success')): ?>
<div class="alert alert-success mt-3"><?php echo e(session('success')); ?></div>
<?php endif; ?>
<?php if(session('error')): ?>
<div class="alert alert-danger mt-3"><?php echo e(session('error')); ?></div>
<?php endif; ?>

<?php if ($__env->exists('usuarios.partials.searcher')) echo $__env->make('usuarios.partials.searcher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php if ($__env->exists('usuarios.partials.modal_eliminar')) echo $__env->make('usuarios.partials.modal_eliminar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


    
    <div class="card-header-custom">
        <?php
        use App\Helpers\RoleHelper;
        ?>
        <a href="<?php echo e(RoleHelper::gestionUsuarios(session('role_id'))); ?>" class="btn-icon-only">
            <i class="fas fa-arrow-left"></i>
            <h2><i class="fas fa-users-cog me-2"></i> Gestión de Usuarios</h2>
        </a>
        <a href="<?php echo e(route('usuarios.create')); ?>" class="btn-custom btn-success">
            <i class="fas fa-plus"></i> Agregar Usuario
        </a>
    </div>

    
    <div class="buscador-container">
        <div class="position-relative">
            <i class="fas fa-search position-absolute" style="top: 50%; left: 15px; transform: translateY(-50%); color: var(--color-vino-primario);"></i>
            <input type="text" id="buscar" class="form-control ps-5" placeholder="Buscar usuario por nombre o correo...">
        </div>
    </div>

    
    <div id="tabla-usuarios">
        <?php echo $__env->make('partials.tabla_usuarios', ['usuarios' => $usuarios], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
    
    
    <?php if(method_exists($usuarios, 'links')): ?>
    <nav class="pagination-container" aria-label="Navegación de páginas">
        <ul class="pagination">
            <li class="page-item ">
                <?php echo e($usuarios->links('vendor.pagination.mi_paginacion')); ?>

            </li>
        </ul>
    </nav>
    <?php endif; ?>


<!--Modal para confirmación-->
<div id="modalConfirmacion" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <h5><i class="fas fa-exclamation-triangle"></i> Confirmación</h5>
        <p id="mensajeConfirmacion">¿Estás seguro de continuar?</p>
        <div class="modal-actions">
            <button id="btnCancelar" class="btn-custom btn-cancelar">Cancelar</button>
            <a id="btnEliminar" class="btn-custom btn-danger">Eliminar</a>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!--Script para buscador-->
<script>
    const URL_BUSCAR_USUARIOS = "<?php echo e(route('usuarios.buscar')); ?>";
</script>
<script src="<?php echo e(asset('js/buscador_usuarios.js')); ?>"></script>


<!--Script para confirmar eliminacion-->
<script>
    const CSRF_TOKEN = "<?php echo e(csrf_token()); ?>";
</script>
<script src="<?php echo e(asset('js/modal-confirmacion.js')); ?>"></script>

<!--Script para menu expandible-->
<script src="<?php echo e(asset('js/tabla-expandible.js')); ?>"></script>

<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/gestion_usuarios.blade.php ENDPATH**/ ?>