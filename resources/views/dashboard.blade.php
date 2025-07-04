<!DOCTYPE html>
<!--Vista del dashboard-->
<html lang="es">

<style>
    .perfil-btn {
        display: inline-block;
        width: auto;
        padding: 8px 16px;
        background-color: #641C16;
        color: #fff;
        text-align: center;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 600;
        margin-top: 10px;
        transition: background-color 0.3s ease;
    }

    .perfil-btn:hover {
        background-color: #4b1511;
    }
</style>

<head>
    <meta charset="UTF-8">
    <title>Panel principal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="card-title">Bienvenido, {{ session('nombre_completo') ?? 'Usuario' }} 👋</h1>
                <p class="card-text">Has iniciado sesion correctamente</p>

                <form action="{{route('logout')}}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Cerrar sesion</button>
                </form>
                <a href="{{route('perfil')}}" class="perfil-btn">Ver Perfil</a>
            </div>
        </div>
    </div>

</body>

</html>