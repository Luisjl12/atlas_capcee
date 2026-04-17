@extends('layouts.app')

@section('content')
    <div class="card-header-custom">
        <a href="{{route('panel.supervision')}}" class="btn-icon-only">
            <i class="fas fa-arrow-left "></i>
            <h2><i class="fas fa-chart-line"></i> Detalle de Supervisión: CORDE {{ $corde->nombre_corde }}</h2>
        </a>
    </div>
    <div class="card-body-custom p-4 mt-3">
        <h6>Gráfica de Avance por Plantel </h6>
        <div class="chart-container">
            <canvas id="graficaAvanceCorde"></canvas>
        </div>
    </div>
    <table class="table table-hover data-table">
        <thead class="thead-custom">
            <tr>
                <th>CCT</th>
                <th>Nombre Escuela</th>
                <th>Avance (%)</th>
                <th>Última Actualización</th>
            </tr>
        </thead>
        <tbody>
            @forelse($planteles as $plantel)
            <!-- Fila principal visible solo en móvil -->
            <tr class="plantel-row d-table-row d-md-none">
                <td colspan="4" class="plantel-cct position-relative" style="cursor: pointer;">
                    <div>
                        <i class="fas fa-school text-primary me-2"></i>
                        {{ $plantel->cct }}
                    </div>
                    <div class="toggle-icon position-absolute top-0 end-0 p-2">
                        <i class="fas fa-chevron-down text-muted"></i>
                    </div>
                </td>
            </tr>

            <!-- Fila completa visible solo en escritorio -->
            <tr class="d-none d-md-table-row">
                <td>{{ $plantel->cct }}</td>
                <td>{{ $plantel->nombre_escuela }}</td>
                <td>{{ number_format($plantel->porcentaje_avance_captura, 2) }}</td>
                <td>{{ \Carbon\Carbon::parse($plantel->fecha_ultima_actualizacion_general)->format('d/m/Y') }}</td>
            </tr>

            <!-- Detalles expandibles solo en móvil -->
            <tr class="plantel-detalle d-none d-md-none">
                <td colspan="4">
                    <div class="detalle-container d-flex flex-column gap-2">
                        <div><strong>Nombre Escuela:</strong> {{ $plantel->nombre_escuela }}</div>
                        <div><strong>Avance (%):</strong> {{ number_format($plantel->porcentaje_avance_captura, 2) }}</div>
                        <div><strong>Última Actualización:</strong> {{ \Carbon\Carbon::parse($plantel->fecha_ultima_actualizacion_general)->format('d/m/Y') }}</div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4">Este CORDE no tiene planteles asignados.</td>
            </tr>
            @endforelse

        </tbody>
    </table>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('graficaAvanceCorde')?.getContext('2d');
        if (ctx) {
            const labels = @json($labels_grafica);
            const data = @json($data_grafica);
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '% Avance',
                        data: data,
                        backgroundColor: 'rgba(154, 42, 42, 0.7)',
                        borderColor: 'rgba(100, 30, 22, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'CCT del plantel',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            }
                        },

                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: (v) => v + '%'
                            },
                            tittle: {
                                display: true,
                                text: 'Porcentaje de Avance'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: (c) => (c.dataset.label || '') + ': ' + c.parsed.y.toFixed(2) + '%'
                            }
                        }
                    }
                }
            });
        }
    });
</script>

<script src="{{ asset('js/tabla-expandible-detalle-supervision.js')}}"></script>

@endsection