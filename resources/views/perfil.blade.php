@extends('layouts.app')

@section('title', 'Mi perfil')

@section('content')
<div class="container mt-5">
    <h2>Perfil del Usuario</h2>
    <div class="card p-4 shadow-sm">
        <p><strong>Nombre completo:</strong> {{ session ('nombre_completo') }}</p>
        <p><strong>Correo electrónico:</strong> {{ session ('correo_electronico') }}</p>
        <p><strong>Teléfono:</strong> {{ session('telefono_contacto') ?? 'No registrado' }}</p>
    </div>
</div>
@endsection