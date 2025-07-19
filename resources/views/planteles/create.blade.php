@extends('layouts.app')

@section('title', 'Agregar Plantel')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Agregar Plantel</h2>

    {{-- Mostrar errores de validación --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Formulario --}}
    <form action="{{ route('planteles.store') }}" method="POST">
        @csrf

        {{-- Sección I: Datos del Plantel --}}
        <h5 class="text-primary">I. Datos Generales</h5>
        <hr>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="cct" class="form-label">CCT:</label>
                <input type="text" class="form-control" name="cct" value="{{ old('cct') }}" required>
            </div>
            <div class="col-md-8">
                <label for="nombre_escuela" class="form-label">Nombre de la Escuela:</label>
                <input type="text" class="form-control" name="nombre_escuela" value="{{ old('nombre_escuela') }}" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="nivel_educativo" class="form-label">Nivel Educativo:</label>
                <input type="text" class="form-control" name="nivel_educativo" value="{{ old('nivel_educativo') }}" required>
            </div>
            <div class="col-md-4">
                <label for="turno" class="form-label">Turno:</label>
                <input type="text" class="form-control" name="turno" value="{{ old('turno') }}" required>
            </div>
            <div class="col-md-4">
                <label for="sostenimiento" class="form-label">Sostenimiento:</label>
                <input type="text" class="form-control" name="sostenimiento" value="{{ old('sostenimiento') }}" required>
            </div>
        </div>

        {{-- Sección II: Ubicación --}}
        <h5 class="text-danger mt-4">II. Ubicación</h5>
        <hr style="border: 1px solid #a10000;">

        <div class="row mb-3">
            {{-- MUNICIPIO --}}
            <div class="col-md-4">
                <label for="id_municipio" class="form-label">Municipio:</label>
                <select name="id_municipio" id="id_municipio" class="form-control">
                    <option value="">Seleccione un municipio</option>
                    @foreach($municipios as $municipio)
                    <option value="{{ $municipio->id }}">{{ $municipio->nombre_municipio }}</option>
                    @endforeach
                    <option value="nuevo">Otro...</option> {{-- <-- esta línea agrega la opción para activar el input --}}
                </select>



                <input type="text" name="nuevo_municipio" id="input_nuevo_municipio" class="form-control mt-2 d-none" placeholder="Nuevo municipio">
            </div>

            {{-- LOCALIDAD --}}
            <div class="col-md-4">
                <label for="id_localidad" class="form-label">Localidad:</label>
                <select name="id_localidad" id="select_localidad" class="form-control" disabled>
                    <option value="">Seleccione...</option>
                    @foreach($localidades as $localidad)
                    <option value="{{ $localidad->id }}">{{ $localidad->nombre_localidad }}</option>
                    @endforeach
                    <option value="nuevo">Otro...</option> {{-- <-- esta línea también --}}
                </select>

                <input type="text" id="input_nuevo_localidad" name="nuevo_localidad" class="form-control mt-2 d-none" placeholder="Ingrese nueva localidad">
            </div>

            {{-- CORDE --}}
            <div class="col-md-4">
                <label for="id_corde" class="form-label">CORDE:</label>
                <select name="id_corde" id="select_corde" class="form-select">
                    <option value="">Seleccione...</option>
                    @foreach($cordes as $corde)
                    <option value="{{ $corde->id }}" {{ old('id_corde', $plantel->id_corde ?? '') == $corde->id ? 'selected' : '' }}>
                        {{ $corde->nombre_corde }}
                    </option>
                    @endforeach

                </select>
                <input type="text" name="nuevo_corde" id="input_nuevo_corde" class="form-control mt-2 d-none" placeholder="Nuevo CORDE">
            </div>
        </div>


        <div class="row mb-3">
            <div class="col-md-6">
                <label for="domicilio_calle_numero" class="form-label">Calle y Número:</label>
                <input type="text" class="form-control" name="domicilio_calle_numero" value="{{ old('domicilio_calle_numero') }}" required>
            </div>
            <div class="col-md-4">
                <label for="domicilio_colonia" class="form-label">Colonia:</label>
                <input type="text" class="form-control" name="domicilio_colonia" value="{{ old('domicilio_colonia') }}" required>
            </div>
            <div class="col-md-2">
                <label for="domicilio_cp" class="form-label">C.P.:</label>
                <input type="text" class="form-control" name="domicilio_cp" value="{{ old('domicilio_cp') }}" required>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <label for="latitud" class="form-label">Latitud:</label>
                <input type="text" class="form-control" name="latitud" value="{{ old('latitud') }}" required>
            </div>
            <div class="col-md-6">
                <label for="longitud" class="form-label">Longitud:</label>
                <input type="text" class="form-control" name="longitud" value="{{ old('longitud') }}" required>
            </div>
        </div>
        {{-- Sección III: Contacto y director --}}
        <h5 class="text-danger mt-4">III. Contacto y director</h5>
        <hr style="border: 1px solid #a10000;">

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="telefono_plantel" class="form-label">Telefono del Plantel:</label>
                <input type="text" class="form-control" name="telefono_plantel" value="{{ old('telefono_plantel') }}" required>
            </div>
            <div class="col-md-4">
                <label for="correo_institucional" class="form-label">Correo Institucional:</label>
                <input type="text" class="form-control" name="correo_institucional" value="{{ old('correo_institucional') }}" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="nombre_director_registrado" class="form-label">Nombre del Director:*</label>
                <input type="text" class="form-control" name="nombre_director_registrado" value="{{old('nombre_director_registrado')}}" required>
            </div>
            <div class="col-md-4">
                <label for="id_director_asignado" class="form-label">Director Asignado(Usuario):</label>
                <select name="id_director_asignado" class="form-select" required>
                    <option value="">Seleccione...</option>
                    @foreach($directores as $director)
                    <option value="{{ $director->id }}" {{ old('id_director_asignado') == $director->id ? 'selected' : '' }}>
                        {{ $director->nombre_completo}}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Sección IV: Accesibilidad --}}
        <h5 class="text-danger mt-4">IV. Accesibilidad</h5>
        <hr style="border: 1px solid #a10000;">
        <div class="mb-3">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="accesibilidad_rampas" id="accesibilidad_rampas" value="1"
                    {{ old('accesibilidad_rampas') ? 'checked' : '' }}>
                <label class="form-check-label" for="accesibilidad_rampas">Rampas</label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="accesibilidad_banos_adaptados" id="accesibilidad_banos_adaptados" value="1"
                    {{ old('accesibilidad_banos_adaptados') ? 'checked' : '' }}>
                <label class="form-check-label" for="accesibilidad_banos_adaptados">Baños Adaptados</label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="accesibilidad_sanaletica_braille" id="accesibilidad_sanaletica_braille" value="1"
                    {{ old('accesibilidad_sanaletica_braille') ? 'checked' : '' }}>
                <label class="form-check-label" for="accesibilidad_sanaletica_braille">Señalética Braille</label>
            </div>
        </div>
        <div class="mb-3">
            <label for="accesibilidad_otros" class="form-label">Otros (Accesibilidad):</label>
            <input type="text" class="form-control" name="accesibilidad_otros" id="accesibilidad_otros"
                value="{{ old('accesibilidad_otros') }}" placeholder="Especificar...">
        </div>

        {{-- Sección V: Total Usuarios Planteles --}}
        <h5 class="text-danger mt-4">V. Total Usuarios Planteles</h5>
        <hr style="border: 1px solid #a10000;">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="total_alumnos" class="form-label">Total Alumnos:</label>
                <input type="number" class="form-control" name="total_alumnos" value="{{ old('total_alumnos') }}" required>
            </div>
            <div class="col-md-6">
                <label for="total_docentes" class="form-label">Total Docentes</label>
                <input type="number" class="form-control" name="total_docentes" value="{{old('total_docentes')}}" required>
            </div>
            <div class="col-md-6">
                <label for="total_administrativos" class="form-label">Total Administrativos</label>
                <input type="number" class="form-control" name="total_administrativos" value="{{old('total_administrativos')}}" required>
            </div>
        </div>

        {{-- Sección VI: estauts plantel(admin) --}}
        <h5 class="text-danger mt-4">VI. Estatus Plantel(Admin)</h5>
        <hr style="border: 1px solid #a10000;">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="estatus_plantel" class="form-label">Estatus:</label>
                <select name="estatus_plantel" class="form-select" required>
                    <option value="">Seleccione una opcion</option>
                    <option value="ACTIVO" {{old ('estatus_plantel')=='ACTIVO' ? 'selected': ''}}>ACTIVO</option>
                    <option value="INACTIVO" {{old ('estatus_plantel')=='INACTIVO' ? 'selected': ''}}>INACTIVO</option>
                    <option value="EN_REVISION" {{old ('estatus_plantel')=='EN_REVISION' ? 'selected': ''}}>EN REVISION</option>

                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Guardar Plantel</button>
        <a href="{{ route('planteles.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
    function toggleInput(selectId, inputId) {
        const select = document.getElementById(selectId);
        const input = document.getElementById(inputId);

        select.addEventListener("change", function() {
            if (this.value === "nuevo") {
                input.classList.remove("d-none");
                input.required = true;
            } else {
                input.classList.add("d-none");
                input.required = false;
                input.value = '';
            }


            // Si se seleccionó un municipio, hacer solicitud AJAX para obtener localidades
            if (selectId === "id_municipio" && this.value !== "" && this.value !== "nuevo") {
                fetch(`/municipios/${this.value}/localidades`)
                    .then(response => response.json())
                    .then(data => {
                        const localidadSelect = document.getElementById("select_localidad");
                        localidadSelect.innerHTML = '<option value="">Seleccione una localidad</option>';
                        localidadSelect.disabled = false;

                        data.forEach(localidad => {
                            const option = document.createElement("option");
                            option.value = localidad.id;
                            option.textContent = localidad.nombre_localidad;
                            localidadSelect.appendChild(option);
                        });
                        const otroOption = document.createElement("option");
                        otroOption.value = "nuevo";
                        otroOption.textContent = "Otro...";
                        localidadSelect.appendChild(otroOption);

                    })
                    .catch(error => console.error("Error al cargar localidades:", error));
            }
        });
    }

    // Aplica la función a cada combo
    toggleInput("id_municipio", "input_nuevo_municipio");
    toggleInput("select_localidad", "input_nuevo_localidad");
    toggleInput("select_corde", "input_nuevo_corde");
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const municipioSelect = document.getElementById("id_municipio");
        const inputNuevoMunicipio = document.getElementById("input_nuevo_municipio");
        const localidadSelect = document.getElementById("select_localidad");

        function actualizarEstadoLocalidad() {
            const seleccion = municipioSelect.value;

            // Si seleccionó un municipio válido o seleccionó "nuevo" y escribió algo
            const municipioValido = (
                seleccion !== "" && seleccion !== "nuevo"
            ) || (
                seleccion === "nuevo" && inputNuevoMunicipio.value.trim() !== ""
            );

            // Habilita o deshabilita el select de localidad
            localidadSelect.disabled = !municipioValido;
        }

        // Escucha cambios en el select de municipio
        municipioSelect.addEventListener("change", actualizarEstadoLocalidad);

        // Escucha cambios en el input de nuevo municipio
        inputNuevoMunicipio.addEventListener("input", actualizarEstadoLocalidad);

        // Ejecuta una vez al cargar
        actualizarEstadoLocalidad();
    });
</script>



@endsection