<!-- Modal Multifiltro -->
<div class="modal fade" id="modalMultifiltro" tabindex="-1" aria-labelledby="modalMultifiltroLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-light text-dark">
                <h5 class="modal-title" id="modalMultifiltroLabel">Multifiltro avanzado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formMultifiltro">
                    <div class="accordion" id="accordionFiltros">
                        
                        <!-- Territorial -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTerritorial">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTerritorial">
                                    🗺️ Territorial
                                </button>
                            </h2>
                            <div id="collapseTerritorial" class="accordion-collapse collapse show">
                                <div class="accordion-body fondo-territorial">
                                    <label>¿Que macroregión desea filtrar?</label>
                                    <select name="macroregion" id="macroregion" class="form-select">
                                        <option value="">-- Selecciona --</option>
                                        <?php $__currentLoopData = $macroregiones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $macro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($macro->id); ?>"><?php echo e($macro->nombre_macroregion); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    <label class="mt-2">¿Que microregión desea filtrar?</label>
                                    <select name="microregion" id="microregion" class="form-select">
                                        <option value="">-- Selecciona --</option>
                                        <?php $__currentLoopData = $microregiones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $micro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($micro->id); ?>"><?php echo e($micro->nombre_microregiones); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    <label class="mt-2">¿Que municipio desea filtrar?</label>
                                    <select name="municipio" id="municipio" class="form-select">
                                        <option value="">-- Selecciona --</option>
                                        <?php $__currentLoopData = $municipios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $muni): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($muni->id); ?>"><?php echo e($muni->nombre_municipio); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Nivel educativo -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingNivel">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNivel">
                                    🎓 Nivel educativo
                                </button>
                            </h2>
                            <div id="collapseNivel" class="accordion-collapse collapse">
                                <div class="accordion-body fondo-territorial">
                                    <label>¿Que nivel escolar deseas filtrar?</label>
                                    <select name="nivel" class="form-select">
                                        <option value="">-- Selecciona --</option>
                                        <?php $__currentLoopData = $niveles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nivel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($nivel->nivel); ?>"><?php echo e(ucwords(str_replace('_', ' ', $nivel->nivel))); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                             <!-- Energía -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingEnergia">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEnergia">
                                    ⚡ Energía
                                </button>
                            </h2>
                            <div id="collapseEnergia" class="accordion-collapse collapse">
                                <div class="accordion-body fondo-territorial">
                                    <label class="form-label d-block">¿Cuenta con suministro de energía?</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="suministro_energia" value="1">
                                        <label class="form-check-label">Sí</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="suministro_energia" value="0">
                                        <label class="form-check-label">No</label>
                                    </div>

                                    <label class="form-label d-block mt-3">¿Cuenta con paneles solares?</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="energia_paneles_solares" value="1">
                                        <label class="form-check-label">Sí</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="energia_paneles_solares" value="0">
                                        <label class="form-check-label">No</label>
                                    </div>

                                    <label class="form-label d-block mt-3">¿Cuenta con planta generadora de energía?</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="energia_planta" value="1">
                                        <label class="form-check-label">Sí</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="energia_planta" value="0">
                                        <label class="form-check-label">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Superficie -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingSuperficie">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSuperficie">
                                    📏 Superficie
                                </button>
                            </h2>
                            <div id="collapseSuperficie" class="accordion-collapse collapse">
                                <div class="accordion-body fondo-territorial">
                                    <label>¿Cual rango de superficie desea filtrar?</label>
                                    <select name="superficie" class="form-select">
                                        <option value="">-- Selecciona --</option>
                                        <?php $__currentLoopData = $rangosSuperficie; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rango): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($rango->rango); ?>"><?php echo e(ucwords(str_replace('_', ' ', $rango->rango))); ?> m²</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingEdificios">
                                <button class="accordion-button collapsed d-flex justify-content-between align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEdificios">
                                    <span>🏢 Infraestructura por Edificios</span>
                                    <span class="badge bg-primary rounded-pill me-3" style="font-size: 0.8rem;">
                                        Total: <span id="totalEdificiosBadge"><?php echo e($totalGlobalEdificios ?? 0); ?></span>
                                    </span>
                                </button>
                            </h2>
                            <div id="collapseEdificios" class="accordion-collapse collapse" aria-labelledby="headingEdificios">
                                <div class="accordion-body fondo-territorial">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Mínimo</label>
                                            <input type="number" name="numero_edificios_min" class="form-control form-control-sm" placeholder="1" min="0">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Máximo</label>
                                            <input type="number" name="numero_edificios_max" class="form-control form-control-sm" placeholder="20" min="0">
                                        </div>
                                        <div class="col-12 mt-2">
                                            <hr class="my-2 text-muted">
                                            <label class="form-label fw-bold">Número exacto</label>
                                            <input type="number" name="numero_edificios_exacto" class="form-control form-control-sm" placeholder="Ej. 5" min="0">
                                            <div class="form-text">Si usas un número exacto, se ignoran los rangos.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Accesibilidad -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingAccesibilidad">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAccesibilidad">
                                    ♿ Accesibilidad
                                </button>
                            </h2>
                            <div id="collapseAccesibilidad" class="accordion-collapse collapse">
                                <div class="accordion-body fondo-territorial">
                                    <label>¿Cuenta con infraestructura para personas discapacitadas?</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="infraestructura_discapacidad" value="1">
                                        <label class="form-check-label">Sí</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="infraestructura_discapacidad" value="0">
                                        <label class="form-check-label">No</label>
                                    </div>

                                    

                                    <label class="mt-3">¿Que nivel de equipamiento tiene?</label>
                                    <select name="equipo_discapacidad_categoria" class="form-select">
                                        <option value="">-- Selecciona --</option>
                                        <option value="ninguno">Sin equipo</option>
                                        <option value="bajo">1 a 2 elementos</option>
                                        <option value="medio">3 a 5 elementos</option>
                                        <option value="alto">Más de 5 elementos</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Obras recientes -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingObras">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseObras">
                                    🏗️ Obras recientes
                                </button>
                            </h2>
                            <div id="collapseObras" class="accordion-collapse collapse">
                                <div class="accordion-body fondo-territorial">

                                    <h6 class="mb-2">🔧 Rehabilitación</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rehabilitacion_realizada" name="rehabilitacion_realizada" value="1">
                                        <label class="form-check-label" for="rehabilitacion_realizada">¿Se han realizado obras de rehabilitación en los últimos cinco años?</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rehabilitacion_impermeabilizacion" name="rehabilitacion_impermeabilizacion" value="1">
                                        <label class="form-check-label" for="rehabilitacion_impermeabilizacion">¿Obras de impermeabilización?</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rehabilitacion_albanileria" name="rehabilitacion_albanileria" value="1">
                                        <label class="form-check-label" for="rehabilitacion_albanileria">¿Obras de albañilería?</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rehabilitacion_pintura" name="rehabilitacion_pintura" value="1">
                                        <label class="form-check-label" for="rehabilitacion_pintura">¿Rehabilitación con pintura general?</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rehabilitacion_red_hidraulica" name="rehabilitacion_red_hidraulica" value="1">
                                        <label class="form-check-label" for="rehabilitacion_red_hidraulica">¿Obras en la red hidráulica?</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rehabilitacion_red_sanitaria" name="rehabilitacion_red_sanitaria" value="1">
                                        <label class="form-check-label" for="rehabilitacion_red_sanitaria">¿Obras en la red sanitaria?</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rehabilitacion_estructural" name="rehabilitacion_estructural" value="1">
                                        <label class="form-check-label" for="rehabilitacion_estructural">¿Mejoras estructurales?</label>
                                    </div>

                                    <hr class="my-3">

                                    <h6 class="mb-2">🏗️ Construcción</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="obras_nuevas" name="obras_nuevas" value="1">
                                        <label class="form-check-label" for="obras_nuevas">¿Obras nuevas en los últimos cinco años?</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="construccion_educativa" name="construccion_educativa" value="1">
                                        <label class="form-check-label" for="construccion_educativa">¿Construcción en espacios educativos?</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="construccion_deportiva" name="construccion_deportiva" value="1">
                                        <label class="form-check-label" for="construccion_deportiva">¿Construcción en espacios deportivos o recreativos?</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="construccion_sanitaria" name="construccion_sanitaria" value="1">
                                        <label class="form-check-label" for="construccion_sanitaria">¿Construcción en sanitarios?</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="construccion_complementos" name="construccion_complementos" value="1">
                                        <label class="form-check-label" for="construccion_complementos">¿Construcción de complementos?</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="construccion_otro" name="construccion_otro" value="1">
                                        <label class="form-check-label" for="construccion_otro">¿Otros tipos de construcción?</label>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!--Seccion de filtro hidraulico-->
<div class="accordion-item">
    <h2 class="accordion-header" id="headingHidraulica">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHidraulica">
            🚰 Hidráulica
        </button>
    </h2>
    <div id="collapseHidraulica" class="accordion-collapse collapse">
        <div class="accordion-body fondo-territorial">

            <h6 class="mb-2">💧 Tipo de suministro de agua</h6>
            <small class="text-muted">Mantén presionada la tecla Ctrl + click (Windows/Linux) o Cmd (Mac) para seleccionar varias opciones.</small>

            <select class="form-select" name="tipo_suministro[]" id="tipo_suministro" multiple>
                <option value="">Seleccione una opción</option>
                <option value="agua_red_publica">Agua de red pública</option>
                <option value="agua_pozo">Agua de pozo</option>
                <option value="agua_cuerpo">Agua de cuerpo natural</option>
                <option value="agua_pipas">Agua por pipas</option>
                <option value="agua_otro">Otro tipo de suministro</option>
            </select>

            <hr class="my-3">

            <h6 class="mb-2">🛢️ Almacenamiento de agua</h6>
            

            <select class="form-select" name="tipo_almacenamiento[]" id="tipo_almacenamiento" multiple>
                <option value="">Seleccione una opción</option>
                <option value="cisterna">Cisterna</option>
                <option value="tinacos">Tinacos</option>
                <option value="tanque">Tanque de almacenamiento</option>
                <option value="otro">Otro tipo de almacenamiento</option>
            </select>

        </div>
    </div>
</div>


                        <!--Drenaje-->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingDrenaje">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDrenaje">
                                    🚽 Drenaje
                                </button>
                            </h2>
                            <div id="collapseDrenaje" class="accordion-collapse collapse">
                                <div class="accordion-body fondo-territorial">

                                    <h6 class="mb-2">💧 Tipo de sistema de drenaje</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="drenaje_publico" name="drenaje_publico" value="1">
                                        <label class="form-check-label" for="drenaje_publico">¿Cuenta con drenaje público?</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="fosa_septica" name="fosa_septica" value="1">
                                        <label class="form-check-label" for="fosa_septica">¿Tiene fosa séptica?</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="planta_tratamiento" name="planta_tratamiento" value="1">
                                        <label class="form-check-label" for="planta_tratamiento">¿Dispone de planta de tratamiento?</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="descarga_otro" name="descarga_otro" value="1">
                                        <label class="form-check-label" for="descarga_otro">¿Existe otro tipo de descarga?</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="separacion_aguas" name="separacion_aguas" value="1">
                                        <label class="form-check-label" for="separacion_aguas">¿Hay separación de aguas?</label>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Estado de conservación -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingEstadoConservacion">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEstadoConservacion">
                                    🧱 Estado de conservación
                                </button>
                            </h2>
                            <div id="collapseEstadoConservacion" class="accordion-collapse collapse">
                                <div class="accordion-body fondo-territorial">

                                    <div class="mb-3">
                                        <label for="estado_red_hidraulica" class="form-label">¿Cuál es el estado de la red hidráulica?</label>
                                        <select id="estado_red_hidraulica" name="estado_red_hidraulica" class="form-select filtro-select">
                                            <option value="">Seleccione una opción</option>
                                            <option value="bueno">Bueno</option>
                                            <option value="regular">Regular</option>
                                            <option value="malo">Malo</option>
                                            <option value="no_tiene">No Tiene</option>    
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="estado_instalacion_sanitaria" class="form-label">¿Cómo está la instalación sanitaria?</label>
                                        <select id="estado_instalacion_sanitaria" name="estado_instalacion_sanitaria" class="form-select filtro-select">
                                            <option value="">Seleccione una opción</option>
                                            <option value="bueno">Bueno</option>
                                            <option value="regular">Regular</option>
                                            <option value="malo">Malo</option>
                                            <option value="no_tiene">No Tiene</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="estado_instalacion_electrica" class="form-label">¿Cuál es el estado de la instalación eléctrica?</label>
                                        <select id="estado_instalacion_electrica" name="estado_instalacion_electrica" class="form-select filtro-select">
                                            <option value="">Seleccione una opción</option>
                                            <option value="bueno">Bueno</option>
                                            <option value="regular">Regular</option>
                                            <option value="malo">Malo</option>
                                            <option value="no_tiene">No Tiene</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Sanitarios -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingSanitarios">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSanitarios">
                                    🚻 Sanitarios
                                </button>
                            </h2>
                            <div id="collapseSanitarios" class="accordion-collapse collapse">
                                <div class="accordion-body fondo-territorial">

                                    <div class="mb-3">
                                        <label for="estado_banos" class="form-label">¿Estado general de los baños?</label>
                                        <select name="estado_banos" id="estado_banos" class="form-select filtro-select">
                                            <option value="">-- Selecciona --</option>
                                            <option value="bueno">Bueno</option>
                                            <option value="regular">Regular</option>
                                            <option value="malo">Malo</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="banos_hombres_min" class="form-label">¿Cantidad mínima de baños para hombres?</label>
                                        <input type="number" name="banos_hombres_min" id="banos_hombres_min" class="form-control" min="0">
                                    </div>

                                    <div class="mb-3">
                                        <label for="banos_mujeres_min" class="form-label">¿Cantidad mínima de baños para mujeres?</label>
                                        <input type="number" name="banos_mujeres_min" id="banos_mujeres_min" class="form-control" min="0">
                                    </div>

                                    <div class="mb-3">
                                        <label for="lavamanos_min" class="form-label">¿Cantidad mínima de lavamanos?</label>
                                        <input type="number" name="lavamanos_min" id="lavamanos_min" class="form-control" min="0">
                                    </div>

                                    <div class="mb-3">
                                        <label for="estado_lavamanos" class="form-label">¿Estado de lavamanos?</label>
                                        <select name="estado_lavamanos" id="estado_lavamanos" class="form-select filtro-select">
                                            <option value="">-- Selecciona --</option>
                                            <option value="bueno">Bueno</option>
                                            <option value="regular">Regular</option>
                                            <option value="malo">Malo</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="tomas_bebederos_min" class="form-label">¿Cantidad mínima de tomas de bebederos?</label>
                                        <input type="number" name="tomas_bebederos_min" id="tomas_bebederos_min" class="form-control" min="0">
                                    </div>

                                    <div class="mb-3">
                                        <label for="estado_bebederos" class="form-label">¿Estado de bebederos?</label>
                                        <select name="estado_bebederos" id="estado_bebederos" class="form-select filtro-select">
                                            <option value="">-- Selecciona --</option>
                                            <option value="bueno">Bueno</option>
                                            <option value="regular">Regular</option>
                                            <option value="malo">Malo</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Seguridad -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingSeguridad">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeguridad">
                                    🛡️ Seguridad
                                </button>
                            </h2>
                            <div id="collapseSeguridad" class="accordion-collapse collapse">
                                <div class="accordion-body fondo-territorial">

                                    <div class="mb-3">
                                        <label class="form-label d-block">¿Cuenta con dictamen de Protección Civil?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="proteccion_civil" id="proteccion_civil_si" value="1">
                                            <label class="form-check-label" for="proteccion_civil_si">Sí</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="proteccion_civil" id="proteccion_civil_no" value="0">
                                            <label class="form-check-label" for="proteccion_civil_no">No</label>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label d-block">¿La barda está completa?</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="barda_completa" id="barda_completa_si" value="1">
                                            <label class="form-check-label" for="barda_completa_si">Sí</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="barda_completa" id="barda_completa_no" value="0">
                                            <label class="form-check-label" for="barda_completa_no">No</label>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="estado_barda" class="form-label">¿Cuál es el estado de la barda?</label>
                                        <select name="estado_barda" id="estado_barda" class="form-select filtro-select">
                                            <option value="">-- Selecciona --</option>
                                            <option value="bueno">Bueno</option>
                                            <option value="regular">Regular</option>
                                            <option value="malo">Malo</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="estado_cerco" class="form-label">¿Cuál es el estado de la cerca?</label>
                                        <select name="estado_cerco" id="estado_cerco" class="form-select filtro-select">
                                            <option value="">-- Selecciona --</option>
                                            <option value="bueno">Bueno</option>
                                            <option value="regular">Regular</option>
                                            <option value="malo">Malo</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnAplicarFiltros">Aplicar filtros</button>
            </div>
             <button type="button"  class="btn btn-secondary" id="btnLimpiarFiltros">
                Limpiar filtros
            </button>
        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/partials/multifiltro.blade.php ENDPATH**/ ?>