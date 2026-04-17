<div class="filtro-bloque">
    <label for="obras-macroregion">¿En qué macroregión desea filtrar?</label>
    <select id="obras-macroregion" name="macroregion" class="filtro-select">
        <option value="">Seleccione</option>
        <?php $__currentLoopData = $macroregiones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $macro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($macro->id); ?>"><?php echo e($macro->nombre_macroregion); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label for="obras-microregion">¿Qué microregión le interesa?</label>
    <select id="obras-microregion" name="microregion" class="filtro-select">
        <option value="">Seleccione</option>
        <?php $__currentLoopData = $microregiones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $micro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($micro->id); ?>"><?php echo e($micro->nombre_microregiones); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label for="obras-municipio">¿En qué municipio desea aplicar el filtro?</label>
    <select id="obras-municipio" name="municipio" class="filtro-select">
        <option value="">Seleccione</option>
        <?php $__currentLoopData = $municipios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($mun->id); ?>"><?php echo e($mun->nombre_municipio); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label for="obras-nivel">¿Qué nivel educativo desea consultar?</label>
    <select id="obras-nivel" name="nivel" class="filtro-select">
        <option value="">Seleccione</option>
        <?php $__currentLoopData = $niveles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nivel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($nivel->nivel); ?>">
            <?php echo e(ucwords(str_replace('_', ' ', $nivel->nivel))); ?>

        </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>

<hr>

<h4>¿Qué tipo de obras desea filtrar?</h4>

<div class="filtro-bloque">
    <label><input type="checkbox" id="rehabilitacion_realizada" name="rehabilitacion_realizada" value="1"> ¿Se han realizado obras de rehabilitación en los últimos cinco años?</label><br>
    <label><input type="checkbox" id="rehabilitacion_impermeabilizacion" name="rehabilitacion_impermeabilizacion" value="1"> ¿Se han hecho obras de impermeabilización en los últimos cinco años?</label><br>
    <label><input type="checkbox" id="rehabilitacion_albanileria" name="rehabilitacion_albanileria" value="1"> ¿Se han hecho obras de albañilería los últimos cinco años?</label><br>
    <label><input type="checkbox" id="rehabilitacion_pintura" name="rehabilitacion_pintura" value="1"> ¿Se han hecho obras de rehabilitación (pintura general) en los últimos cinco años?</label><br>
    <label><input type="checkbox" id="rehabilitacion_red_hidraulica" name="rehabilitacion_red_hidraulica" value="1"> ¿Se han hecho obras a la red hidráulica en los últimos cinco años?</label><br>
    <label><input type="checkbox" id="rehabilitacion_red_sanitaria" name="rehabilitacion_red_sanitaria" value="1"> ¿Se han hecho obras en la red sanitaria en los últimos cinco años?</label><br>
    <label><input type="checkbox" id="rehabilitacion_estructural" name="rehabilitacion_estructural" value="1"> ¿Incluye mejoras estructurales?</label><br>
    <label><input type="checkbox" id="obras_nuevas" name="obras_nuevas" value="1"> ¿Se han realizado obras nuevas en los ultimos cinco años?</label><br>
    <label><input type="checkbox" id="construccion_educativa" name="construccion_educativa" value="1"> ¿Se han hecho construcciones en espacios educativos en los últimos cinco años?</label><br>
    <label><input type="checkbox" id="construccion_deportiva" name="construccion_deportiva" value="1"> ¿Se han hecho construcciones en espacios deportivos o recreativos en los últimimos cinco años?</label><br>
    <label><input type="checkbox" id="construccion_sanitaria" name="construccion_sanitaria" value="1"> ¿Se han hecho construcciones en sanitarios en los últimos cinco años?</label><br>
    <label><input type="checkbox" id="construccion_complementos" name="construccion_complementos" value="1"> ¿Incluye construcción de complementos?</label><br>
    <label><input type="checkbox" id="construccion_otro" name="construccion_otro" value="1"> ¿Incluye otros tipos de construcción?</label>
</div><?php /**PATH /home1/bcecacef/atlasinfraescolarpueblaa.online/resources/views/partials/filtro_obras.blade.php ENDPATH**/ ?>