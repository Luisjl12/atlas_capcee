{{-- resources/views/usuarios/partials/tabla_usuarios.blade.php --}}
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
        <tbody>
            @forelse ($usuarios as $usuario)
            <tr>
                <td data-label="ID">{{ $usuario->id }}</td>
                <td data-label="Nombre">{{ $usuario->nombre_completo }}</td>
                <td data-label="Correo">{{ $usuario->correo_electronico }}</td>
                <td data-label="Rol">{{ $usuario->rol->nombre_rol ?? 'N/D' }}</td>
                <td data-label="Estatus">
                    <span class="badge status-{{ strtolower($usuario->estado) }}">{{ $usuario->estado }}</span>
                </td>
                <td data-label="Acciones">
                    <div class="acciones-btns">
                        <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-sm btn-vino">
                            <i class="fas fa-pen"></i>
                        </a>

                        @if(auth()->id() !== $usuario->id)
                        <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('¿Seguro que quieres eliminar a {{ $usuario->nombre_completo }}?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
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
</div>