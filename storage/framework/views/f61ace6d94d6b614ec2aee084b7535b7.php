<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Mi aplicación'); ?></title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    

    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>?v=<?php echo e(time()); ?>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">



</head>


<body>

    
    <nav class="main-header">

        <div class="logo-container">
            <a href="<?php echo e(route('dashboard')); ?>">
                <img src="<?php echo e(asset('img/logo-atlas.png')); ?>" alt="Logo Atlas de Puebla" class="logo-img">
            </a>
        </div>

      <div class="user-nav d-flex align-items-center gap-2">
    
        <a href="https://www.siiepuebla.com.mx/login" target="_blank" 
            class="btn btn-outline-custom px-3 fw-bold shadow-sm">
            <i class="fas fa-external-link-alt me-1"></i> Ir al SIIE Puebla
        </a>

            <div class="d-flex gap-2">
                <form action="<?php echo e(route('perfil')); ?>" method="GET" class="d-inline">
                    <button type="submit" class="boton-personalizado">
                        <i class="fas fa-user-circle"></i> Admin
                    </button>
                </form>

                <form action="<?php echo e(route('logout')); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="boton-personalizado" style="background-color:#e4472e;">
                        <i class="fas fa-sign-out-alt"></i> Salir
                    </button>
                </form>
            </div>
        </div>
    </nav>

    
    <main class="main-container">
        <div class="container mt-4">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>

    
    <footer class="main-footer">
        © 2025 ATLAS DE PUEBLA. Todos los derechos reservados.
    </footer>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    
    <script src="<?php echo e(asset('js/alerts.js')); ?>"></script>
    
    
    <script src="<?php echo e(asset('js/display-nombre.js')); ?>"></script>
    
    
    <?php echo $__env->yieldPushContent('scripts'); ?>

</body>

</html><?php /**PATH C:\xampp\htdocs\atlas_local\resources\views/layouts/app.blade.php ENDPATH**/ ?>