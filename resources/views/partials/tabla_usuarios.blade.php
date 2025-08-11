<main class="main-container">
    <div class="mt-3 data-table-container">
        <table class="table data-table">
            <thead class="thead-custom">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tbody-js">
                @forelse ($usuarios as $usuario)
                <tr class="fila-usuario">
                    <td data-label="ID" class="info-extra">{{ $usuario->id }}</td>
                    <td data-label="Nombre">{{ $usuario->nombre_completo }}</td>
                    <td data-label="Correo" class="info-extra">{{ $usuario->correo_electronico }}</td>
                    <td data-label="Rol" class="info-extra">{{ $usuario->rol->nombre_rol ?? 'N/D' }}</td>
                    <td data-label="Estatus" class="info-extra">
                        <span class=" badge status-{{ strtolower($usuario->estado) }}">{{ $usuario->estado }}</span>
                    </td>
                    <td data-label="Acciones" class="info-extra">
                        <div class="acciones-btns">
                            <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-sm btn-vino">
                                <i class="fas fa-pen"></i>
                            </a>

                            @if(auth()->id() !== $usuario->id)
                            <button type="button" class="btn btn-sm btn-danger"
                                onclick="mostrarModalConfirmacion('¿Seguro que quieres eliminar a {{ $usuario->nombre_completo }}?', '{{ route('usuarios.destroy', $usuario->id) }}')">
                                <i class="fas fa-trash"></i>
                            </button>



                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No hay usuarios registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{-- Paginación --}}
        @if(method_exists($usuarios, 'links'))
        <nav class="pagination-container" aria-label="Navegación de páginas">
            <ul class="pagination">
                <li class="page-item ">
                    {{ $usuarios->links('vendor.pagination.mi_paginacion') }}
                </li>
            </ul>
        </nav>
        @endif
    </div>
</main>