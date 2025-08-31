@extends('layouts.app')

@section('title', 'Reporte: Conteo de Planteles por Municipio')

@section('content')
<main class="main-container">
    <div class="container mt-4">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <a href="{{ route('reportes.index') }}" class="d-inline-flex align-items-center text-dark text-decoration-none">
                <i class="fas fa-arrow-left me-2" style="font-size:1.5rem;"></i>
                <h2 class="mb-0"><i class="fas fa-city"></i> Reporte: Conteo de Planteles por Municipio</h2>
            </a>
            <div class="report-actions">
                <a href="{{route('reportes.municipios.exportar')}}" class="btn btn-success me-2">
                    <i class="fas fa-file-excel"></i> Exportar a CSV
                </a>
                <a href="{{route('reportes.municipio.pdf')}}" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Exportar a PDF
                </a>
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

        <div class="municipios-grid mt-4">
            @foreach($agrupados as $municipio => $localidades)
            @php
            $municipioTotal = $localidades->sum('total_planteles');
            $totalGeneral += $municipioTotal;
            @endphp

            <div class="municipio-card mb-4">
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
                        <ul class="list-unstyled ms-3">
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
            <div class="municipio-card total-card">
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
    </div>
</main>
@endsection