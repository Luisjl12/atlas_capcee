@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow border-warning">
        <div class="card-header bg-warning text-dark">
            <h4><i class="fas fa-edit"></i> Editando Reporte: {{ $reporte->titulo }}</h4>
        </div>
        <div class="card-body">

            <form action="{{ route('infraescolar.update', $reporte->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label>Título del Reporte:</label>
                    <input type="text" name="titulo" class="form-control" value="{{ $reporte->titulo }}" required>
                </div>

                <div class="mb-3">
                    <label>Archivo PDF (Solo si deseas cambiarlo, de lo contrario déjalo vacío):</label>
                    <input type="file" name="archivo_pdf" class="form-control" accept="application/pdf">
                    <small class="text-muted"><a href="{{ asset('storage/' . $reporte->archivo_pdf) }}" target="_blank">Ver PDF Actual</a></small>
                </div>

                <hr>
                <h5><i class="fas fa-file-excel"></i> Datos Financieros</h5>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label>Objetos del Gasto (Un gasto por línea):</label>
                        <textarea name="gastos" class="form-control" rows="8" required>{{ implode("\n", $grafica->labels ?? []) }}</textarea>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label>Aprobado ($):</label>
                        <textarea name="aprobado" class="form-control" rows="8" required>{{ implode("\n", $grafica->aprobado ?? []) }}</textarea>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Presupuesto Vigente ($):</label>
                        <textarea name="presupuesto_vigente" class="form-control" rows="8" required>{{ implode("\n", $grafica->vigente ?? []) }}</textarea>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Pagado ($):</label>
                        <textarea name="pagado" class="form-control" rows="8" required>{{ implode("\n", $grafica->pagado ?? []) }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('infraescolar.admin') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Cancelar y Regresar</a>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection