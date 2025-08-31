@extends('layouts.app')

@section('title', 'Reporte de Infraestructura')

@section('content')
<main class="main-container">
    <div class="container mt-4">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <a href="{{ route('reportes.index') }}" class="d-inline-flex align-items-center text-dark text-decoration-none">
                <i class="fas fa-arrow-left me-2" style="font-size:1.5rem;"></i>
                <h2 class="mb-0"><i class="fas fa-tools"></i> Reporte: Estado de Conservacion de Espacios</h2>
            </a>
            <div class="report-actions">
                <a href="{{ route('reportes.infraestructura.exportar') }}" class="btn btn-success me-2">
                    <i class="fas fa-file-excel"></i> Exportar CSV
                </a>
                <a href="{{ route('reportes.infraestructura.pdf') }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </a>
            </div>
        </div>

        @php
        // Aseguramos que $infraestructura exista (puede venir como collection o array)
        $col = collect($infraestructura ?? []);

        // Normalizar, eliminar espacios y convertir a mayúsculas
        $estados_normalizados = $col
        ->pluck('estado_conservacion')
        ->map(function($v){
        $v = is_null($v) ? '' : (string)$v;
        return strtoupper(trim($v));
        });

        // Contar por cada valor encontrado
        $conservacionAgrupado = $estados_normalizados->countBy(); // Collection: ['BUENO' => x, 'REGULAR' => y, ...]

        // Etiquetas fijas (orden deseado)
        $labels = ['BUENO','REGULAR','MALO'];

        // Datos numéricos en el mismo orden de $labels
        $data = array_map(function($k) use ($conservacionAgrupado) {
        return (int) ($conservacionAgrupado->get($k, 0));
        }, $labels);

        $totalGeneral = array_sum($data);
        @endphp

        @if($totalGeneral === 0)
        <p class="text-center mt-4">No hay datos disponibles para este reporte.</p>
        @else
        <div class="report-grid mt-4">
            <div class="reporte-flex d-flex flex-wrap justify-content-between">
                {{-- Tarjetas de conservación --}}
                <div class="estatus-cards d-flex flex-wrap gap-3">
                    <div class="card-estado status-bueno">
                        <h3>BUENO</h3>
                        <p class="cantidad">{{ $conservacionAgrupado->get('BUENO', 0) }}</p>
                        <span>Espacios</span>
                    </div>
                    <div class="card-estado status-regular">
                        <h3>REGULAR</h3>
                        <p class="cantidad">{{ $conservacionAgrupado->get('REGULAR', 0) }}</p>
                        <span>Espacios</span>
                    </div>
                    <div class="card-estado status-malo">
                        <h3>MALO</h3>
                        <p class="cantidad">{{ $conservacionAgrupado->get('MALO', 0) }}</p>
                        <span>Espacios</span>
                    </div>
                    <div class="total-text w-100 mt-3">
                        <strong>Total de espacios: {{ $totalGeneral }}</strong>
                    </div>
                </div>

                {{-- Gráfica --}}
                <div class="chart-wrapper" style="flex:1; min-width:300px; max-width:450px;">
                    <canvas id="graficaInfra"></canvas>
                </div>
            </div>
        </div>
        @endif
    </div>
</main>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('graficaInfra')?.getContext('2d');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Nº de Espacios',
                        data: @json($data),
                        backgroundColor: ['#d1fae5', '#ffe8b3', '#fee2e2'], // BUENO, REGULAR, MALO
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection