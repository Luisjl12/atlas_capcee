

<?php $__env->startSection('title', 'Importar Datos'); ?>

<?php $__env->startSection('content'); ?>

        <?php
        use App\Helpers\RoleHelper;
        ?>
        <div class="card-header-custom">
            <a href="<?php echo e(RoleHelper::importarDatos(session('role_id'))); ?>" class="btn-icon-only">
                <i class="fas fa-arrow-left"></i>
                <h2><i class="fas fa-file-upload"></i> Importar Datos de Planteles</h2>
            </a>
        </div>

        <?php if(session('mensaje')): ?>
        <div class="alert alert-success">
            <?php echo e(session('mensaje')); ?>

        </div>
        <?php endif; ?>


        <?php if(session('errores_csv')): ?>
        <div class="alert alert-warning mt-3">
            <strong>Advertencias durante la importación:</strong>
            <ul>
                <?php $__currentLoopData = session('errores_csv'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
        <div class="alert alert-danger mt-3">
            <strong>Errores de validación:</strong>
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>
        <?php if(session('errores')): ?>
        <div class="alert alert-warning">
            <strong>Errores durante la importación:</strong>
            <ul>
                <?php $__currentLoopData = session('errores'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error['cct']); ?>: <?php echo e($error['error']); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="card-body-custom p-4 mt-3">
            <p>Sube un archivo CSV para cargar información de escuelas de forma masiva.</p>
            <div class="mb-3">
                <strong>Asegurese de que el archivo CSV incluya los encabezados con los mismos nombres que los
                        campos requeridos:</strong><br>
                <span class="chip"><i class="fas fa-check"></i><strong>**CV_CCT (CCT)</strong></span>
                <span class="chip"><i class="fas fa-school"></i><strong>**NOMBRECT (Nombre del plantel)</strong></span>
                <span class="chip"><i class="fas fa-map-marker-alt"></i>C_NOM_MUN (Nombre del municipio)</span>
                <span class="chip"><i class="fas fa-building"></i>NOMBRE_CORDE (Nombre del corde)</span>
            </div>

            <form action="<?php echo e(route('importarDatos.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

                <label for="archivo" class="dropzone">
                    <i class="fas fa-file-csv fa-2x"></i>
                    <p>Haz clic aqui para subir tu archivo CSV</p>
                    <input type="file" name="archivo" id="archivo" class="input-csv" required>
                </label>
                <button type="submit" class="btn-custom btn-primary mt-3">
                    <i class="fas fa-upload"></i> Importar Datos
                </button>
            </form>
        </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home1/bcecacef/atlasinfraescolarpueblaa.online/resources/views/importar_datos/index.blade.php ENDPATH**/ ?>