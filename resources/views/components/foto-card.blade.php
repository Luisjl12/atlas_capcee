@props(['foto'])

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

<div class="col-md-3 mb-4 position-relative">
    <div class="card shadow-sm rounded">
        {{-- Botón de eliminar --}}
        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 start-0 m-2 z-3"
            onclick="mostrarModalConfirmacion('¿Estás seguro de eliminar esta foto?', '{{ route('galeria.destroy', $foto->id) }}')">
            <i class="fas fa-trash"></i>
        </button>

        {{-- Imagen clickeable que abre el modal --}}
        <img src="{{ $url }}" class="img-fluid rounded-top" style="cursor:pointer"
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