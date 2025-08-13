@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card-header-custom">
        <h2><i class="fas fa-chart-line"></i> Detalle de Supervisión: CORDE {{ $corde->nombre_corde }}</h2>

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
            <tr>
                <td>{{ $plantel->cct }}</td>
                <td> {{ $plantel->nombre_escuela }}</td>
                <td>{{ number_format($plantel->porcentaje_avance_captuta, 2) }}</td>
                <td>{{ \Carbon\Carbon::parse($plantel->fecha_ultima_actualizacion_general)->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3">Este CORDE no tiene planteles asignados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection