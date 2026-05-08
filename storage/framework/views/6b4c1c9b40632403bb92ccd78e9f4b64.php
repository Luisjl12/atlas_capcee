

<?php $__env->startSection('content'); ?>
<div class="container">
    <h3>Agregar Proyecto desde otra vista</h3>

    <form action="<?php echo e(route('proyectos.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label>Folio PPI:</label>
            <input type="text" name="folio_ppi" class="form-control" required>
        </div>

        <div class="form-group">
            <label>CCT:</label>
            <input type="text" name="cct" class="form-control">
        </div>

        <div class="form-group">
            <label>Nombre del Proyecto:</label>
            <input type="text" name="nombre_proyecto" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Monto de Inversión:</label>
            <input type="number" step="0.01" name="monto_inversion" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Inicio:</label>
            <input type="date" name="inicio" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Término:</label>
            <input type="date" name="termino" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Empresa:</label>
            <input type="text" name="empresa" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success mt-3">
            <i class="fas fa-save"></i> Guardar Proyecto
        </button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/agregarProyecto.blade.php ENDPATH**/ ?>