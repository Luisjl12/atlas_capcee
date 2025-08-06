@extends('layouts.app')

@section('title', 'Mi perfil')

@section('content')
@php
$usuario = \App\Models\Usuario::find(session('id'));
@endphp

<div class="container mt-4">
    <div class="card-header-custom">
        <a href="
        {{
        session('role_id') == 1 ? route('dashboard.admin') :
        (session('role_id') == 2 ? route('dashboard.analista') :
        (session('role_id') == 3 ? route('dashboard.supervisor') :
        route('dashboard.director')))

        }}"
            class="text-decoration-none d-inline-flex align-items-center text-dark">
            <h2>
                <i class="fas fa-arrow-left "></i>
                <i class="fas fa-user-edit"></i> Mi Perfil
            </h2>
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif

    @if ($usuario)
    <div class="row profile-container mt-3">

        <!-- Información Personal -->
        <div class="profile-card p-4 shadow-sm rounded bg-white">
            <h4 class="mb-3"><i class="fas fa-id-card"></i> Información Personal</h4>
            <form action="{{ route('perfil.actualizarDatos') }}" method="POST" class="form-ficha-base">
                @csrf

                <div class="form-group mb-3">
                    <label for="nombre_completo">Nombre completo:</label>
                    <input type="text" id="nombre_completo" name="nombre_completo" class="form-control"
                        value="{{ old('nombre_completo', $usuario->nombre_completo) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="correo_electronico">Correo electrónico (no se puede cambiar):</label>
                    <input type="email" id="correo_electronico" class="form-control"
                        value="{{ $usuario->correo_electronico }}" disabled>
                </div>

                <div class="form-group mb-3">
                    <label for="telefono_contacto">Teléfono de contacto:</label>
                    <div class="input-group">
                        <input type="tel" id="telefono_contacto" name="telefono_contacto" class="form-control"
                            value="{{ old('telefono_contacto', $usuario->telefono_contacto) }}">
                        <button type="button" class="btn btn-secondary disabled-link" title="Próximamente: Validar por WhatsApp/SMS" disabled>Validar</button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar cambios</button>
            </form>
        </div>

        <!-- Cambiar Contraseña -->
        <div class="profile-card p-4 shadow-sm rounded bg-white">
            <h4 class="mb-3"><i class="fas fa-key"></i> Cambiar Contraseña</h4>
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form action="{{ route('perfil.actualizar-password') }}" method="POST" class="form-ficha-base">
                @csrf
                <!-- Contraseña actual -->
                <div class="form-group mb-3">
                    <label for="password_actual">Contraseña actual:</label>
                    <input type="password" name="password_actual" id="password_actual" class="form-control" required>
                    @error('password_actual')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Nueva contraseña -->
                <div class="form-group mb-3">
                    <label for="nueva_password">Nueva contraseña:</label>
                    <input type="password" name="nueva_password" id="nueva_password" class="form-control" required>
                    @error('nueva_password')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Confirmar nueva contraseña -->
                <div class="form-group mb-3">
                    <label for="nueva_password_confirmation">Confirmar nueva contraseña:</label>
                    <input type="password" name="nueva_password_confirmation" id="nueva_password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fas fa-key"></i> Cambiar contraseña</button>
            </form>
        </div>
    </div>
    @else
    <div class="alert alert-danger mt-3">
        No se pudo encontrar la información del usuario.
    </div>
    @endif
</div>
@endsection