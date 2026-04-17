

<?php $__env->startSection('title', 'Agregar Plantel'); ?>

<?php $__env->startSection('content'); ?>

    <div class="card-header">
        <a href="<?php echo e(route('planteles.index')); ?>" class="btn-icon-only">
            <i class="fas fa-arrow-left "></i>
           <h3><i class="fas fa-clipboard-list"></i> Registrar Nuevo Plantel</h3>
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

    
    <div class="form-navigation nav-tabs-text mb-3">
        <span class="nav-tab" data-step="0">I. Identificación</span>
        <span class="nav-tab" data-step="1">II. Ubicación</span>
        <span class="nav-tab" data-step="2">III. Contacto</span>
        <span class="nav-tab" data-step="3">IV. Accesibilidad</span>
        <span class="nav-tab" data-step="4">V. Usuarios</span>
        <span class="nav-tab" data-step="5">VI. Estatus</span>
    </div>

    <!--Tab panes--->
    <form action="<?php echo e(route('planteles.store')); ?>" method="POST" class="needs-validation form-ficha-base">
        <?php echo csrf_field(); ?>
        <div class="form-section step-section" data-step="0">
            <h4>I. Datos de Identificación</h4>
            <div class="row">
                <div class="col-md-4">
                    <label for="cct" class="form-label">CCT:</label>
                    <input type="text" class="form-control" name="cct" value="<?php echo e(old('cct')); ?>" required>
                </div>
                <div class="col-md-8">
                    <label for="nombre_escuela" class="form-label">Nombre de la Escuela:</label>
                    <input type="text" class="form-control" name="nombre_escuela" value="<?php echo e(old('nombre_escuela')); ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label for="nivel_educativo" class="form-label">Nivel Educativo:</label>
                    <select name="nivel_educativo" id="nivel_educativo" class="form-select" value="<?php echo e(old('nivel_educativo')); ?>" required>
                        <option value="">Seleccione una opción</option>
                        <option value="preescolar">Preescolar</option>
                        <option value="primaria">Primaria</option>
                        <option value="secundaria">Secundaria</option>
                        <option value="media superior">Media Superior</option>
                        <option value="superior">Superior</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="turno" class="form-label">Turno:</label>
                    <select name="turno" id="turno" class="form-select" value="<?php echo e(old('turno')); ?>" required>
                        <option value="">Seleccione una opción</option>
                        <option value="matutino">Matutino</option>
                        <option value="vespertino">Vespertino</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="sostenimiento" class="form-label">Sostenimiento:</label>
                    <select name="sostenimiento" id="sostenimiento" class="form-select" value="<?php echo e(old('sostenimiento')); ?>" required>
                        <option value="">Seleccione una opción</option>
                        <option value="federal">Federal</option>
                        <option value="estatal">Estatal</option>
                        <option value="particular">Particular</option>
                        <option value="municipal">Municipal</option>
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn-custom btn-primary"><i class="fas fa-save"></i> Guardar Identificación</button>
            </div>

        </div>

    </form>
    <!--Ubicacion--->
    <form action="<?php echo e(isset($plantel) ? route('planteles.store', $plantel->id) : '#'); ?>" method="POST" class="needs-validation form-ficha-base">
        <?php echo csrf_field(); ?>
        <?php if(isset($plantel)): ?>
        <?php echo method_field('PUT'); ?>
        <?php endif; ?>
        <div class="form-section step-section d-none" data-step="1">
            <h4>II. Ubicación</h4>
            <?php if (! (isset($plantel))): ?>
            <div class="alert alert-warning">
                Debes completar y guardar la Sección I antes de poder llenar esta sección.
            </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-4">
                    <label for="id_municipio" class="form-label">Municipio:</label>
                    <select name="id_municipio" id="id_municipio" class="form-control">
                        <option value="">Seleccione un municipio</option>
                        <?php $__currentLoopData = $municipios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $municipio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($municipio->id); ?>"><?php echo e($municipio->nombre_municipio); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <option value="nuevo">Otro...</option> 
                    </select>

                    <input type="text" name="nuevo_municipio" id="input_nuevo_municipio" class="form-control mt-2 d-none" placeholder="Nuevo municipio">
                </div>
                <div class="col-md-4">
                    <label for="id_localidad" class="form-label">Localidad:</label>
                    <select name="id_localidad" id="select_localidad" class="form-control" disabled>
                        <option value="">Seleccione...</option>
                        <?php $__currentLoopData = $localidades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $localidad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($localidad->id); ?>"><?php echo e($localidad->nombre_localidad); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <option value="nuevo">Otro...</option> 
                    </select>

                    <input type="text" id="input_nuevo_localidad" name="nuevo_localidad" class="form-control mt-2 d-none" placeholder="Ingrese nueva localidad">
                </div>
                <div class="col-md-4">
                    <label for="id_corde" class="form-label">CORDE:</label>
                    <select name="id_corde" id="select_corde" class="form-select">
                        <option value="">Seleccione...</option>
                        <?php $__currentLoopData = $cordes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $corde): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($corde->id); ?>" <?php echo e(old('id_corde', $plantel->id_corde ?? '') == $corde->id ? 'selected' : ''); ?>>
                            <?php echo e($corde->nombre_corde); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </select>
                    <input type="text" name="nuevo_corde" id="input_nuevo_corde" class="form-control mt-2 d-none" placeholder="Nuevo CORDE">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="domicilio_calle_numero" class="form-label">Calle y Número:</label>
                    <input type="text" class="form-control" name="domicilio_calle_numero" value="<?php echo e(old('domicilio_calle_numero')); ?>" placeholder="Ej. Av. Reforma 123" required>
                </div>
                <div class="col-md-4">
                    <label for="domicilio_colonia" class="form-label">Colonia:</label>
                    <input type="text" class="form-control" name="domicilio_colonia" value="<?php echo e(old('domicilio_colonia')); ?>" placeholder="Ej. Centro, Roma Norte" required>
                </div>
                <div class="col-md-2">
                    <label for="domicilio_cp" class="form-label">C.P.:</label>
                    <input type="text" class="form-control" name="domicilio_cp" value="<?php echo e(old('domicilio_cp')); ?>" placeholder="06000" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <label for="latitud" class="form-label">Latitud:</label>
                    <input type="text" class="form-control" name="latitud" value="<?php echo e(old('latitud')); ?>" placeholder="Ej. 19.432608" required>
                </div>
                <div class="col-md-3">
                    <label for="longitud" class="form-label">Longitud:</label>
                    <input type="text" class="form-control" name="longitud" value="<?php echo e(old('longitud')); ?>" placeholder="Ej. -99.133209" required>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Macroregión:</label>
                    <input type="text" class="form-control" value="<?php echo e($plantel->macroregion->nombre_macroregion ?? 'No asignada'); ?>" readonly>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Microregión:</label>
                    <input type="text" class="form-control" value="<?php echo e($plantel->microregion->nombre_microregiones ?? 'No asignada'); ?>" readonly>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn-custom btn-primary" <?php echo e(!isset($plantel) ? 'disabled' : ''); ?>><i class="fas fa-save"></i> Guardar Ubicación</button>
            </div>

        </div>
    </form>


    <!--Contacto--->
    <form action="<?php echo e(isset($plantel) ? route('planteles.update.contacto', $plantel->id) : '#'); ?>" method="POST" class="needs-validation form-ficha-base">
        <?php echo csrf_field(); ?>
        <?php if(isset($plantel)): ?>
        <?php echo method_field('PUT'); ?>
        <?php endif; ?>

        <div class="form-section step-section d-none" data-step="2">
            <h4>III. Contacto y Director</h4>
            <?php if (! (isset($plantel))): ?>
            <div class="alert alert-warning">
                Debes completar y guardar la Sección I antes de poder llenar esta sección.
            </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-6">
                    <label for="telefono_plantel" class="form-label">Telefono del Plantel:</label>
                    <input type="text" class="form-control" name="telefono_plantel" value="<?php echo e(old('telefono_plantel')); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="correo_institucional" class="form-label">Correo Institucional:</label>
                    <input type="text" class="form-control" name="correo_institucional" value="<?php echo e(old('correo_institucional')); ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label for="nombre_director_registrado" class="form-label">Nombre del Director:*</label>
                    <input type="text" class="form-control" name="nombre_director_registrado" value="<?php echo e(old('nombre_director_registrado')); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="id_director_asignado" class="form-label">Director Asignado(Usuario):</label>
                    <select name="id_director_asignado" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <?php $__currentLoopData = $directores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $director): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($director->id); ?>" <?php echo e(old('id_director_asignado') == $director->id ? 'selected' : ''); ?>>
                            <?php echo e($director->nombre_completo); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn-custom btn-primary" <?php echo e(!isset($plantel) ? 'disabled' : ''); ?>><i class="fas fa-save"></i> Guardar Contacto</button>
            </div>
        </div>

    </form>

    
    <form action="<?php echo e(isset($plantel) ? route('planteles.update.accesibilidad', $plantel->id) : '#'); ?>" method="POST" class="needs-validation form-ficha-base">
        <?php echo csrf_field(); ?>
        <?php if(isset($plantel)): ?>
        <?php echo method_field('PUT'); ?>
        <?php endif; ?>
        <div class="form-section step-section d-none" data-step="3">
            <h4>IV. Accesibilidad</h4>
            <?php if (! (isset($plantel))): ?>
            <div class="alert alert-warning">
                Debes completar y guardar la Sección I antes de poder llenar esta sección.
            </div>
            <?php endif; ?>
            <div class="mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="accesibilidad_rampas" id="accesibilidad_rampas" value="1"
                        <?php echo e(old('accesibilidad_rampas') ? 'checked' : ''); ?>>
                    <label class="form-check-label" for="accesibilidad_rampas">Rampas</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="accesibilidad_banos_adaptados" id="accesibilidad_banos_adaptados" value="1"
                        <?php echo e(old('accesibilidad_banos_adaptados') ? 'checked' : ''); ?>>
                    <label class="form-check-label" for="accesibilidad_banos_adaptados">Baños Adaptados</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="accesibilidad_sanaletica_braille" id="accesibilidad_sanaletica_braille" value="1"
                        <?php echo e(old('accesibilidad_sanaletica_braille') ? 'checked' : ''); ?>>
                    <label class="form-check-label" for="accesibilidad_sanaletica_braille">Señalética Braille</label>
                </div>
            </div>
            <div class="mb-3">
                <label for="accesibilidad_otros" class="form-label">Otros (Accesibilidad):</label>
                <input type="text" class="form-control" name="accesibilidad_otros" id="accesibilidad_otros"
                    value="<?php echo e(old('accesibilidad_otros')); ?>" placeholder="Especificar...">
            </div>
            <div class="mt-4">
                <button type="submit" class="btn-custom btn-primary" <?php echo e(!isset($plantel) ? 'disabled' : ''); ?>><i class="fas fa-save"></i> Guardar Accesibilidad</button>
            </div>
        </div>
    </form>

    

    <form action="<?php echo e(isset($plantel) ? route('planteles.update.totalUsuariosPlanteles', $plantel->id) : '#'); ?>" method="POST" class="needs-validation form-ficha-base">
        <?php echo csrf_field(); ?>
        <?php if(isset($plantel)): ?>
        <?php echo method_field('PUT'); ?>
        <?php endif; ?>
        <div class="form-section step-section d-none" data-step="4">
            <h4>V. Total Usuarios Planteles</h4>
            <?php if (! (isset($plantel))): ?>
            <div class="alert alert-warning">
                Debes completar y guardar la Sección I antes de poder llenar esta sección.
            </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-4">
                    <label for="total_alumnos" class="form-label">Total Alumnos:</label>
                    <input type="number" class="form-control" name="total_alumnos" value="<?php echo e(old('total_alumnos')); ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="total_docentes" class="form-label">Total Docentes</label>
                    <input type="number" class="form-control" name="total_docentes" value="<?php echo e(old('total_docentes')); ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="total_administrativos" class="form-label">Total Administrativos</label>
                    <input type="number" class="form-control" name="total_administrativos" value="<?php echo e(old('total_administrativos')); ?>" required>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn-custom btn-primary" <?php echo e(!isset($plantel) ? 'disabled' : ''); ?>><i class="fas fa-save"></i> Guardar Total Usuarios</button>
            </div>
        </div>
    </form>

    
    <form action="<?php echo e(isset($plantel) ? route('planteles.update.estatus', $plantel->id) : '#'); ?>" method="POST" class="needs-validation form-ficha-base">
        <?php echo csrf_field(); ?>
        <?php if(isset($plantel)): ?>
        <?php echo method_field('PUT'); ?>
        <?php endif; ?>
        <div class="form-section step-section d-none" data-step="5">
            <h4>VI. Estatus(Admin)</h4>
            <?php if (! (isset($plantel))): ?>
            <div class="alert alert-warning">
                Debes completar y guardar la Sección I antes de poder llenar esta sección.
            </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-4">
                    <label for="estatus_plantel" class="form-label">Estatus:</label>
                    <select name="estatus_plantel" class="form-select" required>
                        <option value="">Seleccione una opcion</option>
                        <option value="ACTIVO" <?php echo e(old ('estatus_plantel')=='ACTIVO' ? 'selected': ''); ?>>ACTIVO</option>
                        <option value="INACTIVO" <?php echo e(old ('estatus_plantel')=='INACTIVO' ? 'selected': ''); ?>>INACTIVO</option>
                        <option value="EN_REVISION" <?php echo e(old ('estatus_plantel')=='EN_REVISION' ? 'selected': ''); ?>>EN REVISION</option>

                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn-custom btn-primary" <?php echo e(!isset($plantel) ? 'disabled' : ''); ?>><i class="fas fa-save"></i> Guardar Estatus Plantel</button>
            </div>
        </div>
    </form>


<?php $__env->startPush('scripts'); ?>

<!--Navegacion por pestañas nav-->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const secciones = document.querySelectorAll('.step-section');
        const pestañas = document.querySelectorAll('.nav-tab');

        function mostrarSeccion(numero) {
            secciones.forEach((seccion, i) => {
                seccion.classList.toggle('active', i === numero);
                seccion.classList.toggle('d-none', i !== numero);
            });

            pestañas.forEach((pestana, i) => {
                pestana.classList.toggle('active', i === numero);
            });

            // Guardar el paso activo
            localStorage.setItem('pasoActivo', numero);
        }

        // Recuperar el paso guardado o usar 0 por defecto
        const pasoGuardado = parseInt(localStorage.getItem('pasoActivo')) || 0;
        mostrarSeccion(pasoGuardado);

        // Asignar eventos a las pestañas
        pestañas.forEach((pestana, i) => {
            pestana.addEventListener('click', () => {
                mostrarSeccion(i);
            });
        });
    });
</script>

<!--crear un nuevo muncipio y localidad-->
<script src="<?php echo e(asset('js/ubicacion.js')); ?>"></script>


<!--editar municipio-->
<script src="<?php echo e(asset('js/editar_ubicacion.js')); ?>"></script>

<?php $__env->stopPush(); ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home1/bcecacef/atlasinfraescolarpueblaa.online/resources/views/planteles/create.blade.php ENDPATH**/ ?>