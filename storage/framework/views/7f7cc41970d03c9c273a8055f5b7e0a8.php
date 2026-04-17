<div class="filtro-bloque">
    <label for="estado-macroregion">¿En qué macroregión desea filtrar?</label>
    <select id="estado-macroregion" name="macroregion" class="filtro-select">
        <option value="">Seleccione una opción</option>
        <?php $__currentLoopData = $macroregiones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $macro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($macro->id); ?>"><?php echo e($macro->nombre_macroregion); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label for="estado-microregion">¿Qué microregión le interesa?</label>
    <select id="estado-microregion" name="microregion" class="filtro-select">
        <option value="">Seleccione una opción</option>
        <?php $__currentLoopData = $microregiones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $micro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($micro->id); ?>"><?php echo e($micro->nombre_microregiones); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label for="estado-municipio">¿En qué municipio desea filtrar?</label>
    <select id="estado-municipio" name="municipio" class="filtro-select">
        <option value="">Seleccione una opción</option>
        <?php $__currentLoopData = $municipios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($mun->id); ?>"><?php echo e($mun->nombre_municipio); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label for="estado-nivel">¿Qué nivel educativo desea consultar?</label>
    <select id="estado-nivel" name="nivel" class="filtro-select">
        <option value="">Seleccione una opción</option>
        <?php $__currentLoopData = $niveles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nivel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($nivel->nivel); ?>">
            <?php echo e(ucwords(str_replace('_', ' ', $nivel->nivel))); ?>

        </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>

<hr>

<div class="filtro-bloque">
    <label for="estado_red_hidraulica">¿Cuál es el estado de la red hidráulica?</label>
    <select id="estado_red_hidraulica" name="estado_red_hidraulica" class="filtro-select">
        <option value="">Seleccione una opción</option>
        <option value="bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>

    <label for="estado_instalacion_sanitaria">¿Cómo está la instalación sanitaria?</label>
    <select id="estado_instalacion_sanitaria" name="estado_instalacion_sanitaria" class="filtro-select">
        <option value="">Seleccione una opción</option>
        <option value="bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>

    <label for="estado_instalacion_electrica">¿Cuál es el estado de la instalación eléctrica?</label>
    <select id="estado_instalacion_electrica" name="estado_instalacion_electrica" class="filtro-select">
        <option value="">Seleccione una opción</option>
        <option value="bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
</div><?php /**PATH /home1/bcecacef/atlasinfraescolarpueblaa.online/resources/views/partials/filtro_estado.blade.php ENDPATH**/ ?>