<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Mi Aplicación')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: rgba(212, 99, 65, 0.93);
            /* color vino de fondo */
            font-family: 'Inter', sans-serif;
            color: #fff;
        }

        .navbar {
            background-color: #4E100B;
            /* vino más oscuro para el navbar */
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.3rem;
        }

        .btn-outline-light {
            border-color: #fff;
            color: #fff;
        }

        .btn-outline-light:hover {
            background-color: #fff;
            color: #4E100B;
        }

        .content-box {
            background-color: #fff;
            color: #000;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        footer {
            color: #fff;
            text-align: center;
            margin-top: 2rem;
            padding-bottom: 1rem;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">ATLAS DE PUEBLA</a>
            <div class="ms-auto">
                <form action="{{route('logout')}}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container">
        <div class="content-box">
            @yield('content')
        </div>
    </main>

    <footer>
        © 2025 ATLAS DE PUEBLA
    </footer>

</body>

</html>