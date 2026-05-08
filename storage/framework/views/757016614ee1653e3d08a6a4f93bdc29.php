
<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('tickets.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <label>Folio</label>
        <input type="text" name="folio" required>

        <label>Número de Oficio</label>
        <input type="text" name="numero_oficio">

        <label>Áreas Turnadas</label>
        <input type="text" name="areas_turnadas">

        <label>Quién Atiende</label>
        <input type="text" name="quien_atiende">

        <label>Anexo</label>
        <input type="checkbox" name="anexo" value="1">

        <label>Fecha Oficialía</label>
        <input type="date" name="fecha_oficialia">

        <label>Fecha DFE</label>
        <input type="date" name="fecha_dfe">

        <button type="submit">Crear Ticket</button>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/proyectos_especiales.blade.php ENDPATH**/ ?>