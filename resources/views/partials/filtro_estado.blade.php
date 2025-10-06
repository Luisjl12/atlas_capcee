<div class="filtro-bloque">
    <label for="estado-macroregion">¿En qué macroregión desea filtrar?</label>
    <select id="estado-macroregion" name="macroregion" class="filtro-select">
        <option value="">Seleccione una opción</option>
        @foreach($macroregiones as $macro)
        <option value="{{ $macro->id }}">{{ $macro->nombre_macroregion }}</option>
        @endforeach
    </select>

    <label for="estado-microregion">¿Qué microregión le interesa?</label>
    <select id="estado-microregion" name="microregion" class="filtro-select">
        <option value="">Seleccione una opción</option>
        @foreach($microregiones as $micro)
        <option value="{{ $micro->id }}">{{ $micro->nombre_microregiones }}</option>
        @endforeach
    </select>

    <label for="estado-municipio">¿En qué municipio desea filtrar?</label>
    <select id="estado-municipio" name="municipio" class="filtro-select">
        <option value="">Seleccione una opción</option>
        @foreach($municipios as $mun)
        <option value="{{ $mun->id }}">{{ $mun->nombre_municipio }}</option>
        @endforeach
    </select>

    <label for="estado-nivel">¿Qué nivel educativo desea consultar?</label>
    <select id="estado-nivel" name="nivel" class="filtro-select">
        <option value="">Seleccione una opción</option>
        @foreach($niveles as $nivel)
        <option value="{{ $nivel->nivel }}">
            {{ ucwords(str_replace('_', ' ', $nivel->nivel)) }}
        </option>
        @endforeach
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
</div>