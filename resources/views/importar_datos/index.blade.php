@extends('layouts.app')

@section('title', 'Importar Datos')

@section('content')
<main class="main-container">
    <div class="container mt-4">
        <div class="card-header-custom">
            <a href="{{ route('dashboard.admin') }}" class="text-decoration-none d-inline-flex align-items-center text-dark">
                <h4 class="mb-4">
                    <i class="fas fa-arrow-left"></i>
                    <i class="fas fa-file-upload"></i> Importar Datos
                </h4>
            </a>
        </div>
        @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
        @endif

        @if(session('errores_csv'))
        <div class="alert alert-warning mt-3">
            <strong>Advertencias durante la importación:</strong>
            <ul>
                @foreach(session('errores_csv') as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger mt-3">
            <strong>Errores de validación:</strong>
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif


        <div class="card-body-custom p-4 mt-3">
            <p>Esta herramienta permite importar datos de planteles desde un archivo CSV. Asegúrese de que el archivo tenga los encabezados correctos:</p>

            <ul>
                <li><strong>CCT</strong> (obligatorio)</li>
                <li><strong>NOMBRE_ESCUELA</strong> (obligatorio)</li>
                <li><strong>ID_MUNICIPIO</strong></li>
                <li><strong>ID_CORDE</strong></li>
            </ul>
        </div>



        <form action="{{ route('importarDatos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="archivo">Seleccionar archivo CSV:</label>
                <input type="file" name="archivo" id="archivo" class="form-control-file" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">
                <i class="fas fa-upload"></i> Importar Datos
            </button>
        </form>
    </div>
</main>
@endsection