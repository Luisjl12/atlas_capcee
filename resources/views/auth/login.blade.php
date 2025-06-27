<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Atlas de Puebla</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #641C16;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: #fff;
            padding: 40px 30px;
            border-radius: 8px;
            text-align: center;
            width: 100%;
            max-width: 400px;
            box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.1);
        }

        .login-container img {
            width: 50px;
            margin-bottom: 10px;
        }

        .title {
            font-size: 22px;
            font-weight: 600;
            color: #641C16;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            display: block;
            color: #641C16;
            margin-bottom: 5px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            outline: none;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background-color: #641C16;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 10px;
        }

        .btn:hover {
            background-color: #4b1511;
        }

        .alert {
            background-color: #d1e7dd;
            color: #0f5132;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .footer {
            position: fixed;
            bottom: 10px;
            left: 0;
            right: 0;
            text-align: center;
            color: #fff;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" style="width: 120px; height: auto;">

        <div class="title">ATLAS DE PUEBLA</div>

        {{-- Mensaje de sesión cerrada exitosamente --}}
        @if (Session::has('success'))
        <div class="alert">
            {{ Session::get('success') }}
        </div>
        @endif

        {{-- Errores de login --}}
        @if ($errors->any())
        <div class="alert" style="background-color: #f8d7da; color: #842029;">
            @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
            @endforeach
        </div>
        @endif

        {{-- Formulario de login --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" name="email" id="email" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" name="password" id="password" required>
            </div>

            <button type="submit" class="btn">Ingresar</button>
        </form>
    </div>

    <div class="footer">© 2025 ATLAS DE PUEBLA</div>
</body>

</html>