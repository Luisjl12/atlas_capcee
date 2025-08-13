@extends('layouts.app')

@section ('content')

<body>
    <main class="main-container">
        <div class="container mt-4">
            <div class="card-header-custom">
                <h2><i class="fas fa-tasks"></i> Panel de Supervisión</h2>
            </div>
            <div class="card-body-custom p-4">
                <p>A continuación se muestra el resumen del avance de captura de información por cada CORDE.</p>
                <div class="table-responsive mt-3 data-table-container">
                    <table class="table table-hover data-table">
                        <thead class="thead-custom">
                            <tr>
                                <th>CORDE</th>
                                <th>Total de Planteles</th>
                                <th>Avance Promedio</th>
                                <th>Última Actualización</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datos as $dato)
                            <tr>
                                <td>{{ $dato->nombre_corde }}</td>
                                <td>{{ $dato->total_planteles }}</td>
                                <td> {{ $dato->avance_promedio ? number_format($dato->avance_promedio, 2) : 'N/A' }}</td>
                                <td> {{ $dato->ultima_actualizacion ? \Carbon\Carbon::parse($dato->ultima_actualizacion)->format('d/m/Y') : 'Sin registro' }}</td>
                                <td>
                                    <div class="acciones-btns d-flex align-items-center gap-1 flex-nowrap">
                                        <a href="{{ route('supervision.show', ['id' => $dato->id]) }}" class="btn btn-sm btn-info" title="Ver Detalle Completo">
                                            <i class="fas fa-eye"></i> Ver Detalle
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>

@endsection