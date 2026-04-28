

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h2 class="mb-3">Importar Proyectos</h2>

    <!-- Formulario de importación -->
    <form action="<?php echo e(route('proyectos.importar')); ?>" method="POST" enctype="multipart/form-data" class="mb-4">
        <?php echo csrf_field(); ?>
        <input type="file" name="file" required>
        <button type="submit" class="btn btn-primary">Importar</button>
    </form>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <!-- Tabla de registros -->
    <h3 class="mb-3">Registros en la base de datos</h3>
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Folio PPI</th>
                <th>Municipio</th>
                <th>Nombre Proyecto</th>
                <th>Monto Inversión</th>
                <th>Inicio</th>
                <th>Término</th>
                <th>Empresa</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $registros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($row->id); ?></td>
                    <td><?php echo e($row->folio_ppi); ?></td>
                    <td><?php echo e($row->municipio); ?></td>
                    <td><?php echo e($row->nombre_proyecto); ?></td>
                    <td><?php echo e(number_format($row->monto_inversion, 2)); ?></td>
                    <td><?php echo e($row->inicio); ?></td>
                    <td><?php echo e($row->termino); ?></td>
                    <td><?php echo e($row->empresa); ?></td>
                    <td>
                    <!--Boton para eliminar-->
                    <form action = "<?php echo e(route('proyectos.destroy', $row->id)); ?>" method="POST" Onsubmit="return confirm('¿Seguro que quieres eliminar este proyecto?');">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-sm btn-danger">
                             <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="text-center">No hay registros disponibles.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


<?php if($registros->hasPages()): ?>
    <nav class="pagination-container" aria-label="Navegación de páginas">
        <?php echo e($registros->links('vendor.pagination.mi_paginacion')); ?>

    </nav>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/datosProyectos.blade.php ENDPATH**/ ?>