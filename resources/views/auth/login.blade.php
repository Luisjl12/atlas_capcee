<!DOCTYPE html>
<!--Vista del login -->
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Atlas de Puebla</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body class="login-page-body">
    <div class="login-container">
        <div class="login-logo">
            <img src="{{ asset('img/logo-atlas.png') }}" alt="Logo" style="width: 120px; height: auto;">
        </div>

        <div class="title"></div>

        {{-- Mensaje de sesión cerrada exitosamente --}}
        @if (Session::has('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i>
            {{ Session::get('success') }}
        </div>
        @endif

        {{-- Errores de login --}}
        @if ($errors->any())
        <div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i>
            @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
            @endforeach
        </div>
        @endif

        {{-- Formulario de login --}}
        <form method="POST" action="{{ route('login') }}" class="login-form">
            @csrf
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i>Correo Electrónico:</label>
                <input type="email" name="email" id="email" class="form-control" required autofocus>
            </div>

            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Contraseña:</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-sign-in-alt"></i>
                    Ingresar</button>
            </div>
        </form>
    </div>

    <footer class="login-footer">
        <div class="footer">© 2025 ATLAS DE PUEBLA</div>
    </footer>
</body>

</html>