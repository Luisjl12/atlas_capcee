<?php if($paginator->hasPages()): ?>
<nav class="pagination-container" aria-label="Navegación de páginas">
    <ul class="pagination">

        
        <?php if($paginator->onFirstPage()): ?>
        <li class="page-item disabled">
            <span class="page-link">&laquo;</span>
        </li>
        <?php else: ?>
        <li class="page-item">
            <a class="page-link" href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev">&laquo;</a>
        </li>
        <?php endif; ?>

        
        <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        
        <?php if(is_string($element)): ?>
        <li class="page-item disabled">
            <span class="page-link"><?php echo e($element); ?></span>
        </li>
        <?php endif; ?>

        
        <?php if(is_array($element)): ?>
        <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li class="page-item <?php echo e($page == $paginator->currentPage() ? 'active' : ''); ?>">
            <a class="page-link" href="<?php echo e($url); ?>"><?php echo e($page); ?></a>
        </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php if($paginator->hasMorePages()): ?>
        <li class="page-item">
            <a class="page-link" href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next">&raquo;</a>
        </li>
        <?php else: ?>
        <li class="page-item disabled">
            <span class="page-link">&raquo;</span>
        </li>
        <?php endif; ?>

    </ul>
</nav>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/vendor/pagination/mi_paginacion.blade.php ENDPATH**/ ?>