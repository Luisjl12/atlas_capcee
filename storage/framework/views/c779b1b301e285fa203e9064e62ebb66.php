
<div class="filtro-bloque">
    <label>¿En que macroregión desea consultar?</label>
    <select name="macroregion" id="sanitarios-macroregion" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <?php $__currentLoopData = $macroregiones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $macro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($macro->id); ?>"><?php echo e($macro->nombre_macroregion); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label>En que microregión desea consultar?</label>
    <select name="microregion" id="sanitarios-microregion" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <?php $__currentLoopData = $microregiones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $micro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($micro->id); ?>"><?php echo e($micro->nombre_microregiones); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label>¿En que municipio desea consultar?</label>
    <select name="municipio" id="sanitarios-municipio" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <?php $__currentLoopData = $municipios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($mun->id); ?>"><?php echo e($mun->nombre_municipio); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label>¿Que nivel educativo quieres consultar?</label>
    <select name="nivel" id="sanitarios-nivel" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <?php $__currentLoopData = $niveles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nivel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
        $textoNivel = ucwords(str_replace('_', ' ', $nivel->nivel));
        ?>
        <option value="<?php echo e($nivel->nivel); ?>"><?php echo e($textoNivel); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </select>
</div>

<hr>


<div class="filtro-bloque">
    <label>¿Estado general de los baños?</label>
    <select name="estado_banos" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <option value="bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>

    <label>¿Cantidad mínima de baños para hombres?</label>
    <input type="number" name="banos_hombres_min" min="0">

    <label>¿Cantidad mínima de baños para mujeres?</label>
    <input type="number" name="banos_mujeres_min" min="0">

    <label>¿Cantidad mínima de lavamanos?</label>
    <input type="number" name="lavamanos_min" min="0">

    <label>¿Estado de lavamanos?</label>
    <select name="estado_lavamanos" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <option value="bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>

    <label>¿Cantidad mínima de tomas de bebederos?</label>
    <input type="number" name="tomas_bebederos_min" min="0">

    <label>¿Estado de bebederos?</label>
    <select name="estado_bebederos" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <option value="bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
</div><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/partials/filtro_sanitarios.blade.php ENDPATH**/ ?>