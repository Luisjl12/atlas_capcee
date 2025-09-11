@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Restablecer contraseña</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('password.reset') }}">
        @csrf
        <input type="hidden" name="correo_electronico" value="{{ $email }}">
        <input type="hidden" name="code" value="{{ $code }}">

        <div class="mb-3">
            <label for="password">Nueva contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation">Confirmar nueva contraseña</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Cambiar contraseña</button>
    </form>
</div>
@endsection