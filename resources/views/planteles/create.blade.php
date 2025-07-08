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
            <div class="col-md-4">
                <label for="id_municipio" class="form-label">Municipio:</label>
                <select name="id_municipio" class="form-select" required>
                    <option value="">Seleccione...</option>
                    @foreach($municipios as $municipio)
                    <option value="{{ $municipio->id }}" {{ old('id_municipio') == $municipio->id ? 'selected' : '' }}>
                        {{ $municipio->nombre_municipio}}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="id_localidad" class="form-label">Localidad:</label>
                <select name="id_localidad" class="form-select" required>
                    <option value="">Seleccione...</option>
                    @foreach($localidades as $localidad)
                    <option value="{{ $localidad->id }}" {{ old('id_localidad') == $localidad->id ? 'selected' : '' }}>
                        {{ $localidad->nombre_localidad}}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="id_corde" class="form-label">CORDE:</label>
                <select name="id_corde" class="form-select" required>
                    <option value="">Seleccione...</option>
                    @foreach($cordes as $corde)
                    <option value="{{ $corde->id }}" {{ old('id_corde') == $corde->id ? 'selected' : '' }}>
                        {{ $corde->nombre_corde}}
                    </option>
                    @endforeach
                </select>
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


        <button type="submit" class="btn btn-success">Guardar Plantel</button>
        <a href="{{ route('planteles.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection