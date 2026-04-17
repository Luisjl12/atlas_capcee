@extends('layouts.app')

@section('title', 'Generar reportes')

@section('content')

<body>
            @php
            use App\Helpers\RoleHelper;
            @endphp
            <div class="card-header-custom">
                <a href="{{ RoleHelper::gestionReportes(session('role_id')) }}"
                    class="btn-icon-only">
                    <i class="fas fa-arrow-left "></i>
                    <h2><i class="fas fa-chart-bar"></i> Panel de Reportes</h2>
                </a>

            </div>

            <div class="card-body-custom p-4">
                <p>Selecciona un reporte de la lista para ver los datos.</p>

                <div class="report-list">
                    <div class="report-item">
                        <div class="report-icon">
                            <i class="fas fa-city"></i>
                        </div>
                        <div class="report-info">
                            <h4>Planteles por Municipio</h4>
                            <p>Un resumen que cuenta cuántos planteles educativos están registrados en cada municipio.
                            </p>
                            <a href="{{ route('reportes.municipio') }}" class="btn-custom btn-primary">Ver
                                Reporte</a>
                        </div>
                    </div>

                    <div class="report-item ">
                        <div class="report-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="report-info">
                            <h4>Reporte de Estatus de Planteles</h4>
                            <p>Cuenta cuántos planteles están Activos, Inactivos o En Revisión.</p>
                            <a href="{{route('reportes.estatus')}}" class="btn-custom btn-primary " title="Próximamente">Ver Reporte</a>
                        </div>
                    </div>

                    <div class="report-item ">
                        <div class="report-icon">
                            <i class="fas fa-tools"></i>
                        </div>
                        <div class="report-info">
                            <h4>Reporte de Infraestructura</h4>
                            <p>Muestra un resumen del estado de conservación de los espacios.</p>
                            <a href="{{route('reportes.infraestructura')}}" class="btn-custom btn-primary" title="Próximamente">Ver Reporte</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
</body>
@endsection