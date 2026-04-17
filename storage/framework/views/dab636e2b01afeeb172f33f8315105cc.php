<div class="filtro-bloque">
    <label for="energia-macroregion">¿En qué macroregión desea filtrar?</label>
    <select id="energia-macroregion" name="macroregion" class="filtro-select">
        <option value="">--Selecciona--</option>
        <?php $__currentLoopData = $macroregiones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($m->id); ?>"><?php echo e($m->nombre_macroregion); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label for="energia-microregion">¿Qué microregión le interesa?</label>
    <select id="energia-microregion" name="microregion" class="filtro-select">
        <option value="">--Selecciona--</option>
        <?php $__currentLoopData = $microregiones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($m->id); ?>"><?php echo e($m->nombre_microregiones); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label for="energia-municipio">¿En qué municipio desea aplicar el filtro?</label>
    <select id="energia-municipio" name="municipio" class="filtro-select">
        <option value="">--Selecciona--</option>
        <?php $__currentLoopData = $municipios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($mun->id); ?>"><?php echo e($mun->nombre_municipio); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label for="energia-nivel">¿Qué nivel educativo desea consultar?</label>
    <select id="energia-nivel" name="nivel" class="filtro-select">
        <option value="">--Selecciona--</option>
        <?php $__currentLoopData = $niveles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($n->nivel); ?>">
            <?php echo e(ucwords(str_replace('_', ' ', $n->nivel))); ?>

        </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>

<hr>

<div class="filtro-bloque">
    <label><input type="checkbox" id="suministro_energia" name="suministro_energia" value="1"> ¿Cuenta con suministro de energía?</label><br>
    <label><input type="checkbox" id="energia_paneles_solares" name="energia_paneles_solares" value="1"> ¿Dispone de paneles solares?</label><br>
    <label><input type="checkbox" id="energia_planta" name="energia_planta" value="1"> ¿Tiene planta de energía?</label>
</div><?php /**PATH /home1/bcecacef/atlasinfraescolarpueblaa.online/resources/views/partials/filtro_energia.blade.php ENDPATH**/ ?>