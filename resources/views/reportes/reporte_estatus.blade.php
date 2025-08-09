@extends('layouts.app')

@section('title', 'Reporte de Estatus de Plantel')

@section('content')

<body>
    <main class="main-container">
        <div class="container mt-4">
            <div class="card-header-custom">
                <a href="{{route('reportes.index') }}" class="text-decoration-none d-inline-flex align-items-center text-dark">
                    <h2 class="mb-4">
                        <i class="fas fa-arrow-left "></i>
                        <i class="fas fa-check-circle"></i> Reporte de Estatus de Planteles
                    </h2>
                </a>
                <a href="{{ route('reportes.estatus.csv') }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Exportar CSV
                </a>
                <a href="{{route('reportes.estatus.pdf')}}" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </a>
            </div>

            <div class="table-responsive mt-3 data-table-container">
                <table class="table data-table">
                    <thead class="thead-custom">
                        <tr>
                            <th>CCT</th>
                            <th>Nombre del Plantel</th>
                            <th>Ubicacion</th>
                            <TH>Estatus</TH>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($planteles as $p)
                        <tr>
                            <td>{{$p->cct}}</td>
                            <td>{{$p->nombre_escuela}}</td>
                            <td>
                                {{$p->nombre_municipio ?? 'Sin municipio registrado'}},
                                {{$p->nombre_localidad ?? 'Sin localidad registrada'}},
                                {{$p->domicilio_calle_numero ?? ''}},
                                {{$p->domicilio_colonia ?? ''}},
                                CP {{$p->domicilio_cp ?? ''}}
                            </td>
                            <td>
                                @php
                                switch($p->estatus_plantel){
                                case 'Activo':
                                $badge = 'success';
                                break;
                                case 'Inactivo':
                                $badge = 'danger';
                                break;
                                case 'En proceso':
                                $badge = 'warning';
                                break;
                                case 'Cerrado':
                                $badge = 'secondary';
                                break;
                                default:
                                $badge = 'info';
                                break;
                                }
                                @endphp
                                <span class="badge status-{{ strtolower($p->estatus_plantel) }}">{{str_replace('_', ' ', $p->estatus_plantel) }}</span>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </main>
</body>
@endsection