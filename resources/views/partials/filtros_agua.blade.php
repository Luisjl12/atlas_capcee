<div class="filtro-bloque">
    <label>Macroregión</label>
    <select name="macroregion" id="agua-macroregion">
        <option value="">-- Selecciona --</option>
        @foreach($macroregiones as $macro)
        <option value="{{ $macro->id }}">{{ $macro->nombre_macroregion }}</option>
        @endforeach
    </select>

    <label>Microregión</label>
    <select name="microregion" id="agua-microregion">
        <option value="">-- Selecciona --</option>
        @foreach($microregiones as $micro)
        <option value="{{ $micro->id }}">{{ $micro->nombre_microregiones }}</option>
        @endforeach
    </select>

    <label>Municipio</label>
    <select name="municipio" id="agua-municipio">
        <option value="">-- Selecciona --</option>
        @foreach($municipios as $muni)
        <option value="{{ $muni->id }}">{{ $muni->nombre_municipio }}</option>
        @endforeach
    </select>

    <label>Nivel educativo</label>
    <select name="nivel" id="agua-nivel">
        <option value="">-- Selecciona --</option>
        @foreach($niveles as $nivel)
        <option value="{{ $nivel->nivel }}">{{ ucfirst($nivel->nivel) }}</option>
        @endforeach
    </select>
</div>

<hr>

<div class="filtro-bloque">
    <label>Suministro de agua</label>
    @foreach(['agua_red_publica','agua_pozo','agua_cuerpo','agua_pipas','agua_otro','cisterna','tinacos','tanque','almacenamiento_otro'] as $campo)
    <div>
        <input type="checkbox" name="{{ $campo }}" id="{{ $campo }}" value="1">
        <label for="{{ $campo }}">{{ ucwords(str_replace('_', ' ', $campo)) }}</label>
    </div>
    @endforeach
</div>