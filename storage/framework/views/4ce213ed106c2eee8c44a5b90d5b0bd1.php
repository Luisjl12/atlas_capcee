
<?php $__env->startSection('content'); ?>
<!--Vista del dashboard del analista -->

<div class="container mt-4">
    <div class="dashboard-welcome card-header-custom">
        <h2><i class="fas fa-tachometer-alt"></i> Panel Principal</h2>
    </div>

    <div class="card-body-custom pa-4">
        <p class="lead">¡Bienvenido,<strong> <?php echo e(session('nombre_completo')); ?>!</strong></p>
        <p>Has iniciado sesión como <strong>ANALISTA</strong></p>
        <div class="separador"></div>
        <h6>Acciones Disponibles:</h6>
        <nav class="dashboard-nav">
            <div class="contenedor-acciones">
                <div class="columna-acciones">
                    <a href="<?php echo e(route('reportes.index')); ?>" class="accion-card yellow">
                        <i class="fas fa-chart-bar"></i> Gestión de reportes
                    </a>
                    <a href="<?php echo e(route('infraestructura.form')); ?>" class="accion-card yellow">
                        <i class="fas fa-chart-bar"></i> Comparar Infraestructura
                    </a>
                </div>
                <div class="columna-acciones">
                    <a href="<?php echo e(route('busqueda.avanzada')); ?>" class="accion-card green">
                        <i class="fas fa-search"></i> Buscador Avanzado
                    </a>
                    <a href="<?php echo e(route('planteles.index')); ?>" class="accion-card red">
                        <i class="fas fa-school"></i> Gestionar Planteles
                    </a>
                </div>
                <div class="columna-acciones">
                    <a href="<?php echo e(route('importarDatos.show')); ?>" class="accion-card green">
                        <i class="fas fa-upload"></i> Importar Datos
                    </a>
                </div>
            </div>
        </nav>
    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/analista.blade.php ENDPATH**/ ?>