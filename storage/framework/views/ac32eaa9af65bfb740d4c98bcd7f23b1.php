
<div class="filtro-bloque">
    <label for="macroregion">¿En qué macroregión desea filtrar?</label>
    <select name="macroregion" id="accesibilidad-macroregion" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <?php $__currentLoopData = $macroregiones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $macro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($macro->id); ?>"><?php echo e($macro->nombre_macroregion); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label for="microregion">¿Qué microregión le interesa?</label>
    <select name="microregion" id="accesibilidad-microregion" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <?php $__currentLoopData = $microregiones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $micro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($micro->id); ?>"><?php echo e($micro->nombre_microregiones); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label for="municipio">¿En qué municipio desea aplicar el filtro?</label>
    <select name="municipio" id="accesibilidad-municipio" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <?php $__currentLoopData = $municipios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($mun->id); ?>"><?php echo e($mun->nombre_municipio); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label for="nivel">¿Qué nivel académico desea consultar?</label>
    <select name="nivel" id="accesibilidad-nivel" class="filtro-select">
        <option value="">Seleccione</option>
        <?php $__currentLoopData = $niveles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nivel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($nivel->nivel); ?>">
            <?php echo e(ucwords(str_replace('_', ' ', $nivel->nivel))); ?>

        </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>

<hr>


<div class="filtro-bloque">
    <label>¿Cuenta con infraestructura para discapacidad?</label><br>
    <label><input type="radio" name="infraestructura_discapacidad" value="1"> Sí</label>
    <label><input type="radio" name="infraestructura_discapacidad" value="0"> No</label>

    <label>¿Está marcado como sin infraestructura para discapacidad?</label><br>
    <label><input type="radio" name="sin_infraestructura_discapacidad" value="1"> Sí</label>
    <label><input type="radio" name="sin_infraestructura_discapacidad" value="0"> No</label>

    <label for="equipo_discapacidad_categoria">¿Nivel de equipamiento para discapacidad?</label>
    <select name="equipo_discapacidad_categoria" id="equipo_discapacidad_categoria" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <option value="ninguno">Sin equipo</option>
        <option value="bajo">1 a 2 elementos</option>
        <option value="medio">3 a 5 elementos</option>
        <option value="alto">Más de 5 elementos</option>
    </select>
</div><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/partials/filtro_accesibilidad.blade.php ENDPATH**/ ?>