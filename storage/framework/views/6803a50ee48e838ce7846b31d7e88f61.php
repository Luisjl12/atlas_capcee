
<?php $__env->startSection('content'); ?>
<div class="dashboard-welcome card-header-custom">
        <h2><i class="fas fa-tachometer-alt"></i> Panel Principal</h2>
    </div>

    <div class="card-body-custom pa-4">
        <p class="lead">¡Bienvenido,<strong> <?php echo e(session('nombre_completo')); ?>!</strong></p>
        <p>Has iniciado sesión como <strong>Usuario de Proyectos Especiales</strong></p>
        <div class="separador"></div>
        <h6><strong>ACCIONES DISPONIBLES:</strong></h6>
        <nav class="dashboard-nav">
            <div class="contenedor-acciones">
                <div class="columna-acciones">
                    <a href="<?php echo e(route('planteles.index')); ?>" class="accion-card red">
                        <i class="fas fa-school"></i> Gestionar Planteles
                    </a>
                    <a href="<?php echo e(route('mapa.vista')); ?>" class="accion-card red">
                        <i class="fas fa-map"></i> Mapa de planteles
                    </a>
                </div>
                <div class="columna-acciones">
                    <a href="<?php echo e(route('reportes.index')); ?>" class="accion-card yellow">
                        <i class="fas fa-chart-bar"></i> Panel de Reportes
                    </a>
                    <a href="<?php echo e(route('mapa.escuelas100.general')); ?>" class="accion-card yellow">
                        <i class="fas fa-map-marked-alt"></i> Mapa Escuelas al 100
                    </a>
                    <a href="<?php echo e(route('seguimiento-proyectos')); ?>" class="accion-card yellow">
                        <i class="fas fa-map-marked-alt"></i> Seguimiento de proyectos
                    </a>
                </div>
                <div class="columna-acciones">
                    <a href="<?php echo e(route('perfil')); ?>" class="accion-card green">
                        <i class="fas fa-user-edit"></i> Mi Perfil
                    </a>
                    <a href="<?php echo e(route('proyectos.index')); ?>" class="accion-card green">
                        <i class ="fas fa-lightbulb"></i> Proyectos CAPCEE
                    </a>
                </div>

            </div>
        </nav>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/proyectosEspeciales.blade.php ENDPATH**/ ?>