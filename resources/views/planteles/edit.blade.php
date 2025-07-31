@extends('layouts.app')
@section('title', 'Editar Plantel')

@section('content')
<div class="container mt-4">
    <div class="card-header bg-white border-bottom">
        <a href="{{ route('planteles.index') }}" class="text-decoration-none d-inline-flex align-items-center text-dark">
            <h4 class="mb-4">
                <i class="fas fa-arrow-left "></i>
                <i class="fas fa-clipboard-list"></i> Editar Ficha Base: {{ $plantel->nombre_escuela }}</h2>
            </h4>
        </a>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('planteles.update', $plantel->id) }}" method="POST" class="needs-validation form-ficha-base">
        @csrf
        @method('PUT')
        <div class="form-navigation nav-tabs-text mb-3">
            <span class="nav-tab" data-step="0">I. Identificación</span>
            <span class="nav-tab" data-step="1">II. Ubicación</span>
            <span class="nav-tab" data-step="2">III. Contacto</span>
            <span class="nav-tab" data-step="3">IV. Accesibilidad</span>
            <span class="nav-tab" data-step="4">V. Usuarios</span>
            <span class="nav-tab" data-step="5">VI. Estatus</span>
        </div>
        <div class="tab-content" id="plantelTabsContent">
            <div class="form-section step-section" data-step="0">
                <h4>I. Datos de Identificación</h4>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">CCT:</label>
                        <input type="text" name="cct" class="form-control" value="{{ old('cct', $plantel->cct) }}" readonly>
                        <input type="hidden" name="cct" value="{{$plantel->cct}}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nombre de la Escuela:</label>
                        <input type="text" name="nombre_escuela" class="form-control" value="{{ old('nombre_escuela', $plantel->nombre_escuela) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nivel Educativo:</label>
                        <input type="text" name="nivel_educativo" class="form-control" value="{{ old('nivel_educativo', $plantel->nivel_educativo) }}" required>
                    </div>
                </div>


                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Turno:</label>
                        <input type="text" name="turno" class="form-control" value="{{ old('turno', $plantel->turno) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sostenimiento:</label>
                        <input type="text" name="sostenimiento" class="form-control" value="{{ old('sostenimiento', $plantel->sostenimiento) }}" required>
                    </div>
                </div>
            </div>

            <div class="form-section step-section d-none" data-step="1">
                <h4>II. Ubicación</h4>
                <div class="row mb-3">
                    <!--Municipio-->
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
                    <div class="col-md-4">
                        <label class="form-label">Calle y Número:</label>
                        <input type="text" name="domicilio_calle_numero" class="form-control" value="{{ old('domicilio_calle_numero', $plantel->domicilio_calle_numero) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Colonia:</label>
                        <input type="text" name="domicilio_colonia" class="form-control" value="{{ old('domicilio_colonia', $plantel->domicilio_colonia) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Código Postal:</label>
                        <input type="text" name="domicilio_cp" class="form-control" value="{{ old('domicilio_cp', $plantel->domicilio_cp) }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Latitud:</label>
                        <input type="text" name="latitud" class="form-control" value="{{ old('latitud', $plantel->latitud) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Longitud:</label>
                        <input type="text" name="longitud" class="form-control" value="{{ old('longitud', $plantel->longitud) }}" required>
                    </div>
                </div>
            </div>
            <div class="form-section step-section d-none" data-step="2">
                <h4>III. Contacto y Director</h4>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Teléfono del Plantel:</label>
                        <input type="text" name="telefono_plantel" class="form-control" value="{{ old('telefono_plantel', $plantel->telefono_plantel) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Correo Institucional:</label>
                        <input type="text" name="correo_institucional" class="form-control" value="{{ old('correo_institucional', $plantel->correo_institucional) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nombre del Director:</label>
                        <input type="text" name="nombre_director_registrado" class="form-control" value="{{ old('nombre_director_registrado', $plantel->nombre_director_registrado) }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Director Asignado:</label>
                        <select name="id_director_asignado" class="form-select" required>
                            <option value="">Seleccione un director</option>
                            @foreach($directores as $director)
                            <option value="{{ $director->id }}" {{ old('id_director_asignado', $plantel->id_director_asignado) == $director->id ? 'selected' : '' }}>
                                {{ $director->nombre_completo }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-section step-section d-none" data-step="3">
                <h4>IV. Accesibilidad</h4>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="accesibilidad_rampas" id="accesibilidad_rampas" value="1" {{ old('accesibilidad_rampas', $plantel->accesibilidad_rampas) ? 'checked' : '' }}>
                    <label class="form-check-label" for="accesibilidad_rampas">Rampas</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="accesibildad_banos_adaptados" id="accesibildad_banos_adaptados" value="1" {{ old('accesibildad_banos_adaptados', $plantel->accesibildad_banos_adaptados) ? 'checked' : '' }}>
                    <label class="form-check-label" for="accesibildad_banos_adaptados">Baños Adaptados</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="accesibilidad_sanaletica_braille" id="accesibilidad_sanaletica_braille" value="1" {{ old('accesibilidad_sanaletica_braille', $plantel->accesibilidad_sanaletica_braille) ? 'checked' : '' }}>
                    <label class="form-check-label" for="accesibilidad_sanaletica_braille">Señalética Braille</label>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="accesibilidad_otros">Otros (Accesibilidad):</label>
                    <input type="text" class="form-control" name="accesibilidad_otros" value="{{ old('accesibilidad_otros', $plantel->accesibilidad_otros) }}">
                </div>
            </div>
            <div class="form-section step-section d-none" data-step="4">
                <h4>V. Total Usuarios Planteles</h4>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Total Alumnos:</label>
                        <input type="number" name="total_alumnos" class="form-control" value="{{ old('total_alumnos', $plantel->total_alumnos) }}" min="0" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Total Docentes:</label>
                        <input type="number" name="total_docentes" class="form-control" value="{{ old('total_docentes', $plantel->total_docentes) }}" min="0" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Total Administrativos:</label>
                        <input type="number" name="total_administrativos" class="form-control" value="{{ old('total_administrativos', $plantel->total_administrativos) }}" min="0" required>
                    </div>
                </div>
            </div>
            <div class="form-section step-section d-none" data-step="5">
                <h4>VI. Estatus(Admin)</h4>
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
            </div>

        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>Actualizar Datos Del Plantel</button>
        </div>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const secciones = document.querySelectorAll('.step-section');
        const pestañas = document.querySelectorAll('.nav-tab');

        function mostrarSeccion(numero) {
            for (let i = 0; i < secciones.length; i++) {
                if (i === numero) {
                    secciones[i].classList.add('active');
                } else {
                    secciones[i].classList.remove('active');
                }
            }

            for (let i = 0; i < pestañas.length; i++) {
                if (i === numero) {
                    pestañas[i].classList.add('active');
                } else {
                    pestañas[i].classList.remove('active');
                }
            }
        }

        // Cuando se hace clic en alguna pestaña
        for (let i = 0; i < pestañas.length; i++) {
            pestañas[i].addEventListener('click', function() {
                mostrarSeccion(i);
            });
        }
        mostrarSeccion(0);
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const secciones = document.querySelectorAll('.step-section');
        const pestañas = document.querySelectorAll('.nav-tab');

        function mostrarSeccion(numero) {
            secciones.forEach((seccion, i) => {
                if (i === numero) {
                    seccion.classList.remove('d-none');
                } else {
                    seccion.classList.add('d-none');
                }
            });

            pestañas.forEach((pestana, i) => {
                if (i === numero) {
                    pestana.classList.add('active');
                } else {
                    pestana.classList.remove('active');
                }
            });
        }

        pestañas.forEach((pestana, i) => {
            pestana.addEventListener('click', () => {
                mostrarSeccion(i);
            });
        });

        // Mostrar la primera al cargar
        mostrarSeccion(0);
    });
</script>
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