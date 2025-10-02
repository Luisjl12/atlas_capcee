{{-- Macroregión --}}
<div class="filtro-bloque">
    <label for="macroregion">¿En qué macroregión desea filtrar?</label>
    <select name="macroregion" id="seguridad-macroregion" class="filtro-select">
        <option value="">-- Selecciona --</option>
        @foreach($macroregiones as $macro)
        <option value="{{ $macro->id }}">{{ $macro->nombre_macroregion }}</option>
        @endforeach
    </select>

    <label for="microregion">¿Qué microregión le interesa?</label>
    <select name="microregion" id="seguridad-microregion" class="filtro-select">
        <option value="">-- Selecciona --</option>
        @foreach($microregiones as $micro)
        <option value="{{ $micro->id }}">{{ $micro->nombre_microregiones }}</option>
        @endforeach
    </select>

    <label for="municipio">¿En qué municipio desea aplicar el filtro?</label>
    <select name="municipio" id="seguridad-municipio" class="filtro-select">
        <option value="">-- Selecciona --</option>
        @foreach($municipios as $mun)
        <option value="{{ $mun->id }}">{{ $mun->nombre_municipio }}</option>
        @endforeach
    </select>

    <label for="nivel">¿Qué nivel académico desea consultar?</label>
    <select name="nivel" id="seguridad-nivel" class="filtro-select">
        <option value="">Seleccione</option>
        @foreach($niveles as $nivel)
        <option value="{{ $nivel->nivel }}">{{ ucfirst($nivel->nivel) }}</option>
        @endforeach
    </select>
</div>

<hr>

<div class="filtro-bloque">
    <label>¿Cuenta con dictamen de Protección Civil?</label><br>
    <label><input type="radio" name="proteccion_civil" id="proteccion_civil" value="1"> Sí</label>
    <label><input type="radio" name="proteccion_civil" id="proteccion_civil" value="0"> No</label>

    <label>¿La barda está completa?</label><br>
    <label><input type="radio" name="barda_completa" id="barda_completa" value="1"> Sí</label>
    <label><input type="radio" name="barda_completa" id="barda_completa" value="0"> No</label>

    <label for="estado_barda">¿Cuál es el estado de la barda?</label>
    <select name="estado_barda" id="estado_barda" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <option value="bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>

    <label for="estado_cerca">¿Cuál es el estado de la cerca?</label>
    <select name="estado_cerca" id="estado_cerca" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <option value="bueno">Bueno</option>
        <option value="regular">Regular</option>
        <option value="malo">Malo</option>
    </select>
</div>