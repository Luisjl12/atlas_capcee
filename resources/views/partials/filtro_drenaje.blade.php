<label for="drenaje-macroregion">Macroregión:</label>
<select id="drenaje-macroregion" name="macroregion">
    <option value="">--Todas--</option>
    @foreach($macroregiones as $m)
    <option value="{{ $m->id }}">{{ $m->nombre_macroregion }}</option>
    @endforeach
</select><br>

<label for="drenaje-microregion">Microregión:</label>
<select id="drenaje-microregion" name="microregion">
    <option value="">--Todas--</option>
    @foreach($microregiones as $m)
    <option value="{{ $m->id }}">{{ $m->nombre_microregiones }}</option>
    @endforeach
</select><br>

<label for="drenaje-municipio">Municipio:</label>
<select id="drenaje-municipio" name="municipio">
    <option value="">--Todos--</option>
    @foreach($municipios as $mun)
    <option value="{{ $mun->id }}">{{ $mun->nombre_municipio }}</option>
    @endforeach
</select><br>

<label for="drenaje-nivel">Nivel educativo:</label>
<select id="drenaje-nivel" name="nivel">
    <option value="">--Todos--</option>
    @foreach($niveles as $n)
    <option value="{{ $n->nivel }}">{{ $n->nivel }}</option>
    @endforeach
</select><br><br>

<label><input type="checkbox" id="drenaje_publico" name="drenaje_publico" value="1"> Drenaje público</label><br>
<label><input type="checkbox" id="fosa_septica" name="fosa_septica" value="1"> Fosa séptica</label><br>
<label><input type="checkbox" id="planta_tratamiento" name="planta_tratamiento" value="1"> Planta de tratamiento</label><br>
<label><input type="checkbox" id="descarga_otro" name="descarga_otro" value="1"> Otro tipo de descarga</label><br>
<label><input type="checkbox" id="separacion_aguas" name="separacion_aguas" value="1"> Separación de aguas</label>