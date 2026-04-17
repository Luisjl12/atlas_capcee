


<?php $__env->startSection('title', 'Editar Protección Civil'); ?>

<?php $__env->startSection('content'); ?>
<?php
$detalle = $plantel->detalleProteccionCivil;
?>

<div class="container mt-4">
    
    <div class="card-header">
         <a href="<?php echo e(route('planteles.show', $plantel->id)); ?>" class="btn-icon-only">
            <i class="fas fa-arrow-left "></i>
            <h3><i class="fas fa-hard-hat"></i> Editar Protección Civil - <?php echo e($plantel->nombre_escuela ?? 'Plantel desconocido'); ?></h3>
        </a>

    </div>

    
    <?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    
    <?php if(session('success')): ?>
    <div class="alert alert-success mt-2"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="form-navigation nav-tabs-text mb-3">
        <span class="nav-tab active" data-step="0">Seguridad y Protección</span>
        <span class="nav-tab" data-step="1">Brigadas y Simulacros</span>
        <span class="nav-tab" data-step="2">Observaciones Generales</span>
    </div>

    <!--Tab panes--->
    <!--Servicios Basicos --->
    <div class="form-ficha-base">
        <form action="<?php echo e(route('detalle_proteccion_civil.store', $plantel->cct)); ?>" method="POST" class="form-ficha-base">
            <?php echo csrf_field(); ?>

            <input type="hidden" name="seccion" value="seguridad">
            <div class="form-section step-section" data-step="0">

                <h4>Documentación y Equipo</h4>
                <div class="row">
                    <div class="form-check col-md-4">
                        <!-- Hidden para enviar 0 si no se marca -->
                        <input type="hidden" name="programa_interno_pc" value="0">
                        <input class="form-check-input" type="checkbox" name="programa_interno_pc" value="1"
                            <?php echo e(old('programa_interno_pc', $detalle?->programa_interno_pc ?? 0) ? 'checked' : ''); ?>>
                        <label>Tiene Programa Interno de PC</label>
                    </div>

                    <div class="form-check col-md-4">
                        <input type="hidden" name="alarma_sismica" value="0">
                        <input class="form-check-input" type="checkbox" name="alarma_sismica" value="1"
                            <?php echo e(old('alarma_sismica', $detalle?->alarma_sismica ?? 0) ? 'checked' : ''); ?>>
                        <label>Tiene Alarma Sísmica</label>
                    </div>

                    <div class="form-check col-md-4">
                        <input type="hidden" name="alarma_sismica_funcional" value="0">
                        <input class="form-check-input" type="checkbox" name="alarma_sismica_funcional" value="1"
                            <?php echo e(old('alarma_sismica_funcional', $detalle?->alarma_sismica_funcional ?? 0) ? 'checked' : ''); ?>>
                        <label>Alarma Sísmica Funcional</label>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="form-group col-md-4">
                        <label>Señalética:</label>
                        <select name="senaletica_estado" class="form-control">
                            <?php $__currentLoopData = $estadosProteccionCivil; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $valor => $texto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($valor); ?>" <?php echo e(old('senaletica_estado', $detalle?->senaletica_estado) == $valor ? 'selected' : ''); ?>>
                                <?php echo e($texto); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label># Extintores:</label>
                        <input type="number" name="extintores_cantidad" class="form-control" min="0"
                            value="<?php echo e(old('extintores_cantidad', $detalle?->extintores_cantidad ?? 0)); ?>">
                    </div>

                    <div class="form-group col-md-4">
                        <label># Extintores Vigentes:</label>
                        <input type="number" name="extintores_vigentes" class="form-control" min="0"
                            value="<?php echo e(old('extintores_vigentes', $detalle?->extintores_vigentes ?? 0)); ?>">
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="form-group col-md-6">
                        <label>Fecha de Última Recarga de Extintores:</label>
                        <input type="date" name="extintores_ultima_recarga" class="form-control"
                            value="<?php echo e(old('extintores_ultima_recarga', $detalle?->extintores_ultima_recarga)); ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Fecha de Último Simulacro / Actualización de Programa:</label>
                        <input type="date" name="programa_interno_pc_fecha" class="form-control"
                            value="<?php echo e(old('programa_interno_pc_fecha', $detalle?->programa_interno_pc_fecha)); ?>">
                    </div>
                </div>
                <button type="submit" class="btn-custom btn-primary">
                    <i class="fas fa-save"></i> Guardar Datos
                </button>

            </div>
        </form>

        <form action="<?php echo e(route('detalle_proteccion_civil.store', $plantel->cct)); ?>" method="POST" class="form-ficha-base">
            <?php echo csrf_field(); ?>

            <input type="hidden" name="seccion" value="brigadas">
            <div class="form-section step-section" data-step="1">

                <h4>Capital Humano</h4>
                <div class="row">
                    <div class="form-check col-md-6">
                        <input type="hidden" name="brigadas_conformadas" value="0">
                        <input class="form-check-input" type="checkbox" name="brigadas_conformadas" value="1"
                            <?php echo e(old('brigadas_conformadas', $detalle?->brigadas_conformadas ?? 0) ? 'checked' : ''); ?>>
                        <label>Brigadas de Protección Civil Conformadas</label>
                    </div>

                    <div class="form-check col-md-6">
                        <input type="hidden" name="botiquin_existencia" value="0">
                        <input class="form-check-input" type="checkbox" name="botiquin_existencia" value="1"
                            <?php echo e(old ('botiquin_existencia',  $detalle?->botiquin_existencia ?? 0) ? 'checked' : ''); ?>>
                        <label>Cuenta con Botiquín de Primeros Auxilios</label>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="form-group col-md-6">
                        <label>Estado del Botiquín:</label>
                        <select name="botiquin_estado" class="form-control">
                            <option value="INCOMPLETO" <?php echo e(old('botiquin_estado', $detalle?->botiquin_estado) == 'INCOMPLETO' ? 'selected' : ''); ?>>INCOMPLETO</option>
                            <option value="BASICO" <?php echo e(old('botiquin_estado', $detalle?->botiquin_estado) == 'BASICO' ? 'selected' : ''); ?>>BÁSICO</option>
                            <option value="COMPLETO" <?php echo e(old('botiquin_estado', $detalle?->botiquin_estado) == 'COMPLETO' ? 'selected' : ''); ?>>COMPLETO</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label># Simulacros en el último año:</label>
                        <input type="number" name="simulacros_ultimo_anio" class="form-control" min="0"
                            value="<?php echo e(old('simulacros_ultimo_anio', $detalle?->simulacros_ultimo_anio ?? 0)); ?>">
                    </div>
                </div>
                <!-- Nuevo campo: Estado del techado -->
                <div class="form-group col-md-6">
                    <label>Estado del Techado:</label>
                    <p class="form-control-plaintext">
                        <?php echo e($detalle?->estado_techados ?? 'No registrado'); ?>

                    </p>
                </div>
                <button type="submit" class="btn-custom btn-primary">
                    <i class="fas fa-save"></i> Guardar Datos
                </button>
            </div>
        </form>

        <form action="<?php echo e(route('detalle_proteccion_civil.store', $plantel->cct)); ?>" method="POST" class="form-ficha-base">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="seccion" value="observaciones">
            <div class="form-section step-section" data-step="2">
                
                    <h4>Observaciones Generales</h4>
                    <div class="form-group">
                        <label for="observaciones">Observaciones Adicionales de Protección Civil:</label>
                        <textarea name="observaciones" id="observaciones" class="form-control" rows="3"><?php echo e(old('observaciones', $detalle?->observaciones)); ?></textarea>
                    </div>
                
                <button type="submit" class="btn-custom btn-primary">
                    <i class="fas fa-save"></i> Guardar Datos
                </button>
            </div>
        </form>
    </div>

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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home1/bcecacef/atlasinfraescolarpueblaa.online/resources/views/planteles/edit_proteccionCivil.blade.php ENDPATH**/ ?>