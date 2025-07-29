<div class="table-responsive mt-3 data-table-container">
    <table class="table table-striped table-bordered table-hover data-table">
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
        <tbody>
            @forelse ($planteles as $plantel)
            <tr>
                <td data-label="CCT">{{ $plantel->cct }}</td>
                <td data-label="Nombre">{{ $plantel->nombre_escuela }}</td>
                <td data-label="Municipio">{{ $plantel->municipio->nombre_municipio ?? 'N/D' }}</td>
                <td data-label="Director">{{ $plantel->nombre_director_registrado ?? 'No asignado' }}</td>
                <td data-label="Estatus">
                    <span class="badge status-{{ strtolower($plantel->estatus_plantel) }}">
                        {{ $plantel->estatus_plantel }}
                    </span>
                </td>
                <td data-label="Acciones">
                    <div class="acciones-btns">
                        <a href="{{ route('planteles.show', $plantel->id) }}" class="btn btn-sm btn-info" title="Ver Detalle Completo">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('planteles.edit', $plantel->id) }}" class="btn btn-sm btn-primary" title="Editar Ficha Base">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('planteles.destroy', $plantel->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" title="Eliminar Plantel"
                                onclick="return confirm('¿Estás seguro de eliminar este plantel?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
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