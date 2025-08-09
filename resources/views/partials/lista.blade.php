<div class="table-responsive mt-3 data-table-container">
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
            <tr class="fila-usuario">
                <td data-label="CCT" class="info-extra">{{ $plantel->cct }}</td>
                <td data-label="Nombre">{{ $plantel->nombre_escuela }}</td>
                <td data-label="Municipio" class="info-extra">{{ $plantel->municipio->nombre_municipio ?? 'N/D' }}</td>
                <td data-label="Director" class="info-extra">{{ $plantel->nombre_director_registrado ?? 'No asignado' }}</td>
                <td data-label="Estatus" class="info-extra">
                    <span class="badge status-{{ strtolower($plantel->estatus_plantel) }}">
                        {{ $plantel->estatus_plantel }}
                    </span>
                </td>

                <td data-label="Acciones" class="info-extra">

                    <div class="acciones-btns d-flex align-items-center gap-1 flex-nowrap">

                        <a href="{{ route('planteles.show', $plantel->id) }}" class="btn btn-sm btn-info" title="Ver Detalle Completo">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('planteles.edit', $plantel->id) }}" class="btn btn-sm btn-primary" title="Editar Ficha Base">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-danger"
                            onclick="mostrarModalConfirmacion('¿Seguro que quieres eliminar a este plantel?', '{{ route('planteles.destroy', $plantel->id) }}')">
                            <i class="fas fa-trash"></i>
                        </button>

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
            {{ $planteles->links() }}
        </ul>
    </nav>
    @endif
</div>