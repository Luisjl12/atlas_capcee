@extends('layouts.app')

@section('title', 'Buscador Avanzado')

@section('content')

<div class="container mt-4">
    <div class="card-header ">
        <h4><i class="fas fa-search"></i><strong> Buscador Avanzado de Planteles</strong></h4>
    </div>
    <div class="card">
        <div class="card-body">

            <p id="toggleFiltros" class="p-toggle-filtro"><i class="fas fa-filter"></i>Clic para una búsqueda
                avanzada</p>
            <form method="GET" action="{{ route('busqueda.avanzada') }}">
                <div class="filter-panel" id="panelFiltros" style="display: none;">
                    <div class="filter-grid">
                        <!-- Aquí van tus campos de filtro -->
                        <div class="filter-group">
                            <label for="busqueda"> Nombre o CCT:</label>
                            <input type="text" id="busqueda" name="busqueda" value="{{ request('busqueda') }}"
                                placeholder="Buscar por CCT o nombre">
                        </div>
                        <div class="filter-group">
                            <label for="id_corde">Corde:</label>
                            <select id="id_corde" name="id_corde">
                                <option value="">Todas</option>
                                @foreach($cordes as $corde)
                                <option value="{{ $corde->id }}" {{ request('id_corde') == $corde->id ? 'selected' : '' }}>
                                    {{ $corde->nombre_corde }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="id_municipio">Municipio:</label>
                            <select id="id_municipio" name="id_municipio">
                                <option value="">Todos</option>
                                @foreach($municipios as $municipio)
                                <option value="{{ $municipio->id }}" {{ request('id_municipio') == $corde->id ? 'selected' : '' }}>
                                    {{ $municipio->nombre_municipio }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="nivel_educativo">Nivel Educativo:</label>
                            <select id="nivel_educativo" name="nivel_educativo">
                                <option value="">Todos</option>
                                <option value="preescolar">Preescolar</option>
                                <option value="primaria">Primaria</option>
                                <option value="secundaria">Secundaria</option>
                                <option value="media superior">Media Superior</option>
                                <option value="superior">Superior</option>

                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="sostenimiento">Sostenimiento:</label>
                            <select id="sostenimiento" name="sostenimiento">
                                <option value="">Todos</option>
                                <option value="federal">Federal</option>
                                <option value="estatal">Estatal</option>
                                <option value="particular">Particular</option>
                                <option value="municipal">Municipal</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="alarma_sismica">Alarma Sísmica:</label>
                            <select id="alarma_sismica" name="alarma_sismica">
                                <option value="">Indiferente</option>
                                <option value="1" {{ request('alarma_sismica') == '1' ? 'selected' : '' }}>Sí</option>
                                <option value="0" {{ request('alarma_sismica') == '0' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="filter-actions full-width">
                            <button type="submit" class="btn"
                                style="background-color: var(--color-verde-primario)">Aplicar</button>
                            <a href="{{ route('busqueda.avanzada') }}" class="btn"
                                style="background-color: var(--color-amarillo-primario)">Limpiar</a>
                        </div>



                    </div>
                </div>
            </form>

            <p class="mb-2">
                <strong>{{ $planteles->total() }}</strong> Planteles encontrados
            </p>


            {{-- Tabla de planteles --}}
            @include('partials.lista_avanzada', ['planteles' => $planteles])
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById("toggleFiltros").addEventListener("click", function() {
        const panel = document.getElementById("panelFiltros");
        panel.style.display = (panel.style.display === "none" || panel.style.display === "") ? "block" : "none";
    });
</script>
@endpush