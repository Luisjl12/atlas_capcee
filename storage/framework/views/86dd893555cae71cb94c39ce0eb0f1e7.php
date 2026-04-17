<div class="filtro-bloque">
    <label for="drenaje-macroregion">¿En qué macroregión desea filtrar?</label>
    <select id="drenaje-macroregion" name="macroregion" class="filtro-select">
        <option value="">--Todas--</option>
        <?php $__currentLoopData = $macroregiones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($m->id); ?>"><?php echo e($m->nombre_macroregion); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label for="drenaje-microregion">¿Qué microregión le interesa?</label>
    <select id="drenaje-microregion" name="microregion" class="filtro-select">
        <option value="">--Todas--</option>
        <?php $__currentLoopData = $microregiones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($m->id); ?>"><?php echo e($m->nombre_microregiones); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label for="drenaje-municipio">¿En qué municipio desea aplicar el filtro?</label>
    <select id="drenaje-municipio" name="municipio" class="filtro-select">
        <option value="">--Todos--</option>
        <?php $__currentLoopData = $municipios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($mun->id); ?>"><?php echo e($mun->nombre_municipio); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label for="drenaje-nivel">¿Qué nivel educativo desea consultar?</label>
    <select id="drenaje-nivel" name="nivel" class="filtro-select">
        <option value="">--Todos--</option>
        <?php $__currentLoopData = $niveles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($n->nivel); ?>">
            <?php echo e(ucwords(str_replace('_', ' ', $n->nivel))); ?>

        </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>

<hr>

<div class="filtro-bloque">
    <label><input type="checkbox" id="drenaje_publico" name="drenaje_publico" value="1"> ¿Cuenta con drenaje público?</label><br>
    <label><input type="checkbox" id="fosa_septica" name="fosa_septica" value="1"> ¿Tiene fosa séptica?</label><br>
    <label><input type="checkbox" id="planta_tratamiento" name="planta_tratamiento" value="1"> ¿Dispone de planta de tratamiento?</label><br>
    <label><input type="checkbox" id="descarga_otro" name="descarga_otro" value="1"> ¿Existe otro tipo de descarga?</label><br>
    <label><input type="checkbox" id="separacion_aguas" name="separacion_aguas" value="1"> ¿Hay separación de aguas?</label>
</div><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/partials/filtro_drenaje.blade.php ENDPATH**/ ?>