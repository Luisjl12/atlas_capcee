@extends('layouts.app')
@section('title', 'Editar Plantel')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Editar Plantel: {{ $plantel->nombre_escuela }}</h2>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('planteles.update', $plantel->id) }}" method="POST">
        @csrf
        @method('PUT')

        <h5 class="text-danger">I. Datos Generales</h5>
        <hr style="border: 1px solid #a10000;">

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">CCT:</label>
                <input type="text" name="cct" class="form-control" value="{{ old('cct', $plantel->cct) }}" required>
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

        <h5 class="text-danger mt-4">II. Ubicación</h5>
        <hr style="border: 1px solid #a10000;">

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Municipio:</label>
                <select name="id_municipio" class="form-select" required>
                    <option value="">Seleccione...</option>
                    @foreach($municipios as $municipio)
                    <option value="{{ $municipio->id }}" {{ old('id_municipio', $plantel->id_municipio) == $municipio->id ? 'selected' : '' }}>
                        {{ $municipio->nombre_municipio }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Localidad:</label>
                <select name="id_localidad" class="form-select" required>
                    <option value="">Seleccione...</option>
                    @foreach($localidades as $localidad)
                    <option value="{{ $localidad->id }}" {{ old('id_localidad', $plantel->id_localidad) == $localidad->id ? 'selected' : '' }}>
                        {{ $localidad->nombre_localidad }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">CORDE:</label>
                <select name="id_corde" class="form-select" required>
                    <option value="">Seleccione...</option>
                    @foreach($cordes as $corde)
                    <option value="{{ $corde->id }}" {{ old('id_corde', $plantel->id_corde) == $corde->id ? 'selected' : '' }}>
                        {{ $corde->nombre_corde }}
                    </option>
                    @endforeach
                </select>
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

        <h5 class="text-danger mt-4">III. Contacto y Director</h5>
        <hr style="border: 1px solid #a10000;">

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
            <div class="col-md-6">
                <label class="form-label">Estatus del Plantel:</label>
                <select name="estatus_plantel" class="form-select" required>
                    <option value="ACTIVO" {{ old('estatus_plantel', $plantel->estatus_plantel) == 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                    <option value="INACTIVO" {{ old('estatus_plantel', $plantel->estatus_plantel) == 'INACTIVO' ? 'selected' : '' }}>INACTIVO</option>
                    <option value="EN_REVISION" {{ old('estatus_plantel', $plantel->estatus_plantel) == 'EN_REVISION' ? 'selected' : '' }}>EN REVISIÓN</option>
                </select>
            </div>
        </div>

        <h5 class="text-danger mt-4">IV. Accesibilidad</h5>
        <hr style="border: 1px solid #a10000;">

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

        <h5 class="text-danger mt-4">V. Recursos Humanos</h5>
        <hr style="border: 1px solid #a10000;">

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

        <h5 class="text-danger mt-4">VI Accesibilidad</h5>
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

        <button type="submit" class="btn btn-primary">Actualizar Plantel</button>
        <a href="{{ route('planteles.index') }}" class="btn btn-secondary">Cancelar</a>



    </form>
</div>
@endsection