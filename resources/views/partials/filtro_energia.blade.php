<label for="energia-macroregion">Macroregión:</label>
<select id="energia-macroregion" name="macroregion">
    <option value="">--Selecciona--</option>
    @foreach($macroregiones as $m)
    <option value="{{ $m->id }}">{{ $m->nombre_macroregion }}</option>
    @endforeach
</select><br>

<label for="energia-microregion">Microregión:</label>
<select id="energia-microregion" name="microregion">
    <option value="">--Selecciona--</option>
    @foreach($microregiones as $m)
    <option value="{{ $m->id }}">{{ $m->nombre_microregiones }}</option>
    @endforeach
</select><br>

<label for="energia-municipio">Municipio:</label>
<select id="energia-municipio" name="municipio">
    <option value="">--Selecciona--</option>
    @foreach($municipios as $mun)
    <option value="{{ $mun->id }}">{{ $mun->nombre_municipio }}</option>
    @endforeach
</select><br>

<label for="energia-nivel">Nivel educativo:</label>
<select id="energia-nivel" name="nivel">
    <option value="">--Selecciona--</option>
    @foreach($niveles as $n)
    <option value="{{ $n->nivel }}">{{ $n->nivel }}</option>
    @endforeach
</select><br><br>

<label>
    <input type="checkbox" id="suministro_energia" name="suministro_energia" value="1">
    Cuenta con suministro de energía
</label><br>
<label>
    <input type="checkbox" id="energia_paneles_solares" name="energia_paneles_solares" value="1">
    Cuenta con paneles solares
</label><br>
<label>
    <input type="checkbox" id="energia_planta" name="energia_planta" value="1">
    Cuenta con planta de energía
</label>