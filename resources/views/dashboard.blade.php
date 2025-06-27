<!DOCTYPE html>
<html lang="es">

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
            </div>
        </div>
    </div>

</body>

</html>