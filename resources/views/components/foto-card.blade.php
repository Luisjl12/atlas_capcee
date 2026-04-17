<!--Contenedores de las fotos de la seccion de galerias -->
@props(['foto'])

@php
$url = asset('galeria/' . $foto->ruta_foto);
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

{{-- Contenedor con ID único para manipulación desde JS --}}
<div class="col-md-3 mb-4 position-relative" id="foto-{{ $foto->id }}">
    <div class="card shadow-sm rounded" style="border-top: 3px solid #ccc;">

        {{-- Checkbox de selección --}}
        <div class="form-check position-absolute top-0 start-0 m-2">
            <input class="galeria-checkbox" type="checkbox" value="{{ $foto->id }}" name="fotosSeleccionadas[]">
        </div>

        {{-- Botón de eliminar individual --}}
        <button type="button" class="galeria-delete-btn"
            onclick="mostrarModalConfirmacion('¿Estás seguro de eliminar esta foto?', '{{ route('galeria.destroy', $foto->id) }}')">
            <i class="fas fa-times-circle"></i>
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
            <p class="card-text text-center">{{ $fechaSubida }}</p>
        </div>
    </div>
</div>