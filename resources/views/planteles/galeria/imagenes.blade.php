<div class="row mt-4">
    @forelse ($fotos as $foto)
    <div class="col-md-3 mb-4 position-relative">
        <div class="card shadow-sm rounded">
            {{-- Botón de eliminar --}}
            <form action="{{ route('galeria.destroy', $foto->id) }}" method="POST" class="position-absolute top-0 end-0 m-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger"
                    onclick="return confirm('¿Estás seguro de que deseas eliminar esta foto?')">
                    &times;
                </button>
            </form>

            @php

            $url = Storage::url($foto->ruta_foto);
            $cct = $foto->plantel->cct ?? 'N/A';
            $nombrePlantel = $foto->plantel->nombre_escuela ?? 'Plantel desconocido';
            $nombreUsuario = $foto->usuario->nombre_completo ?? 'Usuario desconocido';
            $descripcion = $foto->descripcion_foto ?? 'Sin descripción';
            $fechaSubida = \Carbon\Carbon::parse($foto->fecha_subida ?? $foto->created_at)->format('Y-m-d H:i:s');


            $cctJs = addslashes($cct);
            $nombrePlantelJs = addslashes($nombrePlantel);
            $nombreUsuarioJs = addslashes($nombreUsuario);
            $descripcionJs = addslashes($descripcion);
            @endphp

            {{-- Imagen clickeable que abre el modal --}}
            <img src="{{ $url }}" class="img-fluid rounded-top" style="cursor:pointer"
                data-bs-toggle="modal"
                data-bs-target="#fotoModal"
                onclick="verDetallesFoto(
                    '{{ $url }}',
                    '{{ $cctJs }}',
                    '{{ $nombrePlantelJs }}',
                    '{{ $nombreUsuarioJs }}', 
                    '{{ $descripcionJs }}',
                    '{{ $fechaSubida }}'
                )">

            {{-- Descripción --}}
            <div class="card-body">
                <p class="card-text text-center">{{ $descripcion }}</p>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <p class="text-muted text-center">No hay fotos subidas para este plantel.</p>
    </div>
    @endforelse
</div>