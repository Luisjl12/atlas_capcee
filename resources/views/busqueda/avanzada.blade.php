@extends('layouts.app')

@section('title', 'Buscador Avanzado')

@section('content')

    @php
    use App\Helpers\RoleHelper;
    @endphp

    <div class="card-header-custom">
        <a href="{{ RoleHelper::dashboardRoute(session('role_id')) }}"
            class="btn-icon-only">
            <i class="fas fa-arrow-left"></i>
            <h2><i class="fas fa-search me-3"></i>Buscador Avanzado de Planteles</h2>
        </a>
    </div>

        <div class="data-table-container">

            <!-- Botón para abrir modal -->
            <p id="toggleFiltros" class="p-toggle-filtro">
                <i class="fas fa-filter"></i> Clic para una búsqueda avanzada
            </p>

            <!-- Modal de filtros -->
            <div id="modalFiltros" class="modal-filtros" style="display: none;">
                <div class="modal-content-avanzado">
                    <div class="modal-header">
                        <button class="close-modal" id="closeModal">&times;</button>
                    </div>

                    <form method="GET" action="{{ route('busqueda.avanzada') }}" class="filters-form">

                        {{-- Buscar --}}
                        <div class="filter-section">
                            <label for="busqueda">Buscar</label>
                            <input type="text" id="busqueda" name="busqueda"
                                placeholder="Buscar por CCT o nombre"
                                value="{{ request('busqueda') }}">
                        </div>

                        <hr>

                        {{-- Municipio --}}
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

                        {{-- Nivel educativo --}}
                        <div class="filter-section">
                            <label>Nivel Educativo</label>
                            <div class="chip-group">
                                <label>
                                    <input type="radio" name="nivel_educativo" value=""
                                        {{ request('nivel_educativo') == '' ? 'checked' : '' }}>
                                    <span>Todos</span>
                                </label>
                                @foreach($nivelesEducativos as $nivel) 
                                    @if(!empty($nivel))
                                <label>
                                    <input type="radio" name="nivel_educativo" value="{{ $nivel }}"
                                    {{ request('nivel_educativo') == $nivel ? 'checked' : '' }}>
                                    <span>{{ $nivel }}</span>
                                </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <hr>

                        {{-- Sostenimiento --}}
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

                        {{-- Alarma sísmica --}}
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

                        {{-- Footer del modal --}}
                        <div class="modal-footer">
                            <button type="submit" class="btn-custom show">Mostrar Planteles</button>
                            <a href="{{ route('busqueda.avanzada') }}" class="btn-limpiador">
                                <i class="fas fa-eraser"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Total de resultados --}}
            <p class="mt-3"><strong>{{ $planteles->total() }}</strong> planteles encontrados.</p>

            {{-- Tabla de resultados --}}
            @include('partials.lista_avanzada', ['planteles' => $planteles])
        </div>
        
    {{-- Paginación --}}
    @if(method_exists($planteles, 'links'))
    <nav class="pagination-container" aria-label="Navegación de páginas">
        <ul class="pagination">
            <li class="page-item ">
                {{ $planteles->links('vendor.pagination.mi_paginacion') }}
            </li>
        </ul>
    </nav>
    @endif
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
<script src="{{ asset('js/tabla-expandible-avanzado.js') }}"></script>
@endpush