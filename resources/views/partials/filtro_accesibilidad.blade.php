{{-- Filtro por región --}}
<div class="filtro-bloque">
    <label for="macroregion">¿En qué macroregión desea filtrar?</label>
    <select name="macroregion" id="accesibilidad-macroregion" class="filtro-select">
        <option value="">-- Selecciona --</option>
        @foreach($macroregiones as $macro)
        <option value="{{ $macro->id }}">{{ $macro->nombre_macroregion }}</option>
        @endforeach
    </select>

    <label for="microregion">¿Qué microregión le interesa?</label>
    <select name="microregion" id="accesibilidad-microregion" class="filtro-select">
        <option value="">-- Selecciona --</option>
        @foreach($microregiones as $micro)
        <option value="{{ $micro->id }}">{{ $micro->nombre_microregiones }}</option>
        @endforeach
    </select>

    <label for="municipio">¿En qué municipio desea aplicar el filtro?</label>
    <select name="municipio" id="accesibilidad-municipio" class="filtro-select">
        <option value="">-- Selecciona --</option>
        @foreach($municipios as $mun)
        <option value="{{ $mun->id }}">{{ $mun->nombre_municipio }}</option>
        @endforeach
    </select>

    <label for="nivel">¿Qué nivel académico desea consultar?</label>
    <select name="nivel" id="accesibilidad-nivel" class="filtro-select">
        <option value="">Seleccione</option>
        @foreach($niveles as $nivel)
        <option value="{{ $nivel->nivel }}">{{ ucfirst($nivel->nivel) }}</option>
        @endforeach
    </select>
</div>

<hr>

{{-- Filtro por accesibilidad --}}
<div class="filtro-bloque">
    <label>¿Cuenta con infraestructura para discapacidad?</label><br>
    <label><input type="radio" name="infraestructura_discapacidad" value="1"> Sí</label>
    <label><input type="radio" name="infraestructura_discapacidad" value="0"> No</label>

    <label>¿Está marcado como sin infraestructura para discapacidad?</label><br>
    <label><input type="radio" name="sin_infraestructura_discapacidad" value="1"> Sí</label>
    <label><input type="radio" name="sin_infraestructura_discapacidad" value="0"> No</label>

    <label for="equipo_discapacidad_categoria">¿Nivel de equipamiento para discapacidad?</label>
    <select name="equipo_discapacidad_categoria" id="equipo_discapacidad_categoria" class="filtro-select">
        <option value="">-- Selecciona --</option>
        <option value="ninguno">Sin equipo</option>
        <option value="bajo">1 a 2 elementos</option>
        <option value="medio">3 a 5 elementos</option>
        <option value="alto">Más de 5 elementos</option>
    </select>
</div>