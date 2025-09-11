<div class="table-responsive mt-3 data-table-container">
    <!--Tabla para los planteles-->
    <table class="table data-table">
        <thead class="thead-custom">
            <tr>
                <th>CCT</th>
                <th>Nombre Escuela</th>
                <th>Municipio</th>
                <th>Director Asignado</th>
                <th>Estatus</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tbody-js">
            @forelse ($planteles as $plantel)
            <!-- Fila principal visible solo en móvil -->
            <tr class="plantel-row d-table-row d-md-none">
                <td colspan="6" class="plantel-nombre position-relative" style="cursor: pointer;"><strong>CCT</strong>
                    <div>
                        <i class=""></i>
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
                <td>{{ $plantel->municipio->nombre_municipio ?? 'N/D' }}</td>
                <td>{{ $plantel->nombre_director_registrado ?? 'No asignado' }}</td>
                <td>
                    <span class="badge status-{{ strtolower($plantel->estatus_plantel) }}">
                        {{ $plantel->estatus_plantel }}
                    </span>
                </td>
                <td>
                    <div class="acciones-btns d-flex align-items-center gap-1 flex-nowrap">
                        <a href="{{ route('planteles.show', $plantel->id) }}" class="btn btn-sm btn-info" title="Ver Detalle Completo">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('planteles.edit', $plantel->id) }}" class="btn btn-sm btn-primary" title="Editar Ficha Base">
                            <i class="fas fa-pen"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-danger"
                            onclick="mostrarModalConfirmacion('¿Seguro que quieres eliminar a este plantel?', '{{ route('planteles.destroy', $plantel->id) }}')">
                            <i class="fas fa-trash"></i>
                        </button>
                        <a href="{{ route('planteles.auditoria', $plantel->id) }}" class="btn btn-sm btn-secondary" title="Ver Historial de Cambios">
                            <i class="fas fa-history"></i>
                        </a>
                    </div>
                </td>
            </tr>

            <!-- Detalles expandibles solo en móvil -->
            <tr class="plantel-detalle d-none d-md-none">
                <td colspan="6">
                    <div class="detalle-container d-flex flex-wrap justify-content-between gap-3">
                        <div class="detalle-bloque flex-grow-1" style="min-width: 250px;">
                            <strong>CCT:</strong> {{ $plantel->cct }}<br>
                            <strong>Nombre:</strong>{{$plantel->nombre_escuela}}<br>
                            <strong>Municipio:</strong> {{ $plantel->municipio->nombre_municipio ?? 'N/D' }}<br>
                            <strong>Director:</strong> {{ $plantel->nombre_director_registrado ?? 'No asignado' }}

                        </div>
                        <div class="detalle-bloque flex-grow-1" style="min-width: 250px;">
                            <strong>Estatus:</strong>
                            <span class="badge status-{{ strtolower($plantel->estatus_plantel) }}">
                                {{ $plantel->estatus_plantel }}
                            </span>
                        </div>
                        <div class="w-100 mt-2"><strong>Acciones</strong>
                            <div class="acciones-btns d-flex align-items-center gap-1 flex-wrap">
                                <a href="{{ route('planteles.show', $plantel->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i><!--Ver-->
                                </a>
                                <a href="{{ route('planteles.edit', $plantel->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-pen"></i><!--Editar-->
                                </a>
                                <button type="button" class="btn btn-sm btn-danger"
                                    onclick="mostrarModalConfirmacion('¿Seguro que quieres eliminar a este plantel?', '{{ route('planteles.destroy', $plantel->id) }}')"><!--Eliminar-->
                                    <i class="fas fa-trash"></i>
                                </button>
                                <a href="{{ route('planteles.auditoria', $plantel->id) }}" class="btn btn-sm btn-secondary" title="Ver Historial de Cambios">
                                    <i class="fas fa-history"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No se encontraron planteles.</td>
            </tr>
            @endforelse

        </tbody>
    </table>

    {{-- Paginación --}}
    @if(method_exists($planteles, 'links'))
    <nav class="pagination-container" aria-label="Navegación de páginas">
        <ul class="pagination">
            <li class="page-item ">
                {{ $planteles->links('vendor.pagination.mi_paginacion') }}
            </li>
        </ul>
    </nav>
    @endif
</div>