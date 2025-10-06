{{-- Filtro por región y nivel --}}
<div class="filtro-bloque">
    <label>¿En que macroregión desea consultar?</label>
    <select name="macroregion" id="sanitarios-macroregion" class="filtro-select">
        <option value="">-- Selecciona --</option>
        @foreach($macroregiones as $macro)
        <option value="{{ $macro->id }}">{{ $macro->nombre_macroregion }}</option>
        @endforeach
    </select>

    <label>En que microregión desea consultar?</label>
    <select name="microregion" id="sanitarios-microregion" class="filtro-select">
        <option value="">-- Selecciona --</option>
        @foreach($microregiones as $micro)
        <option value="{{ $micro->id }}">{{ $micro->nombre_microregiones }}</option>
        @endforeach
    </select>

    <label>¿En que municipio desea consultar?</label>
    <select name="municipio" id="sanitarios-municipio" class="filtro-select">
        <option value="">-- Selecciona --</option>
        @foreach($municipios as $mun)
        <option value="{{ $mun->id }}">{{ $mun->nombre_municipio }}</option>
        @endforeach
    </select>

    <label>¿Que nivel educativo quieres consultar?</label>
    <select name="nivel" id="sanitarios-nivel" class="filtro-select">
        <option value="">-- Selecciona --</option>
        @foreach($niveles as $nivel)
        @php
        $textoNivel = ucwords(str_replace('_', ' ', $nivel->nivel));
        @endphp
        <option value="{{ $nivel->nivel }}">{{ $textoNivel }}</option>
        @endforeach

    </select>
</div>

<hr>

{{-- Filtro por sanitarios --}}
<div class="filtro-bloque">
    <label>¿Estado general de los baños?</label>
    <select name="estado_banos" class="filtro-select">
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
    <select name="estado_lavamanos" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <option value="bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>

    <label>¿Cantidad mínima de tomas de bebederos?</label>
    <input type="number" name="tomas_bebederos_min" min="0">

    <label>¿Estado de bebederos?</label>
    <select name="estado_bebederos" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <option value="bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
</div>