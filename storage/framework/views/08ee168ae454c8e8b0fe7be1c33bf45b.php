

<?php $__env->startSection('title', 'Agregar Plantel'); ?>

<?php $__env->startSection('content'); ?>


    <div class="card-header">
        <a href="<?php echo e(route('planteles.show', $plantel->id)); ?>" class="btn-icon-only">
            <i class="fas fa-arrow-left "></i>
            <h3><i class="fas fa-tint"></i> Editar Servicios: <?php echo e($plantel->nombre_escuela); ?>

            <small class="text-muted">(CCT: <?php echo e($plantel->cct); ?>)</small></h3>
        </a>
    </div>
    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    <?php endif; ?>


    
    <?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    
    <div class="form-navigation nav-tabs-text mb-3">
        <span class="nav-tab active" data-step="0">Hidrosanitaria</span>
        <span class="nav-tab" data-step="1">Servicios Basicos</span>

    </div>

    <!--Tab panes--->
    <!--Servicios Basicos --->
    <div class="form-ficha-base">

        <form action="<?php echo e(route('infraestructura.update_hidrosanitario', $plantel->cct)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="form-section step-section" data-step="0">
                <h4><i class="fas fa-faucet"></i> Hidrosanitaria</h4>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Fuente de Agua:</label>
                        <input type="text" name="fuente_agua" class="form-control"
                            value="<?php echo e(old('fuente_agua', $hidrosanitario->fuente_agua ?? '')); ?>">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Almacenamiento de Agua:</label>
                        <input type="text" name="almacenamiento_agua" class="form-control"
                            placeholder="Ej: 1 Cisterna 1000L"
                            value="<?php echo e(old('almacenamiento_agua', $hidrosanitario->almacenamiento_agua ?? '')); ?>">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Tipo de Drenaje:</label>
                        <input type="text" name="tipo_drenaje" class="form-control"
                            placeholder="Ej: Red pública, Fosa séptica"
                            value="<?php echo e(old('tipo_drenaje', $hidrosanitario->tipo_drenaje ?? '')); ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-3">
                        <label># WC Hombres:</label>
                        <input type="number" name="sanitarios_hombres_wc" class="form-control" min="0"
                            value="<?php echo e(old('sanitarios_hombres_wc', $hidrosanitario->sanitarios_hombres_wc ?? 0)); ?>">
                    </div>

                    <div class="form-group col-md-3">
                        <label># Lavabos Hombres:</label>
                        <input type="number" name="sanitarios_hombres_lavabos" class="form-control" min="0"
                            value="<?php echo e(old('sanitarios_hombres_lavabos', $hidrosanitario->sanitarios_hombres_lavabos ?? 0)); ?>">
                    </div>

                    <div class="form-group col-md-3">
                        <label># WC Mujeres:</label>
                        <input type="number" name="sanitarios_mujeres_wc" class="form-control" min="0"
                            value="<?php echo e(old('sanitarios_mujeres_wc', $hidrosanitario->sanitarios_mujeres_wc ?? 0)); ?>">
                    </div>

                    <div class="form-group col-md-3">
                        <label># Lavabos Mujeres:</label>
                        <input type="number" name="sanitarios_mujeres_lavabos" class="form-control" min="0"
                            value="<?php echo e(old('sanitarios_mujeres_lavabos', $hidrosanitario->sanitarios_mujeres_lavabos ?? 0)); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Observaciones Hidrosanitarias:</label>
                    <textarea name="observaciones" class="form-control" rows="2"><?php echo e(old('observaciones', $hidrosanitario->observaciones ?? '')); ?></textarea>
                </div>

                <button type="submit" class="btn-custom btn-primary"><i class="fas fa-save"></i> Guardar Hidrosanitario</button>

            </div>
        </form>

        <!--Hidrosanitario--->
        <form action="<?php echo e(route('infraestructura.update_servicios', $plantel->cct)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="form-section step-section" data-step="1">

                <h4><i class="fas fa-bolt"></i> Servicios Básicos</h4>

                <div class="row">
                    <div class="form-check col-md-3">
                        <input type="hidden" name="electricidad_contrato" value="0">
                        <input class="form-check-input" type="checkbox" name="electricidad_contrato" value="1"
                            <?php echo e(old('electricidad_contrato', $servicio->electricidad_contrato ?? 0) ? 'checked' : ''); ?>>
                        <label> Contrato de Electricidad</label>
                    </div>

                    <div class="form-check col-md-3">
                        <input type="hidden" name="telefonia_fija" value="0">
                        <input class="form-check-input" type="checkbox" name="telefonia_fija" value="1"
                            <?php echo e(old('telefonia_fija', $servicio->telefonia_fija ?? 0) ? 'checked' : ''); ?>>
                        <label> Línea Telefónica Fija</label>
                    </div>

                    <div class="form-check col-md-3">
                        <input type="hidden" name="internet_acceso" value="0">
                        <input class="form-check-input" type="checkbox" name="internet_acceso" value="1"
                            <?php echo e(old('internet_acceso', $servicio->internet_acceso ?? 0) ? 'checked' : ''); ?>>
                        <label> Acceso a Internet</label>
                    </div>
                    <div class="form-group col-md-6">
                     <label>Tiene computadoras:</label>
                     <p class="form-control-plaintext">
                      <?php echo e($servicio->tiene_computadoras ?? 'No registrado'); ?>

                    </p>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="form-group col-md-6">
                        <label>Tipo de Gas:</label>
                        <input type="text" name="gas_tipo" class="form-control"
                            placeholder="Ej: LP Estacionario, Cilindros, No tiene"
                            value="<?php echo e(old('gas_tipo', $servicio->gas_tipo ?? '')); ?>">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Tipo de Internet:</label>
                        <input type="text" name="internet_tipo" class="form-control"
                            placeholder="Ej: Fibra Óptica, ADSL, Satelital"
                            value="<?php echo e(old('internet_tipo', $servicio->internet_tipo ?? '')); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Observaciones de Servicios:</label>
                    <textarea name="observaciones" class="form-control" rows="2"><?php echo e(old('observaciones', $servicio->observaciones ?? '')); ?></textarea>
                </div>
                

                <button type="submit" class="btn-custom btn-primary"><i class="fas fa-save"></i> Guardar Servicios</button>

            </div>

        </form>
    </div>

<?php $__env->startPush('scripts'); ?>
<!--Script para navegacion de tabs-->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const secciones = document.querySelectorAll('.step-section');
        const pestañas = document.querySelectorAll('.nav-tab');

        function mostrarSeccion(numero) {
            for (let i = 0; i < secciones.length; i++) {
                secciones[i].classList.toggle('active', i === numero);
                pestañas[i].classList.toggle('active', i === numero);
            }
        }

        // Obtener el parámetro ?step de la URL
        const params = new URLSearchParams(window.location.search);
        const paso = parseInt(params.get('step')) || 0;

        // Mostrar la sección correspondiente al cargar
        mostrarSeccion(paso);

        // Cuando se hace clic en una pestaña
        for (let i = 0; i < pestañas.length; i++) {
            pestañas[i].addEventListener('click', function() {
                mostrarSeccion(i);
                // Opcional: actualizar la URL sin recargar
                const nuevaUrl = new URL(window.location);
                nuevaUrl.searchParams.set('step', i);
                window.history.replaceState({}, '', nuevaUrl); // Esto evita que se recargue
            });
        }
    });
</script>

<?php $__env->stopPush(); ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/planteles/editar_infraestructura.blade.php ENDPATH**/ ?>