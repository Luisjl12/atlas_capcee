<!-- Modal Multifiltro -->
<div class="modal fade" id="modalMultifiltro" tabindex="-1" aria-labelledby="modalMultifiltroLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
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
                                <div class="accordion-body">
                                    <label>Macroregión</label>
                                    <select name="macroregion" class="form-select">
                                        <option value="">-- Selecciona --</option>
                                        @foreach($macroregiones as $macro)
                                        <option value="{{ $macro->id }}">{{ $macro->nombre_macroregion }}</option>
                                        @endforeach
                                    </select>

                                    <label class="mt-2">Microregión</label>
                                    <select name="microregion" class="form-select">
                                        <option value="">-- Selecciona --</option>
                                        @foreach($microregiones as $micro)
                                        <option value="{{ $micro->id }}">{{ $micro->nombre_microregiones }}</option>
                                        @endforeach
                                    </select>

                                    <label class="mt-2">Municipio</label>
                                    <select name="municipio" class="form-select">
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
                                <div class="accordion-body">
                                    <label>Nivel</label>
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
                                <div class="accordion-body">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="suministro_energia" value="1">
                                        <label class="form-check-label">Suministro de energía</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="energia_paneles_solares" value="1">
                                        <label class="form-check-label">Paneles solares</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="energia_planta" value="1">
                                        <label class="form-check-label">Planta eléctrica</label>
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
                                <div class="accordion-body">
                                    <label>Rango de superficie</label>
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
                                <div class="accordion-body">
                                    <label>Infraestructura para discapacidad</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="infraestructura_discapacidad" value="1">
                                        <label class="form-check-label">Sí</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="infraestructura_discapacidad" value="0">
                                        <label class="form-check-label">No</label>
                                    </div>

                                    <label class="mt-3">Sin infraestructura para discapacidad</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="sin_infraestructura_discapacidad" value="1">
                                        <label class="form-check-label">Sí</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="sin_infraestructura_discapacidad" value="0">
                                        <label class="form-check-label">No</label>
                                    </div>

                                    <label class="mt-3">Nivel de equipamiento</label>
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

                        <!-- Mas secciones -->

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