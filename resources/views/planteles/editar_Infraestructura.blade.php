@extends('layouts.app')

@section('title', 'Agregar Plantel')

@section('content')
<div class="container mt-4">

    <div class="card-header bg-white border-bottom mb-4">
        <a href="{{ route('planteles.show', $plantel->id) }}" class="text-decoration-none d-inline-flex align-items-center text-dark">
            <h4 class="mb-0">
                <i class="fas fa-arrow-left "></i>
                <i class="fas fa-tint"></i> Editar Servicios: {{ $plantel->nombre_escuela }}
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
        <span class="nav-tab active" data-step="0">Servicios Basicos</span>
        <span class="nav-tab" data-step="1">Hidrosanitaria</span>

    </div>

    <!--Tab panes--->
    <!--Servicios Basicos --->
    <div class="form-ficha-base">

        <form action="{{ route('infraestructura.update_servicios', $plantel->cct) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-section step-section" data-step="0">


                <h4><i class="fas fa-bolt"></i> Servicios Básicos</h4>

                <div class="row">
                    <div class="form-check col-md-3">
                        <input type="hidden" name="electricidad_contrato" value="0">
                        <input class="form-check-input" type="checkbox" name="electricidad_contrato" value="1"
                            {{ old('electricidad_contrato', $servicio->electricidad_contrato ?? 0) ? 'checked' : '' }}>
                        <label> Contrato de Electricidad</label>
                    </div>

                    <div class="form-check col-md-3">
                        <input type="hidden" name="telefonia_fija" value="0">
                        <input class="form-check-input" type="checkbox" name="telefonia_fija" value="1"
                            {{ old('telefonia_fija', $servicio->telefonia_fija ?? 0) ? 'checked' : '' }}>
                        <label> Línea Telefónica Fija</label>
                    </div>

                    <div class="form-check col-md-3">
                        <input type="hidden" name="internet_acceso" value="0">
                        <input class="form-check-input" type="checkbox" name="internet_acceso" value="1"
                            {{ old('internet_acceso', $servicio->internet_acceso ?? 0) ? 'checked' : '' }}>
                        <label> Acceso a Internet</label>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="form-group col-md-6">
                        <label>Tipo de Gas:</label>
                        <input type="text" name="gas_tipo" class="form-control"
                            placeholder="Ej: LP Estacionario, Cilindros, No tiene"
                            value="{{ old('gas_tipo', $servicio->gas_tipo ?? '') }}">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Tipo de Internet:</label>
                        <input type="text" name="internet_tipo" class="form-control"
                            placeholder="Ej: Fibra Óptica, ADSL, Satelital"
                            value="{{ old('internet_tipo', $servicio->internet_tipo ?? '') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label>Observaciones de Servicios:</label>
                    <textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones', $servicio->observaciones ?? '') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>Guardar Servicios</button>

            </div>
        </form>

        <!--Hidrosanitario--->
        <form action="{{ route('infraestructura.update_hidrosanitario', $plantel->cct) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-section step-section" data-step="1">

                <h4><i class="fas fa-faucet"></i> Hidrosanitaria</h4>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Fuente de Agua:</label>
                        <input type="text" name="fuente_agua" class="form-control"
                            value="{{ old('fuente_agua', $hidrosanitario->fuente_agua ?? '') }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Almacenamiento de Agua:</label>
                        <input type="text" name="almacenamiento_agua" class="form-control"
                            placeholder="Ej: 1 Cisterna 1000L"
                            value="{{ old('almacenamiento_agua', $hidrosanitario->almacenamiento_agua ?? '') }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Tipo de Drenaje:</label>
                        <input type="text" name="tipo_drenaje" class="form-control"
                            placeholder="Ej: Red pública, Fosa séptica"
                            value="{{ old('tipo_drenaje', $hidrosanitario->tipo_drenaje ?? '') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-3">
                        <label># WC Hombres:</label>
                        <input type="number" name="sanitarios_hombres_wc" class="form-control" min="0"
                            value="{{ old('sanitarios_hombres_wc', $hidrosanitario->sanitarios_hombres_wc ?? 0) }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label># Lavabos Hombres:</label>
                        <input type="number" name="sanitarios_hombres_lavabos" class="form-control" min="0"
                            value="{{ old('sanitarios_hombres_lavabos', $hidrosanitario->sanitarios_hombres_lavabos ?? 0) }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label># WC Mujeres:</label>
                        <input type="number" name="sanitarios_mujeres_wc" class="form-control" min="0"
                            value="{{ old('sanitarios_mujeres_wc', $hidrosanitario->sanitarios_mujeres_wc ?? 0) }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label># Lavabos Mujeres:</label>
                        <input type="number" name="sanitarios_mujeres_lavabos" class="form-control" min="0"
                            value="{{ old('sanitarios_mujeres_lavabos', $hidrosanitario->sanitarios_mujeres_lavabos ?? 0) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label>Observaciones Hidrosanitarias:</label>
                    <textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones', $hidrosanitario->observaciones ?? '') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>Guardar Hidrosanitario</button>

            </div>

        </form>
    </div>
</div>

@push('scripts')
<!--Script para navegacion de tabs-->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const secciones = document.querySelectorAll('.step-section');
        const pestañas = document.querySelectorAll('.nav-tab');

        function mostrarSeccion(numero) {
            for (let i = 0; i < secciones.length; i++) {
                secciones[i].classList.toggle('active', i === numero);
                pestañas[i].classList.toggle('active', i === numero);
            }
        }

        // Obtener el parámetro ?step de la URL
        const params = new URLSearchParams(window.location.search);
        const paso = parseInt(params.get('step')) || 0;

        // Mostrar la sección correspondiente al cargar
        mostrarSeccion(paso);

        // Cuando se hace clic en una pestaña
        for (let i = 0; i < pestañas.length; i++) {
            pestañas[i].addEventListener('click', function() {
                mostrarSeccion(i);
                // Opcional: actualizar la URL sin recargar
                const nuevaUrl = new URL(window.location);
                nuevaUrl.searchParams.set('step', i);
                window.history.replaceState({}, '', nuevaUrl); // Esto evita que se recargue
            });
        }
    });
</script>

@endpush


@endsection