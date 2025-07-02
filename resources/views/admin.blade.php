@extends('layouts.app')
@section('content')
<h1>Has iniciado sesión como Administrador</h1>
<a href="{{route('perfil')}}" class="perfil-btn">Ver Perfil</a>
<p>Bienvenido, {{ session('nombre_completo') }}</p>
@endsection