@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Editar Proyecto</h2>

    <form action="{{ route('proyectos.update', $proyecto->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="folio_ppi" class="form-label">Folio PPI</label>
            <input type="text" class="form-control" id="folio_ppi" name="folio_ppi" value="{{ $proyecto->folio_ppi }}" readonly>
        </div>

        <div class="mb-3">
            <label for="municipio" class="form-label">Municipio</label>
            <input type="text" class="form-control" id="municipio" name="municipio" value="{{ $proyecto->municipio }}">
        </div>

        <div class="mb-3">
            <label for="nombre_proyecto" class="form-label">Nombre Proyecto</label>
            <textarea class="form-control" id="nombre_proyecto" name="nombre_proyecto">{{ $proyecto->nombre_proyecto }}</textarea>
        </div>

        <div class="mb-3">
            <label for="monto_inversion" class="form-label">Monto Inversión</label>
            <input type="number" step="0.01" class="form-control" id="monto_inversion" name="monto_inversion" value="{{ $proyecto->monto_inversion }}">
        </div>

        <div class="mb-3">
            <label for="inicio" class="form-label">Inicio</label>
            <input type="date" class="form-control" id="inicio" name="inicio" value="{{ $proyecto->inicio }}">
        </div>

        <div class="mb-3">
            <label for="termino" class="form-label">Término</label>
            <input type="date" class="form-control" id="termino" name="termino" value="{{ $proyecto->termino }}">
        </div>

        <div class="mb-3">
            <label for="empresa" class="form-label">Empresa</label>
            <input type="text" class="form-control" id="empresa" name="empresa" value="{{ $proyecto->empresa }}">
        </div>

        <button type="submit" class="btn btn-success">Guardar cambios</button>
        <a href="{{ route('proyectos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection