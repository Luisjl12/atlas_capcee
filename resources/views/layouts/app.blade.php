<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi aplicación')</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Estilos personalizados --}}
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


</head>

<body>

    {{-- Barra de navegación --}}
    <nav class="main-header a">

        <div class="d-flex align-items-center">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('img/logo-atlas.png') }}" alt="Logo" class="logo-img" style="height: 75px;">
            </a>
            <a class="navbar-brand text-white fw-bold fs-5" href="{{ route('dashboard') }}"></a>
        </div>

        <div class="d-flex gap-2">
            <form action="{{ route('perfil') }}" method="GET" class="d-inline">
                @csrf
                <button type="submit" class="boton-personalizado">
                    <i class="fas fa-user-circle"></i> {{ session('nombre_completo') }}
                </button>
            </form>

            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="boton-personalizado" style="background-color:#e4472e;">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </button>
            </form>
        </div>
    </nav>

    {{-- Contenido principal --}}
    <main class="container my-4">
        <div class="content-box bg-white text-dark rounded p-4 shadow">
            @yield('content')
        </div>
    </main>

    {{-- Pie de página --}}
    <footer class="main-footer text-center text-muted py-3">

        © 2025 ATLAS DE PUEBLA. Todos los derechos reservados.
    </footer>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Scripts adicionales desde @push --}}
    @stack('scripts')

</body>

</html>