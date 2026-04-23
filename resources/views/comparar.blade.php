@extends('layouts.app')

@section('title', 'Comparar Infraestructura')

@section('content')

    <div class="card-header d-flex align-items-center justify-content-between">
        <h3>
            <i class="fas fa-school"></i> Comparar Infraestructura
        </h3>
    </div>

    {{-- Mensajes de éxito --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    @endif

    {{-- Errores de validación --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Navegación por pestañas --}}
    <div class="form-navigation nav-tabs-text mb-3">
        <span class="nav-tab active" data-step="0"><i class="fas fa-book"></i> Niveles Educativos</span>
        <span class="nav-tab" data-step="1"><i class="fas fa-building"></i> Número de Edificios</span>
        <span class="nav-tab" data-step="2"><i class = "fas fa-tint"></i>Agua y almacenamiento</spán>
    </div>

    <div class="form-ficha-base">

        {{-- Formulario Niveles --}}
        <form action="{{ route('infraestructura.comparar') }}" method="POST">
            @csrf
            <div class="form-section step-section" data-step="0">
                <h4><i class="fas fa-book-open"></i> Niveles Educativos</h4>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>CCT:</label>
                        <input type="text" name="cct" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>¿Cual es su nivel academico?</label>
                        <select name="nivel" class="form-select" required>
                            <option value="" disabled selected>Selecciona un nivel</option>
                            <option value="inicial">Inicial</option>
                            <option value="preescolar">Preescolar</option>
                            <option value="primaria">Primaria</option>
                            <option value="secundaria">Secundaria</option>
                            <option value="formacion_trabajo">Formación Trabajo</option>
                            <option value="bachillerato_generak">Bachillerato General</option>
                            <option value="bachillerato_tecnologico">Bachillerato Tecnologico</option>
                            <option value="tecnico_profesional">Tecnico Profesional</option>
                            <option value="tecnico_licenciatura">Tecnico Licenciatura</option>
                            <option value="tecnico_posgrado">Tecnico Posgrado</option>
                        </select>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label>¿Imparte clases actualmente?</label>
                    <select name="imparte" class="form-select" required>
                        <option value="" disabled selected>Selecciona una opción</option>
                        <option value="1">Sí imparte</option>
                        <option value="0">No imparte</option>
                    </select>
                </div>

                <button type="submit" class="btn-custom btn-primary mt-3">
                    <i class="fas fa-save"></i> Guardar Nivel
                </button>
            </div>
        </form>

        {{-- Formulario Edificios --}}
        <form action="{{ route('comparacion.edificios.store') }}" method="POST">
            @csrf
            <div class="form-section step-section" data-step="1">
                <h4><i class="fas fa-city"></i> Número de Edificios</h4>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>CCT:</label>
                        <input type="text" name="cct" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>¿Cuantos edificios tienen?</label>
                        <input type="number" name="numero_edificios" class="form-control" min="0" required>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label>¿De que fuente obtuvistes esa informacion? (Opcional):</label>
                    <input type="text" name="fuente" class="form-control" placeholder="Ej. INEGI, SEP">
                </div>

                <!-- Nuevo campo para descripción -->
                <div class="form-group mt-3">
                    <label>¿Cual es el tipo de edificios que quieres registrar? (Opcional):</label>
                    <textarea name="descripcion_edificios" class="form-control" rows="3" 
                            placeholder="Ej. Laboratorios, Biblioteca, Edificio Administrativo"></textarea>
                </div>

                <button type="submit" class="btn-custom btn-success mt-3">
                    <i class="fas fa-save"></i> Guardar Edificios
                </button>
            </div>
        </form>

        {{-- Formulario Agua --}}
        <form action="{{ route('comparacion.agua.store') }}" method="POST">
            @csrf
            <div class="form-section step-section" data-step="2">
                <h4><i class="fas fa-water"></i> Agua y Almacenamiento</h4>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label>CCT:</label>
                        <input type="text" name="cct" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Estado de la red pública:</label>
                        <input type="text" name="estado_red_publica" class="form-control" placeholder="Ej. Operando, Deficiente">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="form-group col-md-3">
                        <label><input type="checkbox" name="agua_red_publica" value="1"> Red pública</label>
                    </div>
                    <div class="form-group col-md-3">
                        <label><input type="checkbox" name="agua_pozo" value="1"> Pozo</label>
                    </div>
                    <div class="form-group col-md-3">
                        <label><input type="checkbox" name="agua_cuerpo" value="1"> Cuerpo de agua</label>
                    </div>
                    <div class="form-group col-md-3">
                        <label><input type="checkbox" name="agua_pipas" value="1"> Pipas</label>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="form-group col-md-3">
                        <label><input type="checkbox" name="agua_otro" value="1"> Otro suministro</label>
                    </div>
                    <div class="form-group col-md-3">
                        <label><input type="checkbox" name="cisterna" value="1"> Cisterna</label>
                    </div>
                    <div class="form-group col-md-3">
                        <label><input type="checkbox" name="tinacos" value="1"> Tinacos</label>
                    </div>
                    <div class="form-group col-md-3">
                        <label><input type="checkbox" name="tanque" value="1"> Tanque</label>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label><input type="checkbox" name="almacenamiento_otro" value="1"> Otro almacenamiento</label>
                </div>

                <button type="submit" class="btn-custom btn-info mt-3">
                    <i class="fas fa-save"></i> Guardar Agua
                </button>
            </div>
        </form>

    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const secciones = document.querySelectorAll('.step-section');
        const pestañas = document.querySelectorAll('.nav-tab');

        function mostrarSeccion(numero) {
            secciones.forEach((sec, i) => sec.classList.toggle('active', i === numero));
            pestañas.forEach((tab, i) => tab.classList.toggle('active', i === numero));
        }

        const params = new URLSearchParams(window.location.search);
        const paso = parseInt(params.get('step')) || 0;
        mostrarSeccion(paso);

        pestañas.forEach((tab, i) => {
            tab.addEventListener('click', () => {
                mostrarSeccion(i);
                const nuevaUrl = new URL(window.location);
                nuevaUrl.searchParams.set('step', i);
                window.history.replaceState({}, '', nuevaUrl);
            });
        });
    });
</script>
@endpush

@endsection
