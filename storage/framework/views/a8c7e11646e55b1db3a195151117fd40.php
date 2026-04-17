

<?php $__env->startSection('title', 'Listado de Planteles'); ?>



<?php $__env->startSection('content'); ?>

    <?php
    use App\Helpers\RoleHelper;
    ?>

    <div class="card-header-custom">
        <a href="<?php echo e(RoleHelper::gestionPlanteles(session('role_id'))); ?>" class="btn-icon-only">
            <i class="fas fa-arrow-left"></i>
            <h2><i class="fas fa-school"></i> Gestionar Planteles</h2>
        </a>

        <a href="<?php echo e(route('planteles.create')); ?>" class="btn-custom btn-success">
            <i class="fas fa-plus"></i> Registrar Nuevo Plantel
        </a>
    </div>

    <?php if(session('success')): ?>
    <div class="alert alert-success mt-2"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="buscador-container d-flex align-items-center gap-3 mb-4">
        
        <div class="buscador-input-wrapper">
            <i class="fas fa-search"></i>
            <input type="text" id="buscar" class="form-control" placeholder="Buscar por CCT o Nombre...">
        </div>

        
        <form action="<?php echo e(route('planteles.filtrar')); ?>" method="GET">
            <select name="estatus" class="form-select" style="background-color: #f8f9fa;" onchange="this.form.submit()">
                <option value="">Todos</option>
                <option value="ACTIVO" <?php echo e(request('estatus') == 'ACTIVO' ? 'selected' : ''); ?>>Activo</option>
                <option value="INACTIVO" <?php echo e(request('estatus') == 'INACTIVO' ? 'selected' : ''); ?>>Inactivo</option>
                <option value="EN_REVISION" <?php echo e(request('estatus') == 'EN_REVISION' ? 'selected' : ''); ?>>En revisión</option>
            </select>
        </form>

        
        <button type="button" class="btn-limpiador" onclick="limpiarBusqueda()">
            <i class="fas fa-eraser"></i>
        </button>
    </div>


    <div id="resultados">
        <?php echo $__env->make('partials.lista', ['planteles' => $planteles], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

<!--Mostrar modal para confirmacion de eliminar plantel-->
<div id="modalConfirmacion" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <h5><i class="fas fa-exclamation-triangle"></i> Confirmación</h5>
        <p id="mensajeConfirmacion">¿Estás seguro de continuar?</p>
        <div class="modal-actions">
            <button id="btnCancelar" class="btn-custom btn-cancelar">Cancelar</button>
            <a id="btnEliminar" class="btn-custom btn-danger">Eliminar</a>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    const RUTA_BUSCAR_PLANTELES = "<?php echo e(route('planteles.buscar')); ?>";
</script>


<script>
    const CSRF_TOKEN = "<?php echo e(csrf_token()); ?>";
</script>
<script src="<?php echo e(asset('js/modal-confirmacion.js')); ?>"></script>



<!--Limpia los datos de la accion "ver"-->
<script>
    localStorage.removeItem('pasoActivo');
</script>

<script>
    function limpiarBusqueda() {
        // Limpiar el campo de búsqueda si existe
        const inputBusqueda = document.getElementById('buscar');
        if (inputBusqueda) {
            inputBusqueda.value = '';
        }

        // Limpiar el filtro de estatus si existe
        const selectEstatus = document.querySelector('select[name="estatus"]');
        if (selectEstatus) {
            selectEstatus.value = '';
        }

        // Enviar el formulario de filtro (si existe)
        const formulario = selectEstatus?.form;
        if (formulario) {
            formulario.submit();
        }
    }
</script>

<!--Tabla expandible para versiones moviles--->
<script>
function inicializarDesplieguePlanteles() {
    console.log('[DEBUG] Iniciando despliegue de planteles...');

    const tbody = document.querySelector('#tbody-js');
    if (!tbody) {
        console.warn('[DEBUG] No se encontró el tbody con id "tbody-js".');
        return;
    }

    tbody.addEventListener('click', function (e) {
        const filaNombre = e.target.closest('.plantel-nombre');
        if (!filaNombre) return;

        const filaActual = filaNombre.closest('tr');
        let filaDetalle = filaActual;

        // Buscar hacia adelante hasta encontrar la fila .plantel-detalle
        do {
            filaDetalle = filaDetalle.nextElementSibling;
        } while (filaDetalle && !filaDetalle.classList.contains('plantel-detalle'));

        if (filaDetalle) {
            filaDetalle.classList.toggle('d-none');
            console.log('[DEBUG] Toggle aplicado a fila de detalle:', filaDetalle);

            const icono = filaNombre.querySelector('.toggle-icon i');
            if (icono) {
                icono.classList.toggle('fa-chevron-down');
                icono.classList.toggle('fa-chevron-up');
                console.log('[DEBUG] Ícono actualizado.');
            } else {
                console.warn('[DEBUG] No se encontró el ícono dentro de .toggle-icon.');
            }
        } else {
            console.warn('[DEBUG] No se encontró la fila de detalle esperada.');
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    console.log('[DEBUG] DOM completamente cargado.');
    inicializarDesplieguePlantelesDebug();
});

</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    const inputBuscar = document.getElementById('buscar');
    const contenedorResultados = document.getElementById('resultados');

    inicializarDesplieguePlanteles();

    if (inputBuscar && contenedorResultados && typeof RUTA_BUSCAR_PLANTELES !== 'undefined') {
        inputBuscar.addEventListener('keyup', function () {
            const buscar = this.value;

            fetch(`${RUTA_BUSCAR_PLANTELES}?buscar=${encodeURIComponent(buscar)}`)
                .then(response => response.json())
                .then(data => {
                    contenedorResultados.innerHTML = data.html;
                    inicializarDesplieguePlanteles(); 
                })
                .catch(error => console.error('Error en la búsqueda:', error));
        });
    }
});
</script>

<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/planteles/index.blade.php ENDPATH**/ ?>