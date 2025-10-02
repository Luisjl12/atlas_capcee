<div class="filtro-bloque">
    <label for="drenaje-macroregion">¿En qué macroregión desea filtrar?</label>
    <select id="drenaje-macroregion" name="macroregion" class="filtro-select">
        <option value="">--Todas--</option>
        @foreach($macroregiones as $m)
        <option value="{{ $m->id }}">{{ $m->nombre_macroregion }}</option>
        @endforeach
    </select>

    <label for="drenaje-microregion">¿Qué microregión le interesa?</label>
    <select id="drenaje-microregion" name="microregion" class="filtro-select">
        <option value="">--Todas--</option>
        @foreach($microregiones as $m)
        <option value="{{ $m->id }}">{{ $m->nombre_microregiones }}</option>
        @endforeach
    </select>

    <label for="drenaje-municipio">¿En qué municipio desea aplicar el filtro?</label>
    <select id="drenaje-municipio" name="municipio" class="filtro-select">
        <option value="">--Todos--</option>
        @foreach($municipios as $mun)
        <option value="{{ $mun->id }}">{{ $mun->nombre_municipio }}</option>
        @endforeach
    </select>

    <label for="drenaje-nivel">¿Qué nivel educativo desea consultar?</label>
    <select id="drenaje-nivel" name="nivel" class="filtro-select">
        <option value="">--Todos--</option>
        @foreach($niveles as $n)
        <option value="{{ $n->nivel }}">{{ $n->nivel }}</option>
        @endforeach
    </select>
</div>

<hr>

<div class="filtro-bloque">
    <label><input type="checkbox" id="drenaje_publico" name="drenaje_publico" value="1"> ¿Cuenta con drenaje público?</label><br>
    <label><input type="checkbox" id="fosa_septica" name="fosa_septica" value="1"> ¿Tiene fosa séptica?</label><br>
    <label><input type="checkbox" id="planta_tratamiento" name="planta_tratamiento" value="1"> ¿Dispone de planta de tratamiento?</label><br>
    <label><input type="checkbox" id="descarga_otro" name="descarga_otro" value="1"> ¿Existe otro tipo de descarga?</label><br>
    <label><input type="checkbox" id="separacion_aguas" name="separacion_aguas" value="1"> ¿Hay separación de aguas?</label>
</div>