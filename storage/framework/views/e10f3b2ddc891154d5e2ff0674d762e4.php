

<?php $__env->startSection('content'); ?>


<div class="card-header-custom">
    <?php
    use App\Helpers\RoleHelper;
    ?>
    <a href="<?php echo e(RoleHelper::historialVista(session('role_id'))); ?>" class="btn-icon-only">
        <i class="fas fa-arrow-left"></i>
        <h2><i class="fas fa-history"></i> Historial de Cambios</h2>
    </a>
</div>

<div class="mt-3 data-table-container">
    <table class="table data-table">
        <thead class="thead-custom">
            <tr>
                <th>Fecha</th>
                <th>Entidad</th>
                <th>CCT / Plantel</th>
                <th>Evento</th>
                <th>Detalles</th>
                <th>Cambios realizados</th>
            </tr>
        </thead>
        <tbody id="tbody-js">
            <?php $__empty_1 = true; $__currentLoopData = $auditorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $audit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            
            <tr class="usuario-row d-table-row d-md-none">
                <td colspan="6" class="usuario-nombre position-relative" style="cursor: pointer;">
                
                        <?php if($audit->auditable instanceof \App\Models\Plantel): ?>
                        <?php echo e($audit->auditable->cct ?? '—'); ?> - <?php echo e($audit->auditable->nombre_escuela ?? 'Plantel eliminado'); ?>

                        <?php elseif($audit->auditable instanceof \App\Models\DetalleServicio): ?>
                        <?php echo e($audit->auditable->cct ?? 'Sin CCT'); ?> - <?php echo e($audit->auditable->plantel->nombre_escuela ?? 'Sin nombre'); ?>

                        <?php elseif($audit->auditable instanceof \App\Models\DetalleHidrosanitario): ?>
                        <?php echo e($audit->auditable->cct ?? 'Sin CCT'); ?> - <?php echo e(optional($audit->auditable->plantel)->nombre_escuela ?? 'Sin nombre'); ?>

                        <?php else: ?>
                        —
                        <?php endif; ?>
                    
                    <div class="toggle-icon position-absolute top-0 end-0 p-2">
                        <i class="fas fa-chevron-down text-muted"></i>
                    </div>
                </td>
            </tr>

            
            <tr class="usuario-detalle d-none d-md-none">
                <td colspan="6">
                    <div class="detalle-container d-flex flex-wrap gap-3">
                        <div class="detalle-bloque flex-grow-1" style="min-width: 250px;">
                            <strong>Fecha:</strong> <?php echo e($audit->created_at->format('d/m/Y H:i')); ?><br>
                            <strong>Evento:</strong> <?php echo e(ucfirst($audit->event)); ?><br>

                            <?php if($audit->auditable instanceof \App\Models\Plantel): ?>
                            <strong>Entidad:</strong> Plantel<br>
                            <strong>CCT:</strong> <?php echo e($audit->auditable->cct ?? '—'); ?><br>
                            <strong>Nombre:</strong> <?php echo e($audit->auditable->nombre_escuela ?? '—'); ?><br>
                            <?php elseif($audit->auditable instanceof \App\Models\DetalleServicio): ?>
                            <strong>Entidad:</strong> Servicios<br>
                            <strong>CCT:</strong> <?php echo e($audit->auditable->cct ?? '—'); ?><br>
                            <strong>Nombre:</strong> <?php echo e($audit->auditable->plantel->nombre_escuela ?? '—'); ?><br>
                            <strong>Internet:</strong> <?php echo e($audit->auditable->internet_tipo ?? '—'); ?><br>
                            <?php elseif($audit->auditable instanceof \App\Models\DetalleProteccionCivil): ?>
                            <strong>Entidad:</strong> Protección Civil<br>
                            <strong>CCT:</strong> <?php echo e($audit->auditable->cct ?? '—'); ?><br>
                            <strong>Extintores vigentes:</strong> <?php echo e($audit->auditable->extintores_vigentes ?? '—'); ?><br>
                            <?php elseif($audit->auditable instanceof \App\Models\DetalleHidrosanitario): ?>
                            <strong>Entidad:</strong> Hidrosanitario<br>
                            <strong>CCT:</strong> <?php echo e($audit->auditable->cct ?? '—'); ?><br>
                            <strong>Nombre:</strong> <?php echo e(optional($audit->auditable->plantel)->nombre_escuela ?? '—'); ?><br>
                            <strong>Fuente de agua:</strong> <?php echo e($audit->auditable->fuente_agua ?? '—'); ?><br>
                            <strong>Tipo de drenaje:</strong> <?php echo e($audit->auditable->tipo_drenaje ?? '—'); ?><br>
                            <?php endif; ?>

                        </div>

                        <div class="detalle-bloque flex-grow-1" style="min-width: 250px;">
                            <strong>Detalles:</strong><br>
                            <?php $__empty_2 = true; $__currentLoopData = $audit->new_values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campo => $valor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            <strong><?php echo e($campo); ?>:</strong> <?php echo e($valor); ?><br>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                            <em>No hay cambios registrados.</em>
                            <?php endif; ?>

                            <strong>Tags:</strong><br>
                            <?php $__empty_2 = true; $__currentLoopData = json_decode($audit->tags ?? '[]'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            <span class="badge status-historial me-1"><?php echo e($tag); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                            <em>Sin etiquetas.</em>
                            <?php endif; ?>
                        </div>
                    </div>
                </td>
            </tr>

            
            <tr class="d-none d-md-table-row">
                <td><?php echo e($audit->created_at->format('d/m/Y H:i')); ?></td>
                <td>
                    <?php if($audit->auditable instanceof \App\Models\Plantel): ?>
                    Plantel
                    <?php elseif($audit->auditable instanceof \App\Models\DetalleServicio): ?>
                    Servicios

                    <?php elseif($audit->auditable instanceof \App\Models\DetalleProteccionCivil): ?>
                    Protección Civil
                    <?php elseif($audit->auditable instanceof \App\Models\DetalleHidrosanitario): ?>
                    Hidrosanitario
                    <?php else: ?>
                    —
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($audit->auditable instanceof \App\Models\Plantel): ?>
                    <?php echo e($audit->auditable->cct ?? '—'); ?> - <?php echo e($audit->auditable->nombre_escuela ?? 'Plantel eliminado'); ?>

                    <?php elseif($audit->auditable instanceof \App\Models\DetalleServicio): ?>
                    <?php echo e($audit->auditable->cct ?? 'Sin CCT'); ?> - <?php echo e($audit->auditable->plantel->nombre_escuela ?? 'Sin nombre'); ?>


                    <?php elseif($audit->auditable instanceof \App\Models\DetalleProteccionCivil): ?>
                    <?php echo e($audit->auditable->cct ?? 'Sin CCT'); ?>

                    <?php elseif($audit->auditable instanceof \App\Models\DetalleHidrosanitario): ?>
                    <?php echo e($audit->auditable->cct ?? 'Sin CCT'); ?> - <?php echo e(optional($audit->auditable->plantel)->nombre_escuela ?? 'Sin nombre'); ?>


                    <?php else: ?>
                    —
                    <?php endif; ?>
                </td>
                <td><?php echo e(ucfirst($audit->event)); ?></td>
                <td>
                    <?php $__currentLoopData = $audit->new_values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campo => $valor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <strong><?php echo e($campo); ?>:</strong> <?php echo e($valor); ?><br>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </td>
                <td>
                    <?php $__currentLoopData = json_decode($audit->tags ?? '[]'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="badge status-historial me-1"><?php echo e($tag); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="6" class="text-center">No hay modificaciones registradas.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
 
    <?php if(method_exists($auditorias, 'links')): ?>
    <nav class="pagination-container" aria-label="Navegación de páginas">
        <ul class="pagination">
            <li class="page-item">
                <?php echo e($auditorias->links('vendor.pagination.mi_paginacion')); ?>

            </li>
        </ul>
    </nav>
    <?php endif; ?>

<?php $__env->startPush('scripts'); ?>
<script>
    window.addEventListener('load', function() {
        const filasPrincipales = document.querySelectorAll('.usuario-row');

        filasPrincipales.forEach(fila => {
            fila.addEventListener('click', function() {
                const siguiente = fila.nextElementSibling;

                if (siguiente && siguiente.classList.contains('usuario-detalle')) {
                    siguiente.classList.toggle('d-none');

                    const icono = fila.querySelector('.toggle-icon i');
                    if (icono) {
                        icono.classList.toggle('fa-chevron-down');
                        icono.classList.toggle('fa-chevron-up');
                    }
                }
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/historial/index.blade.php ENDPATH**/ ?>