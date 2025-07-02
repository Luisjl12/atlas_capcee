@extends('layouts.app')

@section('title', 'Cambiar contraseña')

@section('content')
<div class="container mt-5">
    <h2>Cambiar contraseña</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($erros->all() as $error)
            <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if (session('success'))
    <div class="alert alert-success">
        {{sesion ('success')}}
    </div>
    @endif

    <form method="POST" action="{{route('perfil.actualizar-password')}}">
        @csrf
        <div class="mb-3">
            <label for="password_actual" class="form-control" style="width: 200px; font-size: 0.85rem;">Contraseña actual</label>
            <input type="password" name="password_actual" id="password_actual" class="form-control" style="width: 200px; font-size: 0.85rem;" required>
        </div>

        <div class="mb-3">
            <label for="nueva_password" class="form-control" style="width: 200px; font-size: 0.85rem;">Nueva contraseña</label>
            <input type="password" name="nueva_password" id="nueva_password" class="form-control" style="width: 200px; font-size: 0.85rem;" required>
        </div>

        <div class="mb-3">
            <label for="nueva_password_confirmation" class="form-control" style="width: 200px; font-size: 0.85rem;">Confirmar contraseña</label>
            <input type="password" name="nueva_password_confirmation" id="nueva_password_confirmation" class="form-control" style="width: 200px; font-size: 0.85rem;" required>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar contraseña</button>
    </form>

</div>
@endsection