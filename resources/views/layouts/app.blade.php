<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Mi aplicación')</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Estilos personalizados --}}

    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">



</head>


<body>

    {{-- Barra de navegación --}}
    <nav class="main-header">

        <div class="logo-container">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('img/logo-atlas.png') }}" alt="Logo Atlas de Puebla" class="logo-img">
            </a>
        </div>

      <div class="user-nav d-flex align-items-center gap-2">
    
        <a href="/saltar-a-siie" target="_blank" 
            class="btn btn-outline-custom px-3 fw-bold shadow-sm">
            <i class="fas fa-external-link-alt me-1"></i> Ir al SIIE Puebla
        </a>

            <div class="d-flex gap-2">
                <form action="{{ route('perfil') }}" method="GET" class="d-inline">
                    <button type="submit" class="boton-personalizado">
                        <i class="fas fa-user-circle"></i> Admin
                    </button>
                </form>

                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="boton-personalizado" style="background-color:#e4472e;">
                        <i class="fas fa-sign-out-alt"></i> Salir
                    </button>
                </form>
            </div>
        </div>
    </nav>

    {{-- Contenido principal --}}
    <main class="main-container">
        <div class="container mt-4">
            @yield('content')
        </div>
    </main>

    {{-- Pie de página --}}
    <footer class="main-footer">
        © 2025 ATLAS DE PUEBLA. Todos los derechos reservados.
    </footer>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{--Script para alertas--}}
    <script src="{{asset('js/alerts.js')}}"></script>
    
    {{--Script nombre de usuario --}}
    <script src="{{asset('js/display-nombre.js')}}"></script>
    
    {{-- Scripts adicionales desde @push --}}
    @stack('scripts')

</body>

</html>