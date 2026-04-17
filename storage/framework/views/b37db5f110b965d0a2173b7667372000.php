<!--Contenedores de las fotos de la seccion de galerias -->
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['foto']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['foto']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
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
?>


<div class="col-md-3 mb-4 position-relative" id="foto-<?php echo e($foto->id); ?>">
    <div class="card shadow-sm rounded" style="border-top: 3px solid #ccc;">

        
        <div class="form-check position-absolute top-0 start-0 m-2">
            <input class="galeria-checkbox" type="checkbox" value="<?php echo e($foto->id); ?>" name="fotosSeleccionadas[]">
        </div>

        
        <button type="button" class="galeria-delete-btn"
            onclick="mostrarModalConfirmacion('¿Estás seguro de eliminar esta foto?', '<?php echo e(route('galeria.destroy', $foto->id)); ?>')">
            <i class="fas fa-times-circle"></i>
        </button>

        
        <img src="<?php echo e($url); ?>" class="img-fluid rounded-top" style="cursor:pointer"
            onclick="verDetallesFoto(
                '<?php echo e($url); ?>',
                '<?php echo e($cctJs); ?>',
                '<?php echo e($nombrePlantelJs); ?>',
                '<?php echo e($nombreUsuarioJs); ?>', 
                '<?php echo e($descripcionJs); ?>',
                '<?php echo e($fechaSubida); ?>'
            )">

        
        <div class="card-body">
            <p class="card-text text-center"><?php echo e($fechaSubida); ?></p>
        </div>
    </div>
</div><?php /**PATH /home1/bcecacef/atlasinfraescolarpueblaa.online/resources/views/components/foto-card.blade.php ENDPATH**/ ?>