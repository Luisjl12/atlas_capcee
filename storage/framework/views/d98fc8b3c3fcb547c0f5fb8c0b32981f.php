<div id="<?php echo e($id); ?>" class="modal">
    <div class="modal-content">
        <span class="close" data-close="<?php echo e($id); ?>">&times;</span>
        <h3><?php echo e($titulo); ?></h3>

        <form id="<?php echo e($formId); ?>">
            <?php echo e($slot); ?>

            <button type="submit">Aplicar filtros</button>
        </form>
    </div>
</div><?php /**PATH /home1/bcecacef/atlasinfraescolarpueblaa.online/resources/views/components/modal-filtros.blade.php ENDPATH**/ ?>