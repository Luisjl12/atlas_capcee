@extends('layouts.app')

@section('title', 'Importar Datos')

@section('content')

        @php
        use App\Helpers\RoleHelper;
        @endphp
        <div class="card-header-custom">
            <a href="{{ RoleHelper::importarDatos(session('role_id')) }}" class="btn-icon-only">
                <i class="fas fa-arrow-left"></i>
                <h2><i class="fas fa-file-upload"></i> Importar Datos de Planteles</h2>
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
            <p>Sube un archivo CSV para cargar información de escuelas de forma masiva.</p>
            <div class="mb-3">
                <strong>Asegurese de que el archivo CSV incluya los encabezados con los mismos nombres que los
                        campos requeridos:</strong><br>
                <span class="chip"><i class="fas fa-check"></i><strong>**CV_CCT (CCT)</strong></span>
                <span class="chip"><i class="fas fa-school"></i><strong>**NOMBRECT (Nombre del plantel)</strong></span>
                <span class="chip"><i class="fas fa-map-marker-alt"></i>C_NOM_MUN (Nombre del municipio)</span>
                <span class="chip"><i class="fas fa-building"></i>NOMBRE_CORDE (Nombre del corde)</span>
            </div>

            <form action="{{ route('importarDatos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

                <label for="archivo" class="dropzone">
                    <i class="fas fa-file-csv fa-2x"></i>
                    <p>Haz clic aqui para subir tu archivo CSV</p>
                    <input type="file" name="archivo" id="archivo" class="input-csv" required>
                </label>
                <button type="submit" class="btn-custom btn-primary mt-3">
                    <i class="fas fa-upload"></i> Importar Datos
                </button>
            </form>
        </div>
@endsection