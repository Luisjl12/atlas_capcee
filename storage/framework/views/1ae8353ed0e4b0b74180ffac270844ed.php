
<div class="filtro-bloque">
    <label for="macroregion">¿En qué macroregión desea filtrar?</label>
    <select name="macroregion" id="seguridad-macroregion" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <?php $__currentLoopData = $macroregiones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $macro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($macro->id); ?>"><?php echo e($macro->nombre_macroregion); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label for="microregion">¿Qué microregión le interesa?</label>
    <select name="microregion" id="seguridad-microregion" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <?php $__currentLoopData = $microregiones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $micro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($micro->id); ?>"><?php echo e($micro->nombre_microregiones); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label for="municipio">¿En qué municipio desea aplicar el filtro?</label>
    <select name="municipio" id="seguridad-municipio" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <?php $__currentLoopData = $municipios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($mun->id); ?>"><?php echo e($mun->nombre_municipio); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label for="nivel">¿Qué nivel académico desea consultar?</label>
    <select name="nivel" id="seguridad-nivel" class="filtro-select">
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
    <label>¿Cuenta con dictamen de Protección Civil?</label><br>
    <label><input type="radio" name="proteccion_civil" id="proteccion_civil" value="1"> Sí</label>
    <label><input type="radio" name="proteccion_civil" id="proteccion_civil" value="0"> No</label>

    <label>¿La barda está completa?</label><br>
    <label><input type="radio" name="barda_completa" id="barda_completa" value="1"> Sí</label>
    <label><input type="radio" name="barda_completa" id="barda_completa" value="0"> No</label>

    <label for="estado_barda">¿Cuál es el estado de la barda?</label>
    <select name="estado_barda" id="estado_barda" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <option value="bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>

    <label for="estado_cerco">¿Cuál es el estado de la cerca?</label>
    <select name="estado_cerco" id="estado_cerco" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <option value="bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
</div><?php /**PATH /home1/bcecacef/atlasinfraescolarpueblaa.online/resources/views/partials/filtro_seguridad.blade.php ENDPATH**/ ?>