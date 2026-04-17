@extends('layouts.app')

@section('content')
<div class="container mt-4">
    
    <div class="card shadow mb-4">
        <div class="card-header bg-dark text-white">
            <h4><i class="fas fa-upload"></i> Subir Nuevo Reporte Financiero Infraescolar</h4>
        </div>
        <div class="card-body">
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('infraescolar.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label>Título del Reporte:</label>
                    <input type="text" name="titulo" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Archivo PDF:</label>
                    <input type="file" name="archivo_pdf" class="form-control" accept="application/pdf" required>
                </div>

                <hr>
                <h5><i class="fas fa-file-excel"></i> Datos Financieros para la Gráfica</h5>
                
                <div class="alert alert-success py-2 mb-4 text-sm">
                    <strong><i class="fas fa-magic"></i> ¡Soporte para Excel!</strong><br>
                    Ya no uses comas para separar los datos. Ahora debes poner <strong>un valor por línea (Enter)</strong>. Puedes copiar toda una columna de tu archivo de Excel (con signos de pesos, comas y centavos) y pegarla directamente en cada cuadro. El sistema hará el resto.
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label>Objetos del Gasto (Un gasto por línea):</label>
                        <textarea name="gastos" class="form-control" rows="5" placeholder="Sueldo base al personal de confianza&#10;Primas de vacaciones y dominical&#10;Aguinaldo o gratificación" required></textarea>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label>Aprobado ($):</label>
                        <textarea name="aprobado" class="form-control" rows="5" placeholder="$10,572,804.00&#10;$364,311.00&#10;$1,350,969.00" required></textarea>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Presupuesto Vigente ($):</label>
                        <textarea name="presupuesto_vigente" class="form-control" rows="5" placeholder="$7,981,281.86&#10;$364,311.00&#10;$2,248,198.14" required></textarea>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Pagado ($):</label>
                        <textarea name="pagado" class="form-control" rows="5" placeholder="$2,591,522.14&#10;$0.00&#10;$0.00" required></textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-3"><i class="fas fa-save"></i> Guardar y Generar Gráfica Múltiple</button>
            </form>
            </div>
    </div>


    <div class="card shadow mb-5">
        <div class="card-header bg-secondary text-white">
            <h4><i class="fas fa-list"></i> Reportes Administrados</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Título del Reporte</th>
                            <th>Fecha de Subida</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportes as $rep)
                        <tr>
                            <td class="fw-bold text-start">{{ $rep->titulo }}</td>
                            <td>{{ $rep->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('infraescolar.edit', $rep->id) }}" class="btn btn-warning btn-sm shadow-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>

                                <form action="{{ route('infraescolar.destroy', $rep->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm shadow-sm" onclick="return confirm('¿Estás seguro de que deseas ELIMINAR este reporte? Esta acción no se puede deshacer.')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-muted">No hay reportes subidos todavía.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection