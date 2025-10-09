@extends('layouts.app')

@section('title', 'Importar Datos')

@section('content')
<main class="main-container">
    <div class="container mt-4">
        @php
        use App\Helpers\RoleHelper;
        @endphp
        <div class="card-header-custom">
            <a href="{{ RoleHelper::importarDatos(session('role_id')) }}" class="text-decoration-none d-inline-flex align-items-center text-dark">
                <h4 class="mb-4">
                    <i class="fas fa-arrow-left"></i>
                    <i class="fas fa-file-upload"></i> Importar Datos
                </h4>
            </a>

            <a href="{{ asset('plantillas/plantilla_datos.csv') }}" download class="btn btn-success">
                <i class="fas fa-file-excel"></i> Descargar Plantilla CSV
            </a>
        </div>

        @if (session('mensaje'))
        <div class="alert alert-success">
            {{ session('mensaje') }}
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
        @if (session('errores'))
        <div class="alert alert-warning">
            <strong>Errores durante la importación:</strong>
            <ul>
                @foreach (session('errores') as $error)
                <li>{{ $error['cct'] }}: {{ $error['error'] }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="card-body-custom p-4 mt-3">
            <p>Esta herramienta permite importar datos de planteles desde un archivo CSV. Asegúrese de que el archivo tenga los encabezados correctos:</p>

            <ul>
                <li><strong>CV_CCT</strong> (CCT-obligatorio)</li>
                <li><strong>NOMBRECT</strong> (Nombre del plantel-obligatorio)</li>
                <li><strong>C_NOM_MUN</strong> (Nombre del municipio-obligatorio)</li>
                <li><strong>NOMBRE_CORDE</strong> (Nombre del corde-obligatorio)</li>

            </ul>

        </div>


        <form action="{{ route('importarDatos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="archivo">Seleccionar archivo CSV, EXCEL o TXT:</label>
                <input type="file" name="archivo" id="archivo" class="form-control-file" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">
                <i class="fas fa-upload"></i> Importar Datos
            </button>
        </form>
    </div>
</main>
@endsection