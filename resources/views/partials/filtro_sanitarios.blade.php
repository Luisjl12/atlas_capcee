{{-- Filtro por región y nivel --}}
<div class="filtro-bloque">
    <label>Macroregión</label>
    <select name="macroregion" id="sanitarios-macroregion">
        <option value="">-- Selecciona --</option>
        @foreach($macroregiones as $macro)
        <option value="{{ $macro->id }}">{{ $macro->nombre_macroregion }}</option>
        @endforeach
    </select>

    <label>Microregión</label>
    <select name="microregion" id="sanitarios-microregion">
        <option value="">-- Selecciona --</option>
        @foreach($microregiones as $micro)
        <option value="{{ $micro->id }}">{{ $micro->nombre_microregiones }}</option>
        @endforeach
    </select>

    <label>Municipio</label>
    <select name="municipio" id="sanitarios-municipio">
        <option value="">-- Selecciona --</option>
        @foreach($municipios as $mun)
        <option value="{{ $mun->id }}">{{ $mun->nombre_municipio }}</option>
        @endforeach
    </select>

    <label>Nivel educativo</label>
    <select name="nivel" id="sanitarios-nivel">
        <option value="">-- Selecciona --</option>
        @foreach($niveles as $nivel)
        <option value="{{ $nivel->nivel }}">{{ ucfirst($nivel->nivel) }}</option>
        @endforeach
    </select>
</div>

<hr>

{{-- Filtro por sanitarios --}}
<div class="filtro-bloque">
    <label>¿Estado general de los baños?</label>
    <select name="estado_banos">
        <option value="">-- Selecciona --</option>
        <option value="bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>

    <label>¿Cantidad mínima de baños para hombres?</label>
    <input type="number" name="banos_hombres_min" min="0">

    <label>¿Cantidad mínima de baños para mujeres?</label>
    <input type="number" name="banos_mujeres_min" min="0">

    <label>¿Cantidad mínima de lavamanos?</label>
    <input type="number" name="lavamanos_min" min="0">

    <label>¿Estado de lavamanos?</label>
    <select name="estado_lavamanos">
        <option value="">-- Selecciona --</option>
        <option value="bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>

    <label>¿Cantidad mínima de tomas de bebederos?</label>
    <input type="number" name="tomas_bebederos_min" min="0">

    <label>¿Estado de bebederos?</label>
    <select name="estado_bebederos">
        <option value="">-- Selecciona --</option>
        <option value="bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
</div>