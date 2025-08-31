@extends('layouts.app')

@section('title', 'Reporte de Estatus de Planteles')

@section('content')
<main class="main-container">
    <div class="container mt-4">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <a href="{{ route('reportes.index') }}" class="d-inline-flex align-items-center text-dark text-decoration-none">
                <i class="fas fa-arrow-left me-2" style="font-size:1.5rem;"></i>
                <h2 class="mb-0"><i class="fas fa-check-circle"></i> Reporte de Estatus de Planteles</h2>
            </a>
            <div class="report-actions">
                <a href="{{ route('reportes.estatus.csv') }}" class="btn btn-success me-2">
                    <i class="fas fa-file-excel"></i> Exportar CSV
                </a>
                <a href="{{ route('reportes.estatus.pdf') }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </a>
            </div>
        </div>

        @php
        // Agrupamos por estatus y contamos
        $estatusAgrupado = $planteles->groupBy('estatus_plantel')->map->count();

        $totalGeneral = $planteles->count();

        // Preparamos datos para Chart.js
        $labels = $estatusAgrupado->keys();
        $data = $estatusAgrupado->values();
        @endphp

        @if($totalGeneral === 0)
        <p class="text-center mt-4">No hay datos disponibles para este reporte.</p>
        @else
        <div class="report-grid mt-4">
            <div class="reporte-flex d-flex flex-wrap justify-content-between">
                {{-- Tarjetas de estatus --}}
                <div class="estatus-cards d-flex flex-wrap gap-3">
                    <div class="card-estado activo">
                        <i class="fas fa-check"></i>
                        <h3>ACTIVO</h3>
                        <p class="cantidad">{{ $estatusAgrupado['ACTIVO'] ?? 0 }}</p>
                        <span>Planteles</span>
                    </div>
                    <div class="card-estado inactivo">
                        <i class="fas fa-times"></i>
                        <h3>INACTIVO</h3>
                        <p class="cantidad">{{ $estatusAgrupado['INACTIVO'] ?? 0 }}</p>
                        <span>Planteles</span>
                    </div>
                    <div class="card-estado revision">
                        <i class="fas fa-hourglass-half"></i>
                        <h3>EN REVISIÓN</h3>
                        <p class="cantidad">{{ $estatusAgrupado['EN_REVISION'] ?? 0 }}</p>
                        <span>Planteles</span>
                    </div>

                    <div class="total-text w-100 mt-3">
                        <strong>Total de planteles: {{ $totalGeneral }}</strong>
                    </div>
                </div>

                {{-- Gráfica --}}
                <div class="chart-wrapper" style="flex:1; min-width:300px; max-width:450px;">
                    <canvas id="graficaEstatus"></canvas>
                </div>
            </div>
        </div>
        @endif
    </div>
</main>

{{-- Script de Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('graficaEstatus')?.getContext('2d');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Planteles',
                        data: @json($data),
                        backgroundColor: ['#d1fae5', '#fee2e2', '#ffe8b3', '#e5e7eb'], // colores para cada estatus
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 15,
                                padding: 15
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection