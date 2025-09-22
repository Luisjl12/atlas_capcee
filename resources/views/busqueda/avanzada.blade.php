@extends('layouts.app')

@section('title', 'Buscador Avanzado')

@section('content')
<div class="container mt-4">
    @php
    use App\Helpers\RoleHelper;
    @endphp

    <div class="card-header-custom">
        <a href="{{ RoleHelper::dashboardRoute(session('role_id')) }}"
            class="text-decoration-none d-inline-flex align-items-center text-dark">
            <h4>
                <i class="fas fa-arrow-left"></i>
                <i class="fas fa-search"></i>
                <strong> Buscador Avanzado de Planteles</strong>
            </h4>
        </a>
    </div>

    <div class="card">
        <div class="card-body">

            <!-- Botón para abrir modal -->
            <p id="toggleFiltros" class="p-toggle-filtro">
                <i class="fas fa-filter"></i> Clic para una búsqueda avanzada
            </p>

            <!-- Modal de filtros -->
            <div id="modalFiltros" class="modal-filtros" style="display: none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close-modal" id="closeModal">&times;</button>
                    </div>

                    <form method="GET" action="{{ route('busqueda.avanzada') }}" class="filters-form">

                        <div class="filter-section">
                            <label for="busqueda">Buscar</label>
                            <input type="text" id="busqueda" name="busqueda"
                                placeholder="Buscar por CCT o nombre"
                                value="{{ request('busqueda') }}">
                        </div>

                        <hr>

                        <div class="filter-section">
                            <label for="id_municipio">Municipio</label>
                            <select id="id_municipio" name="id_municipio">
                                <option value="">Todos</option>
                                @foreach($municipios as $municipio)
                                <option value="{{ $municipio->id }}"
                                    {{ request('id_municipio') == $municipio->id ? 'selected' : '' }}>
                                    {{ $municipio->nombre_municipio }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-section">
                            <label for="id_corde">Corde</label>
                            <select id="id_corde" name="id_corde">
                                <option value="">Todas</option>
                                @foreach($cordes as $corde)
                                <option value="{{ $corde->id }}"
                                    {{ request('id_corde') == $corde->id ? 'selected' : '' }}>
                                    {{ $corde->nombre_corde }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <hr>

                        <div class="filter-section">
                            <label>Nivel</label>
                            <div class="chip-group">
                                <label>
                                    <input type="radio" name="nivel_educativo" value=""
                                        {{ request('nivel_educativo') == '' ? 'checked' : '' }}>
                                    <span>Todos</span>
                                </label>
                                <label>
                                    <input type="radio" name="nivel_educativo" value="Preescolar"
                                        {{ request('nivel_educativo') == 'Preescolar' ? 'checked' : '' }}>
                                    <span>Preescolar</span>
                                </label>
                                <label>
                                    <input type="radio" name="nivel_educativo" value="Primaria"
                                        {{ request('nivel_educativo') == 'Primaria' ? 'checked' : '' }}>
                                    <span>Primaria</span>
                                </label>
                                <label>
                                    <input type="radio" name="nivel_educativo" value="Secundaria"
                                        {{ request('nivel_educativo') == 'Secundaria' ? 'checked' : '' }}>
                                    <span>Secundaria</span>
                                </label>
                                <label>
                                    <input type="radio" name="nivel_educativo" value="Educación Media Superior"
                                        {{ request('nivel_educativo') == 'Educación Media Superior' ? 'checked' : '' }}>
                                    <span>Media Superior</span>
                                </label>
                                <label>
                                    <input type="radio" name="nivel_educativo" value="Educación Superior"
                                        {{ request('nivel_educativo') == 'Educación Superior' ? 'checked' : '' }}>
                                    <span>Superior</span>
                                </label>
                            </div>
                        </div>

                        <hr>

                        <div class="filter-section">
                            <label>Sostenimiento</label>
                            <div class="chip-group">
                                <label>
                                    <input type="radio" name="sostenimiento" value=""
                                        {{ request('sostenimiento') == '' ? 'checked' : '' }}>
                                    <span>Todos</span>
                                </label>
                                <label>
                                    <input type="radio" name="sostenimiento" value="Federal"
                                        {{ request('sostenimiento') == 'Federal' ? 'checked' : '' }}>
                                    <span>Federal</span>
                                </label>
                                <label>
                                    <input type="radio" name="sostenimiento" value="Estatal"
                                        {{ request('sostenimiento') == 'Estatal' ? 'checked' : '' }}>
                                    <span>Estatal</span>
                                </label>
                                <label>
                                    <input type="radio" name="sostenimiento" value="Particular"
                                        {{ request('sostenimiento') == 'Particular' ? 'checked' : '' }}>
                                    <span>Particular</span>
                                </label>
                                <label>
                                    <input type="radio" name="sostenimiento" value="Municipal"
                                        {{ request('sostenimiento') == 'Municipal' ? 'checked' : '' }}>
                                    <span>Municipal</span>
                                </label>
                            </div>
                        </div>

                        <hr>

                        <div class="filter-section">
                            <label>Alarma Sísmica</label>
                            <div class="chip-group">
                                <label>
                                    <input type="radio" name="alarma_sismica" value=""
                                        {{ request('alarma_sismica') == '' ? 'checked' : '' }}>
                                    <span>Indiferente</span>
                                </label>
                                <label>
                                    <input type="radio" name="alarma_sismica" value="1"
                                        {{ request('alarma_sismica') == '1' ? 'checked' : '' }}>
                                    <span>Sí</span>
                                </label>
                                <label>
                                    <input type="radio" name="alarma_sismica" value="0"
                                        {{ request('alarma_sismica') == '0' ? 'checked' : '' }}>
                                    <span>No</span>
                                </label>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn show">Mostrar Planteles</button>
                            <a href="{{ route('busqueda.avanzada') }}" class="btn-limpiador">
                                <i class="fas fa-eraser"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <p class="mt-3"><strong>{{ $planteles->total() }}</strong> planteles encontrados.</p>

            {{-- Tabla de resultados --}}
            @include('partials.lista_avanzada', ['planteles' => $planteles])

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById("toggleFiltros").addEventListener("click", () => {
        document.getElementById("modalFiltros").style.display = "flex";
    });
    document.getElementById("closeModal").addEventListener("click", () => {
        document.getElementById("modalFiltros").style.display = "none";
    });
    window.addEventListener("click", (e) => {
        if (e.target.id === "modalFiltros") {
            document.getElementById("modalFiltros").style.display = "none";
        }
    });
</script>
@endpush