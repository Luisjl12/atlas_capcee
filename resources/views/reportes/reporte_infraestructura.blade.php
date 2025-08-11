@extends('layouts.app')

@section('title', 'Reportes infraestructura')

@section('content')

<body>
    <main class="main-container">
        <div class="container mt-4">
            <div class="card-header-custom">
                <a href="{{ route('reportes.index') }}" class="text-decoration-none d-inline-flex align-items-center text-dark">
                    <h2 class="mb-4">
                        <i class="fas fa-arrow-left "></i>
                        <i class="fas fa-tools"></i> Reporte de infraestructura
                    </h2>
                </a>
                <a href="{{ route('reportes.infraestructura.exportar') }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Exportar CSV
                </a>
                <a href="{{route('reportes.infraestructura.pdf')}}" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </a>

            </div>

            <div class="table-responsive mt-3 data-table-container">
                <table class="table data-table">
                    <thead class="thead-custom">
                        <tr>
                            <th>CCT</th>
                            <th>Nombre de la Escuela</th>
                            <th>Fuente de Agua</th>
                            <th>Tipo de Drenaje</th>
                            <th>Contrato de Elecricidad</th>
                            <th>Telefonia Fija</th>
                            <th>Acceso a Internet</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($infraestructura as $item)
                        <tr>
                            <td>{{$item->cct}}</td>
                            <td>{{$item->nombre_escuela}}</td>
                            <td>{{$item->fuente_agua ?? 'No Registrada'}}</td>
                            <td>{{$item->tipo_drenaje ?? 'No Registrado'}}</td>
                            <td>{{$item->electricidad_contrato == 1 ? 'Sí' : 'No'}}</td>
                            <td>{{$item->telefonia_fija == 1 ? 'Sí' : 'No'}}</td>
                            <td>{{$item->internet_acceso == 1 ? 'Sí' : 'No' }}</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>


        </div>
    </main>
</body>

@endsection