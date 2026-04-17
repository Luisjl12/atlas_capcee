<div class="filtro-bloque">
    <label for="agua-macroregion">¿En qué macroregión desea filtrar?</label>
    <select name="macroregion" id="agua-macroregion" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <?php $__currentLoopData = $macroregiones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $macro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($macro->id); ?>"><?php echo e($macro->nombre_macroregion); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label for="agua-microregion">¿Qué microregión le interesa?</label>
    <select name="microregion" id="agua-microregion" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <?php $__currentLoopData = $microregiones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $micro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($micro->id); ?>"><?php echo e($micro->nombre_microregiones); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label for="agua-municipio">¿En qué municipio desea aplicar el filtro?</label>
    <select name="municipio" id="agua-municipio" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <?php $__currentLoopData = $municipios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $muni): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($muni->id); ?>"><?php echo e($muni->nombre_municipio); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label for="agua-nivel">¿Qué nivel educativo desea consultar?</label>
    <select name="nivel" id="agua-nivel" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <?php $__currentLoopData = $niveles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nivel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($nivel->nivel); ?>">
            <?php echo e(ucwords(str_replace('_', ' ', $nivel->nivel))); ?>

        </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>

<hr>

<div class="filtro-bloque">
    <label>¿Qué tipo de suministro de agua desea filtrar?</label><br>
    <label><input type="checkbox" name="agua_red_publica" class="filtro-agua" id="agua_red_publica" value="1"> ¿Cuenta con agua de red pública?</label><br>
    <label><input type="checkbox" name="agua_pozo" class="filtro-agua" id="agua_pozo" value="1"> ¿Tiene acceso a agua de pozo?</label><br>
    <label><input type="checkbox" name="agua_cuerpo" class="filtro-agua" id="agua_cuerpo" value="1"> ¿Utiliza agua de cuerpo natural?</label><br>
    <label><input type="checkbox" name="agua_pipas" class="filtro-agua" id="agua_pipas" value="1"> ¿Recibe agua por pipas?</label><br>
    <label><input type="checkbox" name="agua_otro" class="filtro-agua" id="agua_otro" value="1"> ¿Existe otro tipo de suministro?</label><br>
    <label><input type="checkbox" name="cisterna" class="filtro-agua" id="cisterna" value="1"> ¿Dispone de cisterna?</label><br>
    <label><input type="checkbox" name="tinacos" class="filtro-agua" id="tinacos" value="1"> ¿Cuenta con tinacos?</label><br>
    <label><input type="checkbox" name="tanque" class="filtro-agua" id="tanque" value="1"> ¿Tiene tanque de almacenamiento?</label><br>
    <label><input type="checkbox" name="almacenamiento_otro" class="filtro-agua" id="almacenamiento_otro" value="1"> ¿Utiliza otro tipo de almacenamiento?</label>
</div><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/partials/filtros_agua.blade.php ENDPATH**/ ?>