
<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h2>Editar Proyecto</h2>

    <form action="<?php echo e(route('proyectos.update', $proyecto->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="mb-3">
            <label for="folio_ppi" class="form-label">Folio PPI</label>
            <input type="text" class="form-control" id="folio_ppi" name="folio_ppi" value="<?php echo e($proyecto->folio_ppi); ?>" readonly>
        </div>

        <div class="mb-3">
            <label for="municipio" class="form-label">Municipio</label>
            <input type="text" class="form-control" id="municipio" name="municipio" value="<?php echo e($proyecto->municipio); ?>">
        </div>

        <div class="mb-3">
            <label for="nombre_proyecto" class="form-label">Nombre Proyecto</label>
            <textarea class="form-control" id="nombre_proyecto" name="nombre_proyecto"><?php echo e($proyecto->nombre_proyecto); ?></textarea>
        </div>

        <div class="mb-3">
            <label for="monto_inversion" class="form-label">Monto Inversión</label>
            <input type="number" step="0.01" class="form-control" id="monto_inversion" name="monto_inversion" value="<?php echo e($proyecto->monto_inversion); ?>">
        </div>

        <div class="mb-3">
            <label for="inicio" class="form-label">Inicio</label>
            <input type="date" class="form-control" id="inicio" name="inicio" value="<?php echo e($proyecto->inicio); ?>">
        </div>

        <div class="mb-3">
            <label for="termino" class="form-label">Término</label>
            <input type="date" class="form-control" id="termino" name="termino" value="<?php echo e($proyecto->termino); ?>">
        </div>

        <div class="mb-3">
            <label for="empresa" class="form-label">Empresa</label>
            <input type="text" class="form-control" id="empresa" name="empresa" value="<?php echo e($proyecto->empresa); ?>">
        </div>

        <button type="submit" class="btn btn-success">Guardar cambios</button>
        <a href="<?php echo e(route('proyectos.index')); ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/proyectos/edit.blade.php ENDPATH**/ ?>