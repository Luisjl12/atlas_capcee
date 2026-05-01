

<?php $__env->startSection('content'); ?>

<div class="card-header">
    <a href="<?php echo e(route('proyectos.index')); ?>" class="btn-icon-only">
        <i class="fas fa-arrow-left me-2"></i>
        <i class="fas fa-school me-2"></i>
        <h3 class="mb-0">Folio PPI: <?php echo e($proyecto->folio_ppi); ?></h3>
    </a>
</div>


<div class="form-navigation nav-tabs-text mb-3">
    <span class="nav-tab active" data-step="0" data-bs-toggle="tab" data-bs-target="#tab-0" role="tab">I. Ubicación</span>
    <span class="nav-tab" data-step="1" data-bs-toggle="tab" data-bs-target="#tab-1" role="tab">II. Avances</span>
    <span class="nav-tab" data-step="2" data-bs-toggle="tab" data-bs-target="#tab-2" role="tab">III. Contrato</span>
    <span class="nav-tab" data-step="3" data-bs-toggle="tab" data-bs-target="#tab-3" role="tab">IV. Estatus</span>
    <span class="nav-tab" data-step="4" data-bs-toggle="tab" data-bs-target="#tab-4" role="tab">V. Notificacion</span>
</div>



<div class="tab-content">

    <!-- Ubicación -->
    <div class="tab-pane fade show active" id="tab-0">
        <h4 class="bg-white text-dark p-2 rounded shadow-sm">Ubicación</h4>
        <div id="map" style="height: 400px; width: 100%;"></div>
    </div>

    <!-- Avances -->
    <div class="tab-pane fade mb-4" id="tab-1">  
        <h4 class="bg-white text-dark p-2 rounded shadow-sm">Avances del Proyecto</h4>

        <?php if($proyecto): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <p><strong>Avance Financiero Programado:</strong> <?php echo e($proyecto->av_fin_prog); ?>%</p>
                    <p><strong>Avance Financiero Real:</strong> <?php echo e($proyecto->av_fin_real); ?>%</p>
                    <p><strong>Avance Físico Programado:</strong> <?php echo e($proyecto->av_fis_prog); ?>%</p>
                    <p><strong>Avance Físico Real:</strong> <?php echo e($proyecto->av_fis_real); ?>%</p>
                </div>
            </div>
        <?php else: ?>
            <p>No hay información de avances disponible para este proyecto.</p>
        <?php endif; ?>
    </div>


    <div class="tab-pane fade mb-4" id="tab-2">
        <h4 class="bg-white text-dark p-2 rounded shadow-sm">Contrato</h4>
        <?php if($proyecto): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <p><strong>Empresa:</strong> <?php echo e($proyecto->empresa); ?></p>
                    <p><strong>Número de Contrato:</strong> <?php echo e($proyecto->no_contrato); ?></p>
                    <p><strong>Monto Contrato:</strong> <?php echo e(number_format($proyecto->monto_contratado, 2)); ?></p>
                    <p><strong>Plazo de Ejecución en Días:</strong> <?php echo e($proyecto->plazo_ejec_dias); ?></p>
                </div>
            </div>
        <?php else: ?> 
            <p>No hay información de contratos para este proyecto</p>
        <?php endif; ?>
    </div>

    <div class="tab-pane fade mb-4" id="tab-3">
        <h4 class="bg-white text-dark p-2 rounded shadow-sm">Estatus</h4>
        <?php if($proyecto): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <p><strong>Estatus General:</strong> <?php echo e($proyecto->estatus_general); ?></p>
                    <p><strong>Estatus Finanzas:</strong> <?php echo e($proyecto->estatus_finanzas); ?></p>
                    <p><strong>Estatus del Administrador:</strong> <?php echo e($proyecto->estatus_admin); ?></p>
                </div>
            </div>
        <?php else: ?>
            <p>No hay Estatus por mostrar</p>
        <?php endif; ?>
    </div>


    <div class="tab-pane fade mb-4" id="tab-4">
        <h4 class="bg-white text-dark p-2 rounded shadow-sm">Notificación</h4>
        <?php if($proyecto): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <p><strong>Usuario Notificado:</strong> <?php echo e($proyecto->usuario_notif); ?></p>
                    <p><strong>Fecha Notificación:</strong> <?php echo e($proyecto->fecha_notif); ?></p>
                </div>
            </div>
        <?php else: ?>
            <p>No hay Notificaciones por mostrar</p>
        <?php endif; ?>
    </div>

</div>


<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const tabTriggers = document.querySelectorAll('[data-bs-toggle="tab"]');

    tabTriggers.forEach(function (trigger) {
        trigger.addEventListener('click', function () {
            const target = document.querySelector(this.getAttribute('data-bs-target'));

            document.querySelectorAll('.tab-pane').forEach(function (pane) {
                pane.classList.remove('show', 'active');
            });

            tabTriggers.forEach(function (t) {
                t.classList.remove('active');
            });

            target.classList.add('show', 'active');
            trigger.classList.add('active');

            if (trigger.getAttribute('data-bs-target') === '#tab-0') {
                setTimeout(() => map.invalidateSize(), 10);
            }
        });
    });

    var lat = <?php echo e($proyecto->latitud); ?>;
    var lng = <?php echo e($proyecto->longitud); ?>;

    var map = L.map('map').setView([lat, lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    L.marker([lat, lng]).addTo(map)
        .bindPopup("<b><?php echo e($proyecto->nombre_proyecto); ?></b><br><?php echo e($proyecto->municipio); ?>")
        .openPopup();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/verDetallesProyecto.blade.php ENDPATH**/ ?>