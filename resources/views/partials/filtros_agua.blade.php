<div class="filtro-bloque">
    <label for="agua-macroregion">¿En qué macroregión desea filtrar?</label>
    <select name="macroregion" id="agua-macroregion" class="filtro-select">
        <option value="">-- Selecciona --</option>
        @foreach($macroregiones as $macro)
        <option value="{{ $macro->id }}">{{ $macro->nombre_macroregion }}</option>
        @endforeach
    </select>

    <label for="agua-microregion">¿Qué microregión le interesa?</label>
    <select name="microregion" id="agua-microregion" class="filtro-select">
        <option value="">-- Selecciona --</option>
        @foreach($microregiones as $micro)
        <option value="{{ $micro->id }}">{{ $micro->nombre_microregiones }}</option>
        @endforeach
    </select>

    <label for="agua-municipio">¿En qué municipio desea aplicar el filtro?</label>
    <select name="municipio" id="agua-municipio" class="filtro-select">
        <option value="">-- Selecciona --</option>
        @foreach($municipios as $muni)
        <option value="{{ $muni->id }}">{{ $muni->nombre_municipio }}</option>
        @endforeach
    </select>

    <label for="agua-nivel">¿Qué nivel educativo desea consultar?</label>
    <select name="nivel" id="agua-nivel" class="filtro-select">
        <option value="">-- Selecciona --</option>
        @foreach($niveles as $nivel)
        <option value="{{ $nivel->nivel }}">{{ ucfirst($nivel->nivel) }}</option>
        @endforeach
    </select>
</div>

<hr>

<div class="filtro-bloque">
    <label>¿Qué tipo de suministro de agua desea filtrar?</label><br>
    <label><input type="checkbox" name="agua_red_publica" id="agua_red_publica" value="1"> ¿Cuenta con agua de red pública?</label><br>
    <label><input type="checkbox" name="agua_pozo" id="agua_pozo" value="1"> ¿Tiene acceso a agua de pozo?</label><br>
    <label><input type="checkbox" name="agua_cuerpo" id="agua_cuerpo" value="1"> ¿Utiliza agua de cuerpo natural?</label><br>
    <label><input type="checkbox" name="agua_pipas" id="agua_pipas" value="1"> ¿Recibe agua por pipas?</label><br>
    <label><input type="checkbox" name="agua_otro" id="agua_otro" value="1"> ¿Existe otro tipo de suministro?</label><br>
    <label><input type="checkbox" name="cisterna" id="cisterna" value="1"> ¿Dispone de cisterna?</label><br>
    <label><input type="checkbox" name="tinacos" id="tinacos" value="1"> ¿Cuenta con tinacos?</label><br>
    <label><input type="checkbox" name="tanque" id="tanque" value="1"> ¿Tiene tanque de almacenamiento?</label><br>
    <label><input type="checkbox" name="almacenamiento_otro" id="almacenamiento_otro" value="1"> ¿Utiliza otro tipo de almacenamiento?</label>
</div>