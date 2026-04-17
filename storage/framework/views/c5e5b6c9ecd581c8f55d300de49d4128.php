<!DOCTYPE html>
<!--Vista del login -->
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Atlas de Puebla</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="<?php echo e(asset('css/style.css')); ?>" rel="stylesheet">
</head>

<body class="login-page-body">
    <div class="login-container">
        <div class="login-logo">
            <img src="<?php echo e(asset('img/logo-atlas.png')); ?>" alt="Logo" style="width: 120px; height: auto;">
        </div>

        <div class="title"></div>

        
        <?php if(Session::has('success')): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i>
            <?php echo e(Session::get('success')); ?>

        </div>
        <?php endif; ?>

        
        <?php if($errors->any()): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div><?php echo e($error); ?></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>

        
        <form method="POST" action="<?php echo e(route('login')); ?>" class="login-form">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i>Correo Electrónico:</label>
                <input type="email" name="email" id="email" class="form-control" required autofocus>
            </div>

            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Contraseña:</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-custom btn-primary btn-block"><i class="fas fa-sign-in-alt"></i>
                    Ingresar</button>
            </div>
        </form>
    </div>

    <footer class="login-footer">
        <div class="footer">© 2025 ATLAS DE PUEBLA</div>
    </footer>
    
    
    <script src="<?php echo e(asset('js/alerts.js')); ?>"></script>
    
</body>

</html><?php /**PATH /home1/bcecacef/atlasinfraescolarpueblaa.online/resources/views/auth/login.blade.php ENDPATH**/ ?>