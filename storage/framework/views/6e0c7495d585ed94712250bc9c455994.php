

<?php $__env->startSection('content'); ?>


<div class="container">
    <div class="card-header bg-white border-bottom mb-4">
        <a href="<?php echo e(route('planteles.index')); ?>" class="text-decoration-none d-inline-flex align-items-center text-dark">
            <h4 class="mb-0">
                <i class="fas fa-arrow-left"></i>
                <i class="fas fa-school me-2"></i> Historial de Cambios: <?php echo e($plantel->nombre_escuela); ?>

                <small class="text-muted">(CCT: <?php echo e($plantel->cct); ?>)</small>
            </h4>
        </a>
    </div>

    <table class="table table-hover">
        <thead class="thead-custom">
            <tr>
                <th></th>
                <th>Usuario</th>
                <th>Evento</th>
                <th>Fecha</th>
                <th>Tags</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $plantel->auditorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $audit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="audit-toggle" data-index="<?php echo e($index); ?>">
                <td class="toggle-icon text-center">
                    <i class="fas fa-chevron-down cursor-pointer"></i>
                </td>
                <td><?php echo e($audit->user->name ?? 'Sistema'); ?></td>
                <td>
                    <span class="badge bg-<?php echo e($audit->event === 'updated' ? 'warning' : ($audit->event === 'created' ? 'success' : 'danger')); ?>">
                        <?php echo e(ucfirst($audit->event)); ?>

                    </span>
                </td>
                <td><?php echo e($audit->created_at->format('d/m/Y H:i')); ?></td>
                <td>
                    <?php $__currentLoopData = json_decode($audit->tags ?? '[]', true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="badge bg-info"><?php echo e($tag); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </td>
            </tr>
            <tr class="audit-detail d-none" data-index="<?php echo e($index); ?>">
                <td colspan="5">
                    <div class="p-2 bg-light rounded">
                        <?php
                        $municipioAnterior = \App\Models\Municipio::find($audit->old_values['id_municipio'] ?? null)?->nombre_municipio;
                        $municipioNuevo = \App\Models\Municipio::find($audit->new_values['id_municipio'] ?? null)?->nombre_municipio;
                        ?>

                        <?php if($municipioAnterior && $municipioNuevo && $municipioAnterior !== $municipioNuevo): ?>
                        <p><strong>Municipio:</strong> <?php echo e($municipioAnterior); ?> → <?php echo e($municipioNuevo); ?></p>
                        <?php endif; ?>

                        <?php if(isset($audit->municipio_cambio)): ?>
                        <p><strong>Municipio:</strong> <?php echo e($audit->municipio_cambio['de']); ?> → <?php echo e($audit->municipio_cambio['a']); ?></p>
                        <?php endif; ?>
                        <!--Tag para localidades--->
                        <?php
                        $localidadAnterior = \App\Models\Localidad::find($audit->old_values['id_localidad'] ?? null)?->nombre_localidad;
                        $localidadNueva = \App\Models\Localidad::find($audit->new_values['id_localidad'] ?? null)?->nombre_localidad;
                        ?>

                        <?php if($localidadAnterior && $localidadNueva && $localidadAnterior !== $localidadNueva): ?>
                        <p><strong>Localidad:</strong> <?php echo e($localidadAnterior); ?> → <?php echo e($localidadNueva); ?></p>
                        <?php endif; ?>
                        <!--Tag de corde-->
                        <?php
                        $cordeAnterior = \App\Models\Corde::find($audit->old_values['id_corde'] ?? null)?->nombre_corde;
                        $cordeNueva = \App\Models\Corde::find($audit->new_values['id_corde'] ?? null)?->nombre_corde;
                        ?>

                        <?php if($cordeAnterior && $cordeNueva && $cordeAnterior !== $cordeNueva): ?>
                        <p><strong>Localidad:</strong> <?php echo e($cordeAnterior); ?> → <?php echo e($cordeNueva); ?></p>
                        <?php endif; ?>

                        <strong>Antes:</strong>
                        <ul class="mb-2">
                            <?php $__currentLoopData = $audit->old_values ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><strong><?php echo e($key); ?>:</strong> <?php echo e($value); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>

                        <strong>Después:</strong>
                        <ul>
                            <?php $__currentLoopData = $audit->new_values ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><strong><?php echo e($key); ?>:</strong> <?php echo e($value); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>

                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="5">No hay historial de cambios registrados para este plantel.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.audit-toggle').forEach(row => {
            row.addEventListener('click', function() {
                const index = row.getAttribute('data-index');
                const detailRow = document.querySelector(`.audit-detail[data-index="${index}"]`);
                const icon = row.querySelector('.toggle-icon i');

                detailRow.classList.toggle('d-none');

                if (detailRow.classList.contains('d-none')) {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                } else {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                }
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home1/bcecacef/atlasinfraescolarpueblaa.online/resources/views/planteles/auditorias.blade.php ENDPATH**/ ?>