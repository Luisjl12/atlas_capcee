

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    
    <div class="card shadow mb-4 p-4">
        <div class="card-header bg-light border-bottom">
            <h5 class="text-dark mb-0 fs-6 fs-md-4 fw-semibold">
                <i class="fas fa-upload text-primary me-2"></i> Subir Nuevo Reporte Financiero Infraescolar
            </h5>
        </div>

        <div class="card-body">
            
            <?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
            <?php endif; ?>

            <form action="<?php echo e(route('infraescolar.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                
                <div class="mb-3">
                    <label>Título del Reporte:</label>
                    <input type="text" name="titulo" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Archivo PDF:</label>
                    <input type="file" name="archivo_pdf" class="form-control" accept="application/pdf" required>
                </div>

                <hr>
                <h5><i class="fas fa-file-excel"></i> Datos Financieros para la Gráfica</h5>
                
                <div class="alert alert-success py-2 mb-4 text-sm">
                    <strong><i class="fas fa-magic"></i> ¡Soporte para Excel!</strong><br>
                    Ya no uses comas para separar los datos. Ahora debes poner <strong>un valor por línea (Enter)</strong>. Puedes copiar toda una columna de tu archivo de Excel (con signos de pesos, comas y centavos) y pegarla directamente en cada cuadro. El sistema hará el resto.
                </div>

                <div class="row">
                    <div class="col-12 col-md-4 mb-3">
                        <label>Objetos del Gasto (Un gasto por línea):</label>
                        <textarea name="gastos" class="form-control" rows="5" placeholder="Sueldo base al personal de confianza&#10;Primas de vacaciones y dominical&#10;Aguinaldo o gratificación" required></textarea>
                    </div>
                    
                    <div class="col-12 col-md-4 mb-3">
                        <label>Aprobado ($):</label>
                        <textarea name="aprobado" class="form-control" rows="5" placeholder="$10,572,804.00&#10;$364,311.00&#10;$1,350,969.00" required></textarea>
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label>Presupuesto Vigente ($):</label>
                        <textarea name="presupuesto_vigente" class="form-control" rows="5" placeholder="$7,981,281.86&#10;$364,311.00&#10;$2,248,198.14" required></textarea>
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label>Pagado ($):</label>
                        <textarea name="pagado" class="form-control" rows="5" placeholder="$2,591,522.14&#10;$0.00&#10;$0.00" required></textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 w-md-auto mt-3 btn-sm"><i class="fas fa-save"></i> Guardar y Generar Gráfica Múltiple</button>
            </form>
            </div>
    </div>
        <div class="d-md-none">
        <?php $__currentLoopData = $reportes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card mb-2">
            <div class="card-body">
                <h6 class="fw-bold"><?php echo e($rep->titulo); ?></h6>
                <p class="text-muted"><?php echo e($rep->created_at->format('d/m/Y')); ?></p>
                <div class="d-flex gap-2">
                    <a href="<?php echo e(route('infraescolar.edit', $rep->id)); ?>" class="btn btn-warning btn-sm">Editar</a>
                    <form action="<?php echo e(route('infraescolar.destroy', $rep->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="d-none d-md-block">
    <div class="card shadow mb-5">
        <div class="card-header bg-light border-bottom">
            <h5 class="text-secondary mb-0">
                <i class="fas fa-list text-primary me-2"></i> Reportes Administrados
            </h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Título del Reporte</th>
                            <th>Fecha de Subida</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $reportes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="fw-bold text-start"><?php echo e($rep->titulo); ?></td>
                            <td><?php echo e($rep->created_at->format('d/m/Y')); ?></td>
                            <td>
                                <a href="<?php echo e(route('infraescolar.edit', $rep->id)); ?>" class="btn btn-warning btn-sm shadow-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>

                                <form action="<?php echo e(route('infraescolar.destroy', $rep->id)); ?>" method="POST" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm shadow-sm" onclick="return confirm('¿Estás seguro de que deseas ELIMINAR este reporte? Esta acción no se puede deshacer.')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="text-muted">No hay reportes subidos todavía.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/admin/formulario_infraescolar.blade.php ENDPATH**/ ?>