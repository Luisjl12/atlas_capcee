<div class="row mt-4">
    <?php $__empty_1 = true; $__currentLoopData = $fotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $foto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <?php if (isset($component)) { $__componentOriginale844bf68e53d3f89d56e1796578e033a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale844bf68e53d3f89d56e1796578e033a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.foto-card','data' => ['foto' => $foto]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('foto-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['foto' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($foto)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale844bf68e53d3f89d56e1796578e033a)): ?>
<?php $attributes = $__attributesOriginale844bf68e53d3f89d56e1796578e033a; ?>
<?php unset($__attributesOriginale844bf68e53d3f89d56e1796578e033a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale844bf68e53d3f89d56e1796578e033a)): ?>
<?php $component = $__componentOriginale844bf68e53d3f89d56e1796578e033a; ?>
<?php unset($__componentOriginale844bf68e53d3f89d56e1796578e033a); ?>
<?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="col-12">
        <p class="text-muted text-center">No hay fotos subidas para este plantel.</p>
    </div>
    <?php endif; ?>
</div>



<div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-contenido">

            
            
            <button id="btnPantallaCompleta" class="btn btn-outline-light" onclick="togglePantallaCompleta()" title="Pantalla completa">
                <i id="iconPantallaCompleta" class="fas fa-expand"></i>
            </button>

            
            
            <button class="cerrar-modal" onclick="cerrarModal()">✕</button>

            
            <div class="modal-body text-center">
                <img id="modalFoto" src="" class="img-fluid rounded shadow" style="max-height:80vh; object-fit:contain;">
                <hr class="bg-secondary">
                <p><strong>CCT:</strong> <span id="modalCCT"></span></p>
                <p><strong>Plantel:</strong> <span id="modalEscuela"></span></p>
                <p><strong>Subido por el usuario:</strong> <span id="modalUsuario"></span></p>
                <p><strong>Descripción:</strong> <span id="modalDescripcion"></span></p>
                <p><strong>Fecha de subida:</strong> <span id="modalFecha"></span></p>
            </div>

        </div>
    </div>
</div><?php /**PATH /home1/bcecacef/atlasinfraescolarpueblaa.online/resources/views/planteles/galeria/imagenes.blade.php ENDPATH**/ ?>