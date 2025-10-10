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
                                        @foreach($macroregiones as $macro)
                                        <option value="{{ $macro->id }}">{{ $macro->nombre_macroregion }}</option>
                                        @endforeach
                                    </select>

                                    <label class="mt-2">¿Que microregión desea filtrar?</label>
                                    <select name="microregion" id="microregion" class="form-select">
                                        <option value="">-- Selecciona --</option>
                                        @foreach($microregiones as $micro)
                                        <option value="{{ $micro->id }}">{{ $micro->nombre_microregiones }}</option>
                                        @endforeach
                                    </select>

                                    <label class="mt-2">¿Que municipio desea filtrar?</label>
                                    <select name="municipio" id="municipio" class="form-select">
                                        <option value="">-- Selecciona --</option>
                                        @foreach($municipios as $muni)
                                        <option value="{{ $muni->id }}">{{ $muni->nombre_municipio }}</option>
                                        @endforeach
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
                                        @foreach($niveles as $nivel)
                                        <option value="{{ $nivel->nivel }}">{{ ucwords(str_replace('_', ' ', $nivel->nivel)) }}</option>
                                        @endforeach
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
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="suministro_energia" value="1">
                                        <label class="form-check-label">¿Cuenta con suministro de energia?</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="energia_paneles_solares" value="1">
                                        <label class="form-check-label">¿Cuenta con paneles solares?</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="energia_planta" value="1">
                                        <label class="form-check-label">¿Cuenta con planta generadora de energia?</label>
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
                                        @foreach($rangosSuperficie as $rango)
                                        <option value="{{ $rango->rango }}">{{ ucwords(str_replace('_', ' ', $rango->rango)) }} m²</option>
                                        @endforeach
                                    </select>
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

                                    <label class="mt-3">¿No cuenta con infraestructura para discapacitados?</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="sin_infraestructura_discapacidad" value="1">
                                        <label class="form-check-label">Sí</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="sin_infraestructura_discapacidad" value="0">
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
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="agua_red_publica" id="agua_red_publica" value="1">
                                        <label class="form-check-label" for="agua_red_publica">¿Cuenta con agua de red pública?</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="agua_pozo" id="agua_pozo" value="1">
                                        <label class="form-check-label" for="agua_pozo">¿Tiene acceso a agua de pozo?</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="agua_cuerpo" id="agua_cuerpo" value="1">
                                        <label class="form-check-label" for="agua_cuerpo">¿Utiliza agua de cuerpo natural?</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="agua_pipas" id="agua_pipas" value="1">
                                        <label class="form-check-label" for="agua_pipas">¿Recibe agua por pipas?</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="agua_otro" id="agua_otro" value="1">
                                        <label class="form-check-label" for="agua_otro">¿Existe otro tipo de suministro?</label>
                                    </div>

                                    <hr class="my-3">

                                    <h6 class="mb-2">🛢️ Almacenamiento de agua</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="cisterna" id="cisterna" value="1">
                                        <label class="form-check-label" for="cisterna">¿Dispone de cisterna?</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="tinacos" id="tinacos" value="1">
                                        <label class="form-check-label" for="tinacos">¿Cuenta con tinacos?</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="tanque" id="tanque" value="1">
                                        <label class="form-check-label" for="tanque">¿Tiene tanque de almacenamiento?</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="almacenamiento_otro" id="almacenamiento_otro" value="1">
                                        <label class="form-check-label" for="almacenamiento_otro">¿Utiliza otro tipo de almacenamiento?</label>
                                    </div>

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
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="estado_instalacion_sanitaria" class="form-label">¿Cómo está la instalación sanitaria?</label>
                                        <select id="estado_instalacion_sanitaria" name="estado_instalacion_sanitaria" class="form-select filtro-select">
                                            <option value="">Seleccione una opción</option>
                                            <option value="bueno">Bueno</option>
                                            <option value="regular">Regular</option>
                                            <option value="malo">Malo</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="estado_instalacion_electrica" class="form-label">¿Cuál es el estado de la instalación eléctrica?</label>
                                        <select id="estado_instalacion_electrica" name="estado_instalacion_electrica" class="form-select filtro-select">
                                            <option value="">Seleccione una opción</option>
                                            <option value="bueno">Bueno</option>
                                            <option value="regular">Regular</option>
                                            <option value="malo">Malo</option>
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
        </div>
    </div>
</div>