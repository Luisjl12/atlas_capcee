

<?php $__env->startSection('title', 'Ubicación del Plantel'); ?>

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="fas fa-map-marked-alt text-danger"></i> Ubicación Geográfica</h2>
        <a href="<?php echo e(url()->previous()); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Volver a la tabla
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div id="map" style="height: 600px; width: 100%; border-radius: 8px; z-index: 1;"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Pasamos los datos de PHP (Laravel) a JavaScript
    const plantelData = {
        lat: <?php echo e($escuela->latitud); ?>,
        lng: <?php echo e($escuela->longitud); ?>,
        nombre: "<?php echo htmlspecialchars($escuela->plantel, ENT_QUOTES); ?>", // htmlspecialchars evita que comillas rompan el JS
        cct: "<?php echo e($escuela->cct); ?>"
    };

    // 2. Tu Script exacto de inicialización
    var map = L.map('map').setView([plantelData.lat, plantelData.lng], 16); // Zoom 16 para verlo más de cerca

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    L.marker([plantelData.lat, plantelData.lng])
        .addTo(map)
        .bindPopup(
            "<div style='text-align: center;'>" +
            "<h6 style='color: #691C32; font-weight: bold; margin-bottom: 5px;'>" + plantelData.nombre + "</h6>" +
            "<b>CCT:</b> " + plantelData.cct + "<br>" +
            "<small><b>Lat:</b> " + plantelData.lat + " | <b>Lon:</b> " + plantelData.lng + "</small>" +
            "</div>"
        ).openPopup(); // .openPopup() hace que el globito se abra solito al entrar

    // 3. Recalcular tamaño (Si usas pestañas d-none como en tu código original)
    const tabMapa = document.querySelector('[data-step="6"]');
    if(tabMapa) {
        const observer = new MutationObserver(() => {
            if (!tabMapa.classList.contains('d-none')) {
                setTimeout(() => map.invalidateSize(), 300);
            }
        });
        observer.observe(tabMapa, { attributes: true, attributeFilter: ['class'] });
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/reportes/mapa_plantel.blade.php ENDPATH**/ ?>