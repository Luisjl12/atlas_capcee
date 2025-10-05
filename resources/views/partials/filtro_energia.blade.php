<div class="filtro-bloque">
    <label for="energia-macroregion">¿En qué macroregión desea filtrar?</label>
    <select id="energia-macroregion" name="macroregion" class="filtro-select">
        <option value="">--Selecciona--</option>
        @foreach($macroregiones as $m)
        <option value="{{ $m->id }}">{{ $m->nombre_macroregion }}</option>
        @endforeach
    </select>

    <label for="energia-microregion">¿Qué microregión le interesa?</label>
    <select id="energia-microregion" name="microregion" class="filtro-select">
        <option value="">--Selecciona--</option>
        @foreach($microregiones as $m)
        <option value="{{ $m->id }}">{{ $m->nombre_microregiones }}</option>
        @endforeach
    </select>

    <label for="energia-municipio">¿En qué municipio desea aplicar el filtro?</label>
    <select id="energia-municipio" name="municipio" class="filtro-select">
        <option value="">--Selecciona--</option>
        @foreach($municipios as $mun)
        <option value="{{ $mun->id }}">{{ $mun->nombre_municipio }}</option>
        @endforeach
    </select>

    <label for="energia-nivel">¿Qué nivel educativo desea consultar?</label>
    <select id="energia-nivel" name="nivel" class="filtro-select">
        <option value="">--Selecciona--</option>
        @foreach($niveles as $n)
        <option value="{{ $n->nivel }}">
            {{ ucwords(str_replace('_', ' ', $n->nivel)) }}
        </option>
        @endforeach
    </select>
</div>

<hr>

<div class="filtro-bloque">
    <label><input type="checkbox" id="suministro_energia" name="suministro_energia" value="1"> ¿Cuenta con suministro de energía?</label><br>
    <label><input type="checkbox" id="energia_paneles_solares" name="energia_paneles_solares" value="1"> ¿Dispone de paneles solares?</label><br>
    <label><input type="checkbox" id="energia_planta" name="energia_planta" value="1"> ¿Tiene planta de energía?</label>
</div>