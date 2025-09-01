@extends('layouts.app')

@section('title', 'Mi perfil')

@section('content')
@php
$usuario = \App\Models\Usuario::find(session('id'));
@endphp


<body>


    <div class="">
        @php
        use App\Helpers\RoleHelper;
        @endphp
        <div class="card-header-custom">
            <a href="{{ RoleHelper::dashboardRoute(session('role_id')) }}" class="text-decoration-none d-inline-flex align-items-center text-dark">
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
            <div class="profile-card">
                <div class="card-header">
                    <h3>Información Personal</h3>
                    <button type="button" class="btn-edit" onclick="toggleEdit('info')">
                        <i class="fas fa-pen"></i> Editar
                    </button>
                </div>

                <form action="{{ route('perfil.actualizarDatos') }}" method="POST" id="form-info">
                    @csrf
                    <div class="info-row">
                        <div class="form-group">
                            <label>Nombre Completo</label>
                            <input type="text" name="nombre_completo" id="nombre_completo" class="form-control"
                                value="{{ old('nombre_completo', $usuario->nombre_completo) }}" disabled>
                        </div>

                        <div class="form-group">
                            <label>Teléfono de Contacto</label>
                            <div class="input-group">
                                <input type="tel" name="telefono_contacto" id="telefono_contacto" class="form-control"
                                    value="{{ old('telefono_contacto', $usuario->telefono_contacto) }}" disabled>
                                <button type="button" class="btn btn-secondary" disabled>Validar</button>
                            </div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="form-group full">
                            <label>Correo Electrónico (no se puede cambiar)</label>
                            <input type="email" value="{{ $usuario->correo_electronico }}" disabled>
                        </div>
                    </div>

                    <button type="submit" id="guardar-info" class="btn btn-profile" style="display: none;">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </form>
            </div>

            <!-- Cambiar Contraseña -->
            <div class="profile-card">
                <div class="card-header">
                    <h3>Cambiar Contraseña</h3>
                    <button type="button" class="btn-edit" onclick="toggleEdit('pass')">
                        <i class="fas fa-pen"></i> Editar
                    </button>
                </div>

                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('perfil.actualizar-password') }}" method="POST" id="form-pass">
                    @csrf
                    <div class="info-row">
                        <div class="form-group">
                            <label>Contraseña Actual</label>
                            <input type="password" name="password_actual" id="password_actual" class="form-control" disabled>
                            @error('password_actual')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Nueva Contraseña</label>
                            <input type="password" name="nueva_password" id="nueva_password" class="form-control" disabled>
                            @error('nueva_password')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="form-group full">
                            <label>Confirmar Nueva Contraseña</label>
                            <input type="password" name="nueva_password_confirmation" id="confirmar_password" class="form-control" disabled>
                        </div>
                    </div>

                    <button type="submit" id="guardar-pass" class="btn btn-profile" style="display: none;">
                        <i class="fas fa-key"></i> Cambiar Contraseña
                    </button>
                </form>
            </div>
        </div>
        @else
        <div class="alert alert-danger mt-3">
            No se pudo encontrar la información del usuario.
        </div>
        @endif
    </div>

</body>
@endsection

<!--Referencia del script-->
<script src="{{asset('js/info_perfil.js')}}"></script>