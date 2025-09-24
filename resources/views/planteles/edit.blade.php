@extends('layouts.app')

@section('title', 'Agregar Plantel')

@section('content')
<div class="container mt-4">

    <div class="card-header bg-white border-bottom mb-4">
        <a href="{{ route('planteles.index') }}" class="text-decoration-none d-inline-flex align-items-center text-dark">
            <h4 class="mb-0">
                <i class="fas fa-arrow-left "></i>
                <i class="fas fa-clipboard-list"></i> Guardar/Editar información: {{ $plantel->nombre_escuela }}
                <small class="text-muted">(CCT: {{ $plantel->cct }})</small>
            </h4>
        </a>
    </div>
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    @endif


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
    <div class="form-navigation nav-tabs-text mb-3">
        <span class="nav-tab" data-step="0">I. Identificación</span>
        <span class="nav-tab" data-step="1">II. Ubicación</span>
        <span class="nav-tab" data-step="2">III. Contacto</span>
        <span class="nav-tab" data-step="3">IV. Accesibilidad</span>
        <span class="nav-tab" data-step="4">V. Usuarios</span>
        <span class="nav-tab" data-step="5">VI. Estatus</span>
    </div>

    <!--Tab panes--->
    <form action="{{ route('planteles.update', $plantel->id) }}" method="POST" class="needs-validation form-ficha-base">

        @csrf
        @method('PUT')
        <div class="form-section step-section" data-step="0">
            <h4>I. Datos de Identificación</h4>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">CCT:</label>

                    @if(strtoupper(session('rol')) === 'DIRECTOR')
                    <input type="text" name="cct" class="form-control"
                        value="{{ old('cct', $plantel->cct ?? '') }}">
                    @else
                    <input type="text" class="form-control"
                        value="{{ $plantel->cct }}" readonly>
                    <input type="hidden" name="cct" value="{{ $plantel->cct }}">
                    @endif
                </div>

                <div class="col-md-8">
                    <label for="nombre_escuela" class="form-label">Nombre de la Escuela:</label>
                    <input type="text" class="form-control" name="nombre_escuela" value="{{ old('nombre_escuela', $plantel->nombre_escuela ?? '') }}" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="nivel_educativo" class="form-label">Nivel Educativo:</label>
                    <select name="nivel_educativo" id="nivel_educativo" class="form-select" required>
                        <option value="">Seleccione una opción</option>
                        <option value="preescolar" {{ old('nivel_educativo', $plantel->nivel_educativo ?? '') == 'preescolar' ? 'selected' : '' }}>Preescolar</option>
                        <option value="primaria" {{ old('nivel_educativo', $plantel->nivel_educativo ?? '') == 'primaria' ? 'selected' : '' }}>Primaria</option>
                        <option value="secundaria" {{ old('nivel_educativo', $plantel->nivel_educativo ?? '') == 'secundaria' ? 'selected' : '' }}>Secundaria</option>
                        <option value="media superior" {{ old('nivel_educativo', $plantel->nivel_educativo ?? '') == 'media superior' ? 'selected' : '' }}>Media Superior</option>
                        <option value="superior" {{ old('nivel_educativo', $plantel->nivel_educativo ?? '') == 'superior' ? 'selected' : '' }}>Superior</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="turno" class="form-label">Turno:</label>
                    <select name="turno" id="turno" class="form-select" required>
                        <option value="">Seleccione una opción</option>
                        <option value="matutino" {{ old('turno', $plantel->turno ?? '') == 'matutino' ? 'selected' : '' }}>Matutino</option>
                        <option value="vespertino" {{ old('turno', $plantel->turno ?? '') == 'vespertino' ? 'selected' : '' }}>Vespertino</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="sostenimiento" class="form-label">Sostenimiento:</label>
                    <select name="sostenimiento" id="sostenimiento" class="form-select" value="{{ old('sostenimiento', $plantel->sostenimiento) }}" required>
                        <option value="">Seleccione una opción</option>
                        <option value="federal" {{ old('sostenimiento', $plantel->sostenimiento ?? '') == 'federal' ? 'selected' : '' }}>Federal</option>
                        <option value="estatal" {{ old('sostenimiento', $plantel->sostenimiento ?? '') == 'estatal' ? 'selected' : '' }}>Estatal</option>
                        <option value="particular" {{ old('sostenimiento', $plantel->sostenimiento ?? '') == 'particular' ? 'selected' : '' }}>Particular</option>
                        <option value="municipal" {{ old('sostenimiento', $plantel->sostenimiento ?? '') == 'municipal' ? 'selected' : '' }}>Municipal</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Identificación
                </button>

                <a href="{{ route('planteles.show', $plantel->id) }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-eye"></i> Ver mas detalles del plantel
                </a>
            </div>



        </div>

    </form>
    <!--Ubicacion--->
    <form action="{{ isset($plantel) ? route('planteles.update.ubicacion', $plantel->id) : '#' }}" method="POST" class="needs-validation form-ficha-base">
        @csrf
        @if(isset($plantel))
        @method('PUT')
        @endif
        <div class="form-section step-section d-none" data-step="1">
            <h4>II. Ubicación</h4>
            @unless(isset($plantel))
            <div class="alert alert-warning">
                Debes completar y guardar la Sección I antes de poder llenar esta sección.
            </div>
            @endunless
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="id_municipio" class="form-label">Municipio:</label>
                    <select name="id_municipio" id="id_municipio" class="form-control">
                        <option value="">Seleccione un municipio</option>
                        @foreach($municipios as $municipio)
                        <option value="{{ $municipio->id }}" {{ old('id_municipio', $plantel->id_municipio ?? '') == $municipio->id ? 'selected' : '' }}>
                            {{ $municipio->nombre_municipio }}
                        </option>
                        @endforeach
                        <option value="nuevo">Otro...</option>
                    </select>

                    <input type="text" name="nuevo_municipio" id="input_nuevo_municipio" class="form-control mt-2 d-none" placeholder="Nuevo municipio">
                </div>
                <div class="col-md-4">
                    <label for="id_localidad" class="form-label">Localidad:</label>
                    <select name="id_localidad" id="select_localidad" class="form-control" disabled>
                        <option value="">Seleccione...</option>
                        @foreach($localidades as $localidad)
                        <option value="{{ $localidad->id }}" {{ old('id_localidad', $plantel->id_localidad ?? '') == $localidad->id ? 'selected' : '' }}>
                            {{ $localidad->nombre_localidad }}
                        </option>
                        @endforeach
                        <option value="nuevo">Otro...</option> {{-- <-- esta línea también --}}
                    </select>

                    <input type="text" id="input_nuevo_localidad" name="nuevo_localidad" class="form-control mt-2 d-none" placeholder="Ingrese nueva localidad">
                </div>
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
                    <input type="text" class="form-control" name="domicilio_calle_numero" value="{{ old('domicilio_calle_numero', $plantel->domicilio_calle_numero) }}" placeholder="Ej. Av. Reforma 123" required>
                </div>
                <div class="col-md-4">
                    <label for="domicilio_colonia" class="form-label">Colonia:</label>
                    <input type="text" class="form-control" name="domicilio_colonia" value="{{ old('domicilio_colonia', $plantel->domicilio_colonia) }}" placeholder="Ej. Centro, Roma Norte" required>
                </div>
                <div class="col-md-2">
                    <label for="domicilio_cp" class="form-label">C.P.:</label>
                    <input type="text" class="form-control" name="domicilio_cp" value="{{ old('domicilio_cp', $plantel->domicilio_cp) }}" placeholder="06000" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="latitud" class="form-label">Latitud:</label>
                    <input type="text" class="form-control" name="latitud" value="{{ old('latitud', $plantel->latitud) }}" placeholder="Ej. 19.432608" required>
                </div>
                <div class="col-md-6">
                    <label for="longitud" class="form-label">Longitud:</label>
                    <input type="text" class="form-control" name="longitud" value="{{ old('longitud', $plantel->longitud) }}" placeholder="Ej. -99.133209" required>
                </div>
            </div>

            <div class="row mb-3">

                <div class="col-md-4">
                    <label class="form-label">Macroregión:</label>
                    <input type="text" class="form-control" value="{{ $plantel->macroregion->nombre_macroregion ?? 'No asignada' }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Microregión:</label>
                    <input type="text" class="form-control" value="{{ $plantel->microregion->nombre_microregiones ?? 'No asignada' }}" readonly>
                </div>
            </div>


            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>Guardar Ubicación</button>
            </div>

        </div>
    </form>


    <!--Contacto--->
    <form action="{{ isset($plantel) ? route('planteles.update.contacto', $plantel->id) : '#' }}" method="POST" class="needs-validation form-ficha-base">
        @csrf
        @if(isset($plantel))
        @method('PUT')
        @endif

        <div class="form-section step-section d-none" data-step="2">
            <h4>III. Contacto y Director</h4>
            @unless(isset($plantel))
            <div class="alert alert-warning">
                Debes completar y guardar la Sección I antes de poder llenar esta sección.
            </div>
            @endunless
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="telefono_plantel" class="form-label">Telefono del Plantel:</label>
                    <input type="text" class="form-control" name="telefono_plantel" value="{{ old('telefono_plantel', $plantel->telefono_plantel) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="correo_institucional" class="form-label">Correo Institucional:</label>
                    <input type="text" class="form-control" name="correo_institucional" value="{{ old('correo_institucional', $plantel->correo_institucional) }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="nombre_director_registrado" class="form-label">Nombre del Director:*</label>
                    <input type="text" class="form-control" name="nombre_director_registrado" value="{{old('nombre_director_registrado', $plantel->nombre_director_registrado)}}" required>
                </div>
                <div class="col-md-4">
                    <label for="id_director_asignado" class="form-label">Director Asignado(Usuario):</label>
                    <select name="id_director_asignado" class="form-select" required>
                        <option value="">Seleccione...</option>
                        @foreach($directores as $director)
                        <option value="{{ $director->id }}" {{ old('id_director_asignado', $plantel->id_director_asignado ?? '') == $director->id ? 'selected' : '' }}>
                            {{ $director->nombre_completo}}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>Guardar Contacto</button>
            </div>
        </div>

    </form>

    {{-- Sección IV: Accesibilidad --}}
    <form action="{{ isset($plantel) ? route('planteles.update.accesibilidad', $plantel->id) : '#' }}" method="POST" class="needs-validation form-ficha-base">
        @csrf
        @if(isset($plantel))
        @method('PUT')
        @endif
        <div class="form-section step-section d-none" data-step="3">
            <h4>IV. Accesibilidad</h4>
            @unless(isset($plantel))
            <div class="alert alert-warning">
                Debes completar y guardar la Sección I antes de poder llenar esta sección.
            </div>
            @endunless
            <div class="mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="accesibilidad_rampas" id="accesibilidad_rampas" value="1"
                        {{ old('accesibilidad_rampas', $plantel->accesibilidad_rampas ?? false) ? 'checked' : '' }}
                        <label class="form-check-label" for="accesibilidad_rampas">Rampas</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="accesibilidad_banos_adaptados" id="accesibilidad_banos_adaptados" value="1"
                        {{ old('accesibilidad_banos_adaptados', $plantel->accesibilidad_banos_adaptados ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="accesibilidad_banos_adaptados">Baños Adaptados</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="accesibilidad_sanaletica_braille" id="accesibilidad_sanaletica_braille" value="1"
                        {{ old('accesibilidad_sanaletica_braille', $plantel->accesibilidad_sanaletica_braille ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="accesibilidad_sanaletica_braille">Señalética Braille</label>
                </div>
            </div>
            <div class="mb-3">
                <label for="accesibilidad_otros" class="form-label">Otros (Accesibilidad):</label>
                <input type="text" class="form-control" name="accesibilidad_otros" id="accesibilidad_otros"
                    value="{{ old('accesibilidad_otros', $plantel->accesibilidad_otros) }}" placeholder="Especificar...">
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>Guardar Accesibilidad</button>
            </div>
        </div>
    </form>

    {{-- Sección V: Total Usuarios Planteles --}}

    <form action="{{ isset($plantel) ? route('planteles.update.totalUsuariosPlanteles', $plantel->id) : '#' }}" method="POST" class="needs-validation form-ficha-base">
        @csrf
        @if(isset($plantel))
        @method('PUT')
        @endif
        <div class="form-section step-section d-none" data-step="4">
            <h4>V. Total Usuarios Planteles</h4>
            @unless(isset($plantel))
            <div class="alert alert-warning">
                Debes completar y guardar la Sección I antes de poder llenar esta sección.
            </div>
            @endunless
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="total_alumnos" class="form-label">Total Alumnos:</label>
                    <input type="number" class="form-control" name="total_alumnos" value="{{ old('total_alumnos', $plantel->total_alumnos) }}" required>
                </div>
                <div class="col-md-6">
                    <label for="total_docentes" class="form-label">Total Docentes</label>
                    <input type="number" class="form-control" name="total_docentes" value="{{old('total_docentes', $plantel->total_docentes)}}" required>
                </div>
                <div class="col-md-6">
                    <label for="total_administrativos" class="form-label">Total Administrativos</label>
                    <input type="number" class="form-control" name="total_administrativos" value="{{old('total_administrativos', $plantel->total_administrativos)}}" required>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>Guardar Total Usuarios</button>
            </div>
        </div>
    </form>

    {{-- Sección VI: estauts plantel(admin) --}}
    <form action="{{ isset($plantel) ? route('planteles.update.estatus', $plantel->id) : '#' }}" method="POST" class="needs-validation form-ficha-base">
        @csrf
        @if(isset($plantel))
        @method('PUT')
        @endif
        <div class="form-section step-section d-none" data-step="5">
            <h4>VI. Estatus(Admin)</h4>
            @unless(isset($plantel))
            <div class="alert alert-warning">
                Debes completar y guardar la Sección I antes de poder llenar esta sección.
            </div>
            @endunless
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="estatus_plantel" class="form-label">Estatus:</label>
                    <select name="estatus_plantel" class="form-select" required>
                        <option value="">Seleccione una opcion</option>
                        <option value="ACTIVO" {{ old('estatus_plantel', $plantel->estatus_plantel ?? '') == 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                        <option value="INACTIVO" {{ old('estatus_plantel', $plantel->estatus_plantel ?? '') == 'INACTIVO' ? 'selected' : '' }}>INACTIVO</option>
                        <option value="EN_REVISION" {{ old('estatus_plantel', $plantel->estatus_plantel ?? '') == 'EN_REVISION' ? 'selected' : '' }}>EN REVISION</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>Guardar Estatus Plantel</button>
            </div>
        </div>
    </form>

</div>

@push('scripts')
<!--Script para navegacion de tabs-->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const secciones = document.querySelectorAll('.step-section');
        const pestañas = document.querySelectorAll('.nav-tab');

        function mostrarSeccion(numero) {
            secciones.forEach((seccion, i) => {
                seccion.classList.toggle('active', i === numero);
                seccion.classList.toggle('d-none', i !== numero);
            });

            pestañas.forEach((pestana, i) => {
                pestana.classList.toggle('active', i === numero);
            });

            // Guardar el paso activo
            localStorage.setItem('pasoActivo', numero);
        }

        // Recuperar el paso guardado o usar 0 por defecto
        const pasoGuardado = parseInt(localStorage.getItem('pasoActivo')) || 0;
        mostrarSeccion(pasoGuardado);

        // Asignar eventos a las pestañas
        pestañas.forEach((pestana, i) => {
            pestana.addEventListener('click', () => {
                mostrarSeccion(i);
            });
        });
    });
</script>

<!--Script para nuevos municipios y localidades--->
<script src="{{ asset('js/ubicacion.js') }}"></script>


<!--Script para municipios--->
<script src="{{ asset('js/editar_ubicacion.js') }}"></script>

@endpush


@endsection