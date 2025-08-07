@extends('layouts.app')

@section('title', 'Listado de Planteles')



@section('content')


<div class="container mt-4">

    <div class="card-header-custom d-flex justify-content-between align-items-center mb-3">
        <a href="{{ session('role_id') == 1 ? route('dashboard.admin') : route('dashboard.director') }}" class="text-decoration-none d-inline-flex align-items-center text-dark">
            <h4 class="mb-4">
                <i class="fas fa-arrow-left "></i>
                <i class="fas fa-school"></i> Gestionar Planteles
            </h4>
        </a>
        <a href="{{ route('planteles.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Registrar Nuevo Plantel
        </a>
    </div>
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <strong>¡Éxito!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    @endif

    <div class="search-container mb-4">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="buscar" class="form-control" placeholder="    Buscar por CCT o Nombre...">
    </div>

    <div id="resultados">
        @include('partials.lista', ['planteles' => $planteles])
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.getElementById('buscar').addEventListener('keyup', function() {
        const buscar = this.value;

        fetch(`{{ route('planteles.buscar') }}?buscar=${encodeURIComponent(buscar)}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('resultados').innerHTML = data.html;
            })
            .catch(error => console.error('Error en la búsqueda:', error));
    });
</script>
@endpush