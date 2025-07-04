@extends('layouts.app')
@section('content')
<!--Vista del dashboard del admistrador -->
<h1>Has iniciado sesión como Administrador</h1>
<p>Bienvenido, {{ session('nombre_completo') }}</p>
<div class="d-flex gap-2 mb-3">
    <a href="{{route('perfil')}}" class="btn btn-primary">Ver Perfil</a>
    <a href="{{route('gestion.usuarios')}}" class="btn btn-primary">Gestion de usuarios</a>
</div>

@endsection