@extends('layouts.app')

@section('content')

<div class="card-header">
    <a href="{{ route('proyectos.index') }}" class="btn-icon-only">
        <i class="fas fa-arrow-left me-2"></i>
        <i class="fas fa-school me-2"></i>
        <h3 class="mb-0">Folio PPI: {{ $proyecto->folio_ppi }}</h3>
    </a>
</div>

{{-- Navegación de pestañas --}}
<div class="form-navigation nav-tabs-text mb-3">
    <span class="nav-tab active" data-step="0" data-bs-toggle="tab" data-bs-target="#tab-0" role="tab">I. Ubicación</span>
    <span class="nav-tab" data-step="1" data-bs-toggle="tab" data-bs-target="#tab-1" role="tab">II. Avances</span>
    <span class="nav-tab" data-step="2" data-bs-toggle="tab" data-bs-target="#tab-2" role="tab">III. Contrato</span>
    <span class="nav-tab" data-step="3" data-bs-toggle="tab" data-bs-target="#tab-3" role="tab">IV. Estatus</span>
    <span class="nav-tab" data-step="4" data-bs-toggle="tab" data-bs-target="#tab-4" role="tab">V. Notificacion</span>
</div>


{{-- Contenido de pestañas --}}
<div class="tab-content">

    <!-- Ubicación -->
    <div class="tab-pane fade show active" id="tab-0">
        <h4 class="bg-white text-dark p-2 rounded shadow-sm">Ubicación</h4>
        <div id="map" style="height: 400px; width: 100%;"></div>
    </div>

    <!-- Avances -->
    <div class="tab-pane fade mb-4" id="tab-1">  
        <h4 class="bg-white text-dark p-2 rounded shadow-sm">Avances del Proyecto</h4>

        @if($proyecto)
            <div class="card mb-4">
                <div class="card-body">
                    <p><strong>Avance Financiero Programado:</strong> {{ $proyecto->av_fin_prog }}%</p>
                    <p><strong>Avance Financiero Real:</strong> {{ $proyecto->av_fin_real }}%</p>
                    <p><strong>Avance Físico Programado:</strong> {{ $proyecto->av_fis_prog }}%</p>
                    <p><strong>Avance Físico Real:</strong> {{ $proyecto->av_fis_real }}%</p>
                </div>
            </div>
        @else
            <p>No hay información de avances disponible para este proyecto.</p>
        @endif
    </div>


    <div class="tab-pane fade mb-4" id="tab-2">
        <h4 class="bg-white text-dark p-2 rounded shadow-sm">Contrato</h4>
        @if($proyecto)
            <div class="card mb-4">
                <div class="card-body">
                    <p><strong>Empresa:</strong> {{$proyecto->empresa}}</p>
                    <p><strong>Número de Contrato:</strong> {{$proyecto->no_contrato}}</p>
                    <p><strong>Monto Contrato:</strong> {{ number_format($proyecto->monto_contratado, 2) }}</p>
                    <p><strong>Plazo de Ejecución en Días:</strong> {{$proyecto->plazo_ejec_dias}}</p>
                </div>
            </div>
        @else 
            <p>No hay información de contratos para este proyecto</p>
        @endif
    </div>

    <div class="tab-pane fade mb-4" id="tab-3">
        <h4 class="bg-white text-dark p-2 rounded shadow-sm">Estatus</h4>
        @if($proyecto)
            <div class="card mb-4">
                <div class="card-body">
                    <p><strong>Estatus General:</strong> {{ $proyecto->estatus_general }}</p>
                    <p><strong>Estatus Finanzas:</strong> {{ $proyecto->estatus_finanzas }}</p>
                    <p><strong>Estatus del Administrador:</strong> {{ $proyecto->estatus_admin }}</p>
                </div>
            </div>
        @else
            <p>No hay Estatus por mostrar</p>
        @endif
    </div>


    <div class="tab-pane fade mb-4" id="tab-4">
        <h4 class="bg-white text-dark p-2 rounded shadow-sm">Notificación</h4>
        @if($proyecto)
            <div class="card mb-4">
                <div class="card-body">
                    <p><strong>Usuario Notificado:</strong> {{ $proyecto->usuario_notif }}</p>
                    <p><strong>Fecha Notificación:</strong> {{ $proyecto->fecha_notif }}</p>
                </div>
            </div>
        @else
            <p>No hay Notificaciones por mostrar</p>
        @endif
    </div>

</div>

{{-- Leaflet --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const tabTriggers = document.querySelectorAll('[data-bs-toggle="tab"]');

    tabTriggers.forEach(function (trigger) {
        trigger.addEventListener('click', function () {
            const target = document.querySelector(this.getAttribute('data-bs-target'));

            document.querySelectorAll('.tab-pane').forEach(function (pane) {
                pane.classList.remove('show', 'active');
            });

            tabTriggers.forEach(function (t) {
                t.classList.remove('active');
            });

            target.classList.add('show', 'active');
            trigger.classList.add('active');

            if (trigger.getAttribute('data-bs-target') === '#tab-0') {
                setTimeout(() => map.invalidateSize(), 10);
            }
        });
    });

    var lat = {{ $proyecto->latitud }};
    var lng = {{ $proyecto->longitud }};

    var map = L.map('map').setView([lat, lng], 15);

    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles © Esri & Sources'
    }).addTo(map);


    L.marker([lat, lng]).addTo(map)
        .bindPopup("<b>{{ $proyecto->nombre_proyecto }}</b><br>{{ $proyecto->municipio }}")
        .openPopup();
});
</script>
@endsection