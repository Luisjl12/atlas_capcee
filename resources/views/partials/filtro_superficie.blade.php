<div class="filtro-bloque">
    <label for="filtro-macroregion">¿En qué macroregión desea filtrar?</label>
    <select id="filtro-macroregion" name="macroregion" class="filtro-select">
        <option value="">-- Selecciona --</option>
        @foreach($macroregiones as $macro)
        <option value="{{ $macro->id }}">{{ $macro->nombre_macroregion }}</option>
        @endforeach
    </select>

    <label for="filtro-microregion">¿Qué microregión le interesa?</label>
    <select id="filtro-microregion" name="microregion" class="filtro-select">
        <option value="">-- Selecciona --</option>
        @foreach($microregiones as $micro)
        <option value="{{ $micro->id }}">{{ $micro->nombre_microregiones }}</option>
        @endforeach
    </select>

    <label for="filtro-municipio">¿En qué municipio desea aplicar el filtro?</label>
    <select id="filtro-municipio" name="municipio" class="filtro-select">
        <option value="">-- Selecciona --</option>
        @foreach($municipios as $muni)
        <option value="{{ $muni->id }}">{{ $muni->nombre_municipio }}</option>
        @endforeach
    </select>

    <label for="filtro-nivel">¿Qué nivel educativo desea consultar?</label>
    <select id="filtro-nivel" name="nivel" class="filtro-select">
        <option value="">-- Selecciona --</option>
        @foreach($niveles as $nivel)
        <option value="{{ $nivel->nivel }}">
            {{ ucwords(str_replace('_', ' ', $nivel->nivel)) }}
        </option>

        @endforeach
    </select>
</div>

<div class="filtro-bloque">
    <label for="filtro-superficie">¿Qué rango de superficie desea filtrar?</label>
    <select id="filtro-superficie" name="superficie" class="filtro-select">
        <option value="">-- Selecciona --</option>
        @foreach($rangosSuperficie as $rango)
        <option value="{{ $rango->rango }}">{{ ucwords(str_replace('_', ' ', $rango->rango)) }} m²</option>
        @endforeach
    </select>
</div>