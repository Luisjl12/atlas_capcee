@extends('layouts.app')

@section('title', 'Generar reportes')

@section('content')

<body>
    <main class="main-container">
        <div class="container mt-4">
            <div class="card-header-custom">
                <a href="{{ session('role_id') == 1 ? route('dashboard.admin') : route('dashboard.analista') }}"
                    class="text-decoration-none d-inline-flex align-items-center text-dark">
                    <h4 class="mb-4">
                        <i class="fas fa-arrow-left "></i>
                        <i class="fas fa-chart-bar"></i> Panel de Reportes
                    </h4>
                </a>

            </div>

            <div class="card-body-custom p-4">
                <p><strong>Selecciona un reporte de la lista para ver los datos.</strong></p>

                <div class="report-list">
                    <div class="report-item">
                        <div class="report-icon">
                            <i class="fas fa-city"></i>
                        </div>
                        <div class="report-info">
                            <h4>Planteles por Municipio</h4>
                            <p>Un resumen que cuenta cuántos planteles educativos están registrados en cada municipio.
                            </p>
                            <a href="{{ route('reportes.municipio') }}" class="btn btn-primary">Ver
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
                            <a href="{{route('reportes.estatus')}}" class="btn btn-primary " title="Próximamente">Ver Reporte</a>
                        </div>
                    </div>

                    <div class="report-item disabled">
                        <div class="report-icon">
                            <i class="fas fa-tools"></i>
                        </div>
                        <div class="report-info">
                            <h4>Reporte de Infraestructura</h4>
                            <p>Muestra un resumen del estado de conservación de los espacios.</p>
                            <a href="#" class="btn btn-secondary disabled-link" title="Próximamente">Ver Reporte</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        </div>
    </main>
</body>
@endsection