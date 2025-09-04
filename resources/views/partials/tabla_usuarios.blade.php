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
            <!-- Fila principal visible solo en móvil -->
            <tr class="usuario-row d-table-row d-md-none">
                <td colspan="6" class="usuario-nombre position-relative" style="cursor: pointer;"><strong>Nombre:</strong>
                    <div>

                        {{ $usuario->nombre_completo }}
                    </div>
                    <div class="toggle-icon position-absolute top-0 end-0 p-2">
                        <i class="fas fa-chevron-down text-muted"></i>
                    </div>
                </td>
            </tr>

            <!-- Fila completa visible solo en escritorio -->
            <tr class="d-none d-md-table-row">
                <td>{{ $usuario->id }}</td>
                <td>{{ $usuario->nombre_completo }}</td>
                <td>{{ $usuario->correo_electronico }}</td>
                <td>{{ $usuario->rol->nombre_rol ?? 'N/D' }}</td>
                <td>
                    <span class="badge status-{{ strtolower($usuario->estado) }}">{{ $usuario->estado }}</span>
                </td>
                <td>
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

            <!-- Detalles expandibles solo en móvil -->
            <tr class="usuario-detalle d-none d-md-none">
                <td colspan="6">
                    <div class="detalle-container d-flex flex-wrap justify-content-between gap-3">
                        <div class="detalle-bloque flex-grow-1" style="min-width: 250px;">
                            <strong>Nombre:</strong> {{ $usuario->nombre_completo }}<br>
                            <strong>ID:</strong> {{ $usuario->id }}<br>
                            <strong>Correo:</strong> {{ $usuario->correo_electronico }}<br>
                            <strong>Rol:</strong> {{ $usuario->rol->nombre_rol ?? 'N/D' }}
                        </div>
                        <div class="detalle-bloque flex-grow-1" style="min-width: 250px;">
                            <strong>Estatus:</strong>
                            <span class="badge status-{{ strtolower($usuario->estado) }}">{{ $usuario->estado }}</span>
                        </div>
                        <div class="w-100 mt-2">
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
                        </div>
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