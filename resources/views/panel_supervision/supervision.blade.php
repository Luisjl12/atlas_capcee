@extends('layouts.app')

@section ('content')
<!--Interfaz para panel principal o index de supervision-->

            @php
            use App\Helpers\RoleHelper;
            @endphp
            <div class="card-header-custom">
                <a href="{{ RoleHelper::gestionSupervision(session('role_id')) }}" class="btn-icon-only">
                    <i class="fas fa-arrow-left"></i>
                    <h2><i class="fas fa-tasks"></i> Panel de Supervisión</h2>
                </a>
            </div>
          
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
                            <!-- Fila principal visible solo en móvil -->
                            <tr class="corde-row d-table-row d-md-none">
                                <td colspan="5" class="corde-nombre position-relative" style="cursor: pointer;">
                                    <div>
                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                        {{ $dato->nombre_corde }}
                                    </div>
                                    <div class="toggle-icon position-absolute top-0 end-0 p-2">
                                        <i class="fas fa-chevron-down text-muted"></i>
                                    </div>
                                </td>
                            </tr>

                            <!-- Fila completa visible solo en escritorio -->
                            <tr class="d-none d-md-table-row">
                                <td>{{ $dato->nombre_corde }}</td>
                                <td>{{ $dato->total_planteles }}</td>
                                <td>{{ $dato->avance_promedio ? number_format($dato->avance_promedio, 2) : 'N/A' }}</td>
                                <td>{{ $dato->ultima_actualizacion ? \Carbon\Carbon::parse($dato->ultima_actualizacion)->format('d/m/Y') : 'Sin registro' }}</td>
                                <td>
                                    <div class="acciones-btns d-flex align-items-center gap-1 flex-nowrap">
                                        <a href="{{ route('supervision.show', ['id' => $dato->id]) }}" class="btn-custom btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Ver Detalle
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <!-- Detalles expandibles solo en móvil -->
                            <tr class="corde-detalle d-none d-md-none">
                                <td colspan="5">
                                    <div class="detalle-container d-flex flex-wrap justify-content-between gap-3">
                                        <div class="detalle-bloque flex-grow-1" style="min-width: 250px;">
                                            <strong>Total de Planteles:</strong> {{ $dato->total_planteles }}<br>
                                            <strong>Avance Promedio:</strong> {{ $dato->avance_promedio ? number_format($dato->avance_promedio, 2) : 'N/A' }}
                                        </div>
                                        <div class="detalle-bloque flex-grow-1" style="min-width: 250px;">
                                            <strong>Última Actualización:</strong>
                                            {{ $dato->ultima_actualizacion ? \Carbon\Carbon::parse($dato->ultima_actualizacion)->format('d/m/Y') : 'Sin registro' }}
                                        </div>
                                        <div class="w-100 mt-2">
                                            <a href="{{ route('supervision.show', ['id' => $dato->id]) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Ver Detalle
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            

@endsection

@push('scripts')

<script src="{{ asset('js/tabla-expandible-supervision.js')}}"></script>


@endpush