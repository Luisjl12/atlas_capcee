

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <div class="dashboard-welcome card-header-custom">
        <h2><i class="fas fa-tachometer-alt"></i> Panel Principal</h2>
    </div>

    <div class="card-body-custom pa-4">
        <p class="lead">¡Bienvenido, <strong><?php echo e(session('nombre_completo')); ?>!</strong></p>
        <p>Has iniciado sesión como <strong>DIRECTOR</strong></p>
        <div class="separador"></div>
        <h6>Acciones Disponibles:</h6>
        
        <nav class="dashboard-nav">
            <div class="contenedor-acciones">
                
                <style>
                    .accion-card.guinda {
                        background: linear-gradient(135deg, #691C32 0%, #9F2241 100%);
                        color: white !important;
                        border: none;
                    }
                    .accion-card.guinda:hover {
                        background: linear-gradient(135deg, #9F2241 0%, #691C32 100%);
                        transform: translateY(-3px);
                        box-shadow: 0 8px 15px rgba(105, 28, 50, 0.4);
                    }
                </style>

                <div class="columna-acciones">
                    <a href="<?php echo e(route('planteles.index')); ?>" class="accion-card red">
                        <i class="fas fa-school"></i> Gestionar Planteles
                    </a>

                    <a href="<?php echo e(route('mapa.vista')); ?>" class="accion-card red">
                        <i class="fas fa-map"></i> Mapa de planteles
                    </a>
                </div>

                <div class="columna-acciones">
                    <a href="<?php echo e(route('infraescolar.director')); ?>" class="accion-card guinda">
                        <i class="fas fa-chart-line"></i> Reportes financieros
                    </a>

                    <a href="<?php echo e(route('busqueda.avanzada')); ?>" class="accion-card green">
                        <i class="fas fa-search"></i> Buscador Avanzado
                    </a>
                </div>

            </div>
        </nav>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home1/bcecacef/atlasinfraescolarpueblaa.online/resources/views/directorReportes.blade.php ENDPATH**/ ?>