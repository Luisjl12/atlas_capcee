<div class="filtro-bloque">
    <label for="filtro-macroregion">¿En qué macroregión desea filtrar?</label>
    <select id="filtro-macroregion" name="macroregion" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <?php $__currentLoopData = $macroregiones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $macro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($macro->id); ?>"><?php echo e($macro->nombre_macroregion); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label for="filtro-microregion">¿Qué microregión le interesa?</label>
    <select id="filtro-microregion" name="microregion" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <?php $__currentLoopData = $microregiones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $micro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($micro->id); ?>"><?php echo e($micro->nombre_microregiones); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label for="filtro-municipio">¿En qué municipio desea aplicar el filtro?</label>
    <select id="filtro-municipio" name="municipio" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <?php $__currentLoopData = $municipios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $muni): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($muni->id); ?>"><?php echo e($muni->nombre_municipio); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label for="filtro-nivel">¿Qué nivel educativo desea consultar?</label>
    <select id="filtro-nivel" name="nivel" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <?php $__currentLoopData = $niveles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nivel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($nivel->nivel); ?>">
            <?php echo e(ucwords(str_replace('_', ' ', $nivel->nivel))); ?>

        </option>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>

<div class="filtro-bloque">
    <label for="filtro-superficie">¿Qué rango de superficie desea filtrar?</label>
    <select id="filtro-superficie" name="superficie" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <?php $__currentLoopData = $rangosSuperficie; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rango): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($rango->rango); ?>"><?php echo e(ucwords(str_replace('_', ' ', $rango->rango))); ?> m²</option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/partials/filtro_superficie.blade.php ENDPATH**/ ?>