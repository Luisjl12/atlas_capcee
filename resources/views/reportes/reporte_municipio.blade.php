@extends('layouts.app')

@section('title', 'Reportes por municipio')

@section('content')

<body>
    <main class="main-container">
        <div class="container mt-4">
            <div class="card-header-custom">
                <a href="{{ route('reportes.index') }}" class="text-decoration-none d-inline-flex align-items-center text-dark">
                    <h2 class="mb-4">
                        <i class="fas fa-arrow-left "></i>
                        <i class="fas fa-city"></i> Reporte: Conteo de Planteles por Municipio
                    </h2>
                </a>
                <a href="{{route('reportes.municipios.exportar')}}" class="btn btn-success"><i class="fas fa-file-excel"></i> Exportar a CSV</a>
                <a href="{{route('reportes.municipio.pdf')}}" class="btn btn-danger"><i class="fas fa-file-pdf"></i>Exportar a PDF</a>

            </div>

            <div class="table-responsive mt-3 data-table-container">
                <table class="table table-striped table-bordered table-hover data-table">
                    <thead class="thead-custom">
                        <tr>
                            <th>Municipio</th>
                            <th>Localidad</th>
                            <th>Nombre de los Planteles</th>
                            <th>Total de Planteles Registrados</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Fila de total general --}}

                        {{-- Mostrar cada municipio y localidad--}}
                        @foreach ($datos as $fila)
                        <tr>
                            <td>{{$fila->municipio}}</td>
                            <td>{{$fila->localidad}}</td>
                            <td>{!! str_replace(', ', '<br>', $fila->nombre_planteles) !!}</td>
                            <td>{{$fila->total_planteles}}</td>
                        </tr>
                        @endforeach
                        @if($datos->isEmpty())
                        <tr>
                            <td colspan="3" class="text-center">No hay datos disponibles para este reporte.</td>
                        </tr>
                        @endif
                        {{-- Fila de total general --}}
                        <tr class="table-total-row">
                            <td><strong>TOTAL GENERAL</strong></td>
                            <td><strong>/</strong></td>
                            <td><strong>/</strong></td>
                            <td><strong>{{ $totalGeneral }}</strong></td>
                        </tr>
                    </tbody>

                </table>
            </div>
        </div>
    </main>
</body>
@endsection