@extends('layouts.app')

@section('title', 'Reporte: Conteo de Planteles por Municipio')

@section('content')

        <div class="card-header-custom">
            <a href="{{ route('reportes.index') }}" class="btn-icon-only">
                <i class="fas fa-arrow-left"></i>
                <h2><i class="fas fa-city"></i> Reporte: Conteo de Planteles por Municipio</h2>
            </a>
            <div class="report-actions">
                <a href="{{route('reportes.municipios.exportar')}}" class="btn-custom btn-success me-2">
                    <i class="fas fa-file-excel"></i> Exportar a CSV
                </a>
                
            </div>
        </div>
        
        {{-- Buscador --}}
        <div class="buscador-container mb-3">
            <div class="position-relative">
                <i class="fas fa-search position-absolute"
                style="top: 50%; left: 15px; transform: translateY(-50%); color: var(--color-vino-primario);"></i>
                <input type="text" id="buscar" class="form-control ps-5" placeholder="Buscar municipio por nombre">
            </div>
        </div>

        {{-- Mensaje si no hay datos --}}
        @if($datos->isEmpty())
        <p class="text-center mt-4">No hay datos disponibles para este reporte.</p>
        @else
        @php
        $agrupados = $datos->groupBy('municipio');
        $totalGeneral = 0;
        @endphp

        <div class="municipios-grid">
            @foreach($agrupados as $municipio => $localidades)
            @php
            $municipioTotal = $localidades->sum('total_planteles');
            $totalGeneral += $municipioTotal;
            @endphp

            <div class="municipio-card" data-nombre="{{ strtolower($municipio) }}">
                <div class="card-bar"></div>
                <div class="card-content">
                    <div class="card-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="card-text">
                        <h3>{{ $municipio }}</h3>
                        <p><strong>{{ $municipioTotal }} Planteles Registrados</strong></p>

                        @foreach($localidades as $loc)
                        <p><strong>Localidad: </strong>{{ $loc->localidad }}</p>
                        <ul class="">
                            <p><strong>Nombre del plantel:</strong></p>
                            @foreach(explode(', ', $loc->nombre_planteles) as $plantel)
                            <li>{{ $plantel }}</li>
                            @endforeach
                        </ul>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach

            {{-- Tarjeta total general --}}
            <div class="municipio-card total-card" data-general="true">
                <div class="card-bar total-bar"></div>
                <div class="card-content">
                    <div class="card-icon">
                        <i class="fas fa-list-ul"></i>
                    </div>
                    <div class="card-text">
                        <h3>TOTAL GENERAL</h3>
                        <p>{{ $totalGeneral }} Planteles</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

@endsection

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('buscar');
    const cards = document.querySelectorAll('.municipio-card');

    input.addEventListener('input', () => {
        const filtro = input.value.trim().toLowerCase();

        cards.forEach(card => {
            const esGeneral = card.dataset.general === 'true';
            const nombreMunicipio = (card.dataset.nombre || '').toLowerCase();

            if (esGeneral || nombreMunicipio.includes(filtro)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
</script>



