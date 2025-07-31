@extends('layouts.app')
@section('title', 'Crear nuevo usuario')


@section('content')
<div class="card-header bg-white border-bottom">
    <a href="{{ route('usuarios.index') }}" class="text-decoration-none d-inline-flex align-items-center text-dark">
        <h4 class="mb-4">
            <i class="fas fa-arrow-left "></i>
            <i class="fas fa-user-plus"></i> Agregar Nuevo Usuario
        </h4>
    </a>
</div>
<div class="container mt-4">


    {{-- Validación de errores --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Mensaje de éxito --}}
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    {{-- Card de formulario --}}
    <div class="form-ficha-base">
        <div class="card-header bg-white border-bottom">
            <h5 class=" mb-0" style="color: var(--color-vino-secundario);">Datos del Usuario</h5>
        </div>
        <div class=" card-body">
            <form method="POST" action="{{ route('usuarios.store') }}" class="form-ficha-base">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre Completo *</label>
                        <input type="text" name="nombre_completo" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Correo Electrónico *</label>
                        <input type="email" name="correo_electronico" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Contraseña *</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono de contacto *</label>
                        <input type="text" name="telefono_contacto" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Rol *</label>
                        <select name="role_id" class="form-select" required>
                            <option value="">Seleccione un Rol</option>
                            @foreach($roles as $rol)
                            <option value="{{ $rol->id }}">{{ $rol->nombre_rol }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Estatus *</label>
                        <select name="estado" class="form-select" required>
                            <option value="">Seleccione estatus</option>
                            <option value="ACTIVO">ACTIVO</option>
                            <option value="INACTIVO">INACTIVO</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-start gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Crear
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection