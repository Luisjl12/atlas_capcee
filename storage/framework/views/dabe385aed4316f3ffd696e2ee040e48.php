

<?php $__env->startSection('content'); ?>
<?php
use App\Helpers\RoleHelper;
?>
<div class="container mt-4">
    <style>
        .proyecto-detalle {
        background-color: #f9f9f9;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }
    
    .proyecto-detalle strong {
        color: #495057;
    }
    
    .proyecto-detalle .acciones-btns .btn {
        min-width: 36px;
    }

    </style>
    <a href="<?php echo e(RoleHelper::gestionPlanteles(session('role_id'))); ?>" class="btn-icon-only">
        <i class="fas fa-arrow-left"></i>
        <h5><i class="fas fa-lightbulb"></i> Proyectos Capcee</h5>
    </a>

   
    
    <!-- Formulario de importación con cursor pointer -->
    <div class="card shadow-sm mb-4">
        <div class="card-body text-center p-5">
            <form action="<?php echo e(route('proyectos.importar')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <!-- Área de carga -->
                <div class="mb-3">
                    <label for="file" class="form-label d-block upload-area">
                        <i class="fas fa-file-csv fa-4x text-success mb-3"></i>
                        <p class="text-muted">Haz clic aquí o arrastra tu archivo CSV/Excel</p>
                    </label>
                    <input type="file" name="file" id="file" class="form-control d-none" required>
                    <p id="file-name" class="small text-secondary mt-2">Sin archivos seleccionados</p>
                </div>
    
                <!-- Botón de acción -->
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-upload me-2"></i> Importar Datos
                </button>
            </form>
        </div>
    </div>
     <div class="card shadow-sm mb-4 text-center">
        <div class="card-body">
            <a href="<?php echo e(route('mapa.proyectos')); ?>" class="btn btn-danger">
                <i class="fas fa-map-marked-alt me-2"></i> Mapa global de proyectos
            </a>
        </div>
    </div>
    
    <!-- Script para mostrar nombre del archivo -->
    <script>
        document.getElementById('file').addEventListener('change', function(){
            const fileName = this.files.length ? this.files[0].name : "Sin archivos seleccionados";
            document.getElementById('file-name').textContent = fileName;
        });
    </script>
    
    <!-- Estilos adicionales -->
    <style>
        .upload-area {
            cursor: pointer; /* Cambia el cursor a mano */
        }
        .upload-area:hover {
            background-color: #f8f9fa; /* Efecto visual al pasar el mouse */
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }
    </style>



    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="card shadow-sm mb-4 text-center">
        <div class="card-body">
            <!-- Botón para ir al formulario de nuevo registro -->
            <a href="<?php echo e(route('proyectos.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Agregar nuevo proyecto
            </a>
            <a href="<?php echo e(route('plantilla.descargar')); ?>" class="btn btn-primary">
                <i class="fas fa-download"></i> Descargar Plantilla
            </a>
        </div>
    </div>


    

    <!-- Tabla de registros -->
    <div class="table-responsive mt-3 data-table-container">
    <!--Tabla para proyectos-->
    <table class="table data-table">
        <thead class="thead-custom">
            <tr>
                <th>Folio PPI</th>
                <th>CCT</th>
                <th>Módulo</th>
                <th>Municipio</th>
                <th>Nombre Proyecto</th>
                <th>Monto Inversión</th>
                <th>Inicio</th>
                <th>Término</th>
                <th>Empresa</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tbody-js">
            <?php $__empty_1 = true; $__currentLoopData = $registros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <!-- Fila principal visible solo en móvil -->
            <tr class="proyecto-row d-table-row d-md-none">
                <td colspan="8" class="proyecto-nombre position-relative" style="cursor: pointer;">
                    <strong>Proyecto</strong>
                    <div><?php echo e($row->nombre_proyecto); ?></div>
                    <div class="toggle-icon position-absolute top-0 end-0 p-2">
                        <i class="fas fa-chevron-down text-muted"></i>
                    </div>
                </td>
            </tr>

            <!-- Fila completa visible solo en escritorio -->
            <tr class="d-none d-md-table-row">
                <td><?php echo e($row->folio_ppi); ?></td>
                <td><?php echo e($row->cct); ?></td>
                <td><?php echo e($row->modulo); ?></td>
                <td><?php echo e($row->municipio); ?></td>
                <td><?php echo e($row->nombre_proyecto); ?></td>
                <td><?php echo e(number_format($row->monto_inversion, 2)); ?></td>
                <td><?php echo e($row->inicio); ?></td>
                <td><?php echo e($row->termino); ?></td>
                <td><?php echo e($row->empresa); ?></td>
                <td>
                    <div class="acciones-btns d-flex align-items-center gap-1 flex-nowrap">
                        <a href="<?php echo e(route('proyectos.detalle', $row->id)); ?>" class="btn btn-sm btn-info custom-tooltip">
                            <i class="fas fa-eye"></i>
                            <span class="tooltiptext">Ver Detalle</span>
                        </a>
                        <a href="<?php echo e(route('proyectos.edit', $row->id)); ?>" class="btn btn-sm btn-primary custom-tooltip">
                            <i class="fas fa-pen"></i>
                            <span class="tooltiptext">Editar</span>
                        </a>
                        <form action="<?php echo e(route('proyectos.destroy', $row->id)); ?>" method="POST"
                              onsubmit="return confirm('¿Seguro que quieres eliminar este proyecto?');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-sm btn-danger custom-tooltip">
                                <i class="fas fa-trash"></i>
                                <span class="tooltiptext">Eliminar</span>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>

            <!-- Detalles expandibles solo en móvil -->
            <tr class="d-table-row d-md-none">
                <td colspan="9">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h6 class="card-title mb-2">
                                <i class="fas fa-lightbulb text-warning me-2"></i>
                                <?php echo e($row->nombre_proyecto); ?>

                            </h6>
                            <p class="mb-1"><strong>Folio PPI:</strong> <?php echo e($row->folio_ppi); ?></p>
                            <p class="mb-1"><strong>CCT:</strong> <?php echo e($row->cct); ?></p>
                            <p class="mb-1"><strong>Municipio:</strong> <?php echo e($row->municipio); ?></p>
                            <p class="mb-1"><strong>Empresa:</strong> <?php echo e($row->empresa); ?></p>
                            <p class="mb-1"><strong>Monto:</strong> <?php echo e(number_format($row->monto_inversion, 2)); ?></p>
                            <span class="badge bg-warning text-dark">En revisión</span>
                            <div class="acciones-btns d-flex gap-2 mt-2">
                                <a href="<?php echo e(route('proyectos.detalle', $row->id)); ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('proyectos.edit', $row->id)); ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="<?php echo e(route('proyectos.destroy', $row->id)); ?>" method="POST"
                                      onsubmit="return confirm('¿Seguro que quieres eliminar este proyecto?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
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
<script>
document.querySelectorAll('.proyecto-row').forEach(row => {
    row.addEventListener('click', () => {
        const detalle = row.nextElementSibling;
        detalle.classList.toggle('d-none');
        detalle.classList.toggle('d-table-row');

        const icon = row.querySelector('.toggle-icon i');
        icon.classList.toggle('fa-chevron-down');
        icon.classList.toggle('fa-chevron-up');
    });
});

</script>


</div>


<?php if($registros->hasPages()): ?>
    <nav class="pagination-container" aria-label="Navegación de páginas">
        <?php echo e($registros->links('vendor.pagination.mi_paginacion')); ?>

    </nav>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/datosProyectos.blade.php ENDPATH**/ ?>