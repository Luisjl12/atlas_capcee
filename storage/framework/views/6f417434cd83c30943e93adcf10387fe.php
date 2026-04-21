

<?php $__env->startSection('content'); ?>
<div class="container mt-4">

  <h2 class="mb-3">Reportes de Comparación</h2>

  <div class="accordion" id="accordionComparaciones">

    <!-- Sección 1 -->
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingOne">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
          Niveles
        </button>
      </h2>
      <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" 
           data-bs-parent="#accordionComparaciones">
        <div class="accordion-body">
            <div class="d-flex justify-content-end mb-2">
                <a href="<?php echo e(route('reportes.niveles.exportar')); ?>" 
                class="btn btn-sm btn-primary">
                <i class="fas fa-file-excel"></i> Generar Reporte CSV
                </a>
            </div>

            <table class="table table-striped table-hover">
                <thead class="table">
                <tr>
                    <th>CCT</th>
                    <th>Nivel ingresado (director/docente)</th>
                    <th>Nivel registrado (CAPCEE)</th>
                    <th>Estado</th>
                </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $registros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                    $estado = $row->nivel_comparada === $row->nivel_inmueble ? 'Coinciden' : 'Diferentes';
                    $badgeClass = $estado === 'Coinciden' ? 'bg-success' : 'bg-danger';
                    ?>
                    <tr>
                    <td><?php echo e($row->cct); ?></td>
                    <td><?php echo e($row->nivel_comparada); ?></td>
                    <td><?php echo e($row->nivel_inmueble); ?></td>
                    <td><span class="badge <?php echo e($badgeClass); ?>"><?php echo e($estado); ?></span></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                    <td colspan="4" class="text-center">No hay registros de comparación.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
            </div>

      </div>
    </div>

    <!-- Sección 2 -->
    <div class="accordion-item">
    <h2 class="accordion-header" id="headingThree">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
        Número de Edificios
        </button>
    </h2>
    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" 
        data-bs-parent="#accordionComparaciones">
        <div class="accordion-body">
            <div class="d-flex justify-content-end mb-2">
                <a href="<?php echo e(route('reportes.edificios.exportar')); ?>" 
                class="btn btn-sm btn-success">
                <i class="fas fa-file-excel"></i> Generar Reporte CSV
                </a>
            </div>

            <table class="table table-striped table-hover">
                <thead class="table">
                <tr>
                    <th>CCT</th>
                    <th>Edificios ingresados por el maestro/director</th>
                    <th>Edificios registrados por el CAPCEE</th>
                    <th>Estado</th>
                </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $registrosEdificios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                    $estado = $row->edificios_comparada == $row->edificios_registrados ? 'Coinciden' : 'Diferentes';
                    $badgeClass = $estado === 'Coinciden' ? 'bg-success' : 'bg-danger';
                    ?>
                    <tr>
                    <td><?php echo e($row->cct); ?></td>
                    <td><?php echo e($row->edificios_comparada); ?></td>
                    <td><?php echo e($row->edificios_registrados); ?></td>
                    <td><span class="badge <?php echo e($badgeClass); ?>"><?php echo e($estado); ?></span></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                    <td colspan="4" class="text-center">No hay registros de comparación.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>


  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/reportes/comparacionPlanteles.blade.php ENDPATH**/ ?>