

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="card shadow border-warning">
        <div class="card-header bg-warning text-dark">
            <h4><i class="fas fa-edit"></i> Editando Reporte: <?php echo e($reporte->titulo); ?></h4>
        </div>
        <div class="card-body">

            <form action="<?php echo e(route('infraescolar.update', $reporte->id)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label>Título del Reporte:</label>
                    <input type="text" name="titulo" class="form-control" value="<?php echo e($reporte->titulo); ?>" required>
                </div>

                <div class="mb-3">
                    <label>Archivo PDF (Solo si deseas cambiarlo, de lo contrario déjalo vacío):</label>
                    <input type="file" name="archivo_pdf" class="form-control" accept="application/pdf">
                    <small class="text-muted"><a href="<?php echo e(asset('storage/' . $reporte->archivo_pdf)); ?>" target="_blank">Ver PDF Actual</a></small>
                </div>

                <hr>
                <h5><i class="fas fa-file-excel"></i> Datos Financieros</h5>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label>Objetos del Gasto (Un gasto por línea):</label>
                        <textarea name="gastos" class="form-control" rows="8" required><?php echo e(implode("\n", $grafica->labels ?? [])); ?></textarea>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label>Aprobado ($):</label>
                        <textarea name="aprobado" class="form-control" rows="8" required><?php echo e(implode("\n", $grafica->aprobado ?? [])); ?></textarea>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Presupuesto Vigente ($):</label>
                        <textarea name="presupuesto_vigente" class="form-control" rows="8" required><?php echo e(implode("\n", $grafica->vigente ?? [])); ?></textarea>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Pagado ($):</label>
                        <textarea name="pagado" class="form-control" rows="8" required><?php echo e(implode("\n", $grafica->pagado ?? [])); ?></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="<?php echo e(route('infraescolar.admin')); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Cancelar y Regresar</a>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/admin/editar_infraescolar.blade.php ENDPATH**/ ?>