
<?php $__env->startSection('content'); ?>
<!--Vista del dashboard del admistrador -->
<!--Cabecera y footers estan el layout-->

    <div class="dashboard-welcome card-header-custom">
        <h2><i class="fas fa-tachometer-alt"></i> Panel Principal</h2>
    </div>

    <div class="card-body-custom pa-4">
        <p class="lead">¡Bienvenido,<strong> <?php echo e(session('nombre_completo')); ?>!</strong></p>
        <p>Has iniciado sesión como <strong>ADMINISTRADOR</strong></p>
        <div class="separador"></div>
        <h6><strong>ACCIONES DISPONIBLES:</strong></h6>
        <nav class="dashboard-nav">
            <div class="contenedor-acciones">
                <div class="columna-acciones">
                    <a href="<?php echo e(route('gestion.usuarios')); ?>" class="accion-card red">
                        <i class="fas fa-users-cog"></i> Gestión de Usuarios
                    </a>
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
                    <a href="<?php echo e(route('panel.supervision')); ?>" class="accion-card yellow">
                        <i class="fas fa-tasks"></i> Panel de Supervisión
                    </a>
                    <a href="<?php echo e(route('importarDatos.show')); ?>" class="accion-card yellow">
                        <i class="fas fa-upload"></i> Importar Datos
                    </a>
                </div>
                <div class="columna-acciones">
                    <a href="<?php echo e(route('busqueda.avanzada')); ?>" class="accion-card green">
                        <i class="fas fa-search"></i> Buscador Avanzado
                    </a>
                    <a href="<?php echo e(route('perfil')); ?>" class="accion-card green">
                        <i class="fas fa-user-edit"></i> Mi Perfil
                    </a>
                    <a href="<?php echo e(route('historial.index')); ?>" class="accion-card green">
                        <i class="fas fa-history"></i> Historial de Modificaciones
                    </a>
                </div>

            </div>
        </nav>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/admin.blade.php ENDPATH**/ ?>