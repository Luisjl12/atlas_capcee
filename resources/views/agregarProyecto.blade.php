@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Agregar Proyecto desde otra vista</h3>

    <form action="{{ route('proyectos.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Folio PPI:</label>
            <input type="text" name="folio_ppi" class="form-control" required>
        </div>

        <div class="form-group">
            <label>CCT:</label>
            <input type="text" name="cct" class="form-control">
        </div>

        <div class="form-group">
            <label>Nombre del Proyecto:</label>
            <input type="text" name="nombre_proyecto" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Monto de Inversión:</label>
            <input type="number" step="0.01" name="monto_inversion" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Inicio:</label>
            <input type="date" name="inicio" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Término:</label>
            <input type="date" name="termino" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Empresa:</label>
            <input type="text" name="empresa" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success mt-3">
            <i class="fas fa-save"></i> Guardar Proyecto
        </button>
    </form>
</div>
@endsection
