@extends('layouts.app')

@section('content')
<div class="container mt-4">

  <h2 class="mb-3">Reportes de Comparación</h2>

  <div class="accordion" id="accordionComparaciones">

    <!-- Sección 1 -->
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingOne">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
          Niveles
        </button>
      </h2>
      <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" 
           data-bs-parent="#accordionComparaciones">
        <div class="accordion-body">
            <div class="d-flex justify-content-end mb-2">
                <a href="{{ route('reportes.niveles.exportar') }}" 
                class="btn btn-sm btn-primary">
                <i class="fas fa-file-excel"></i> Generar Reporte CSV
                </a>
            </div>

            <table class="table table-striped table-hover">
                <thead class="table">
                <tr>
                    <th>CCT</th>
                    <th>Nivel ingresado (director/docente)</th>
                    <th>Nivel registrado (CAPCEE)</th>
                    <th>Estado</th>
                </tr>
                </thead>
                <tbody>
                @forelse($registros as $row)
                    @php
                    $estado = $row->nivel_comparada === $row->nivel_inmueble ? 'Coinciden' : 'Diferentes';
                    $badgeClass = $estado === 'Coinciden' ? 'bg-success' : 'bg-danger';
                    @endphp
                    <tr>
                    <td>{{ $row->cct }}</td>
                    <td>{{ $row->nivel_comparada }}</td>
                    <td>{{ $row->nivel_inmueble }}</td>
                    <td><span class="badge {{ $badgeClass }}">{{ $estado }}</span></td>
                    </tr>
                @empty
                    <tr>
                    <td colspan="4" class="text-center">No hay registros de comparación.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            </div>

      </div>
    </div>

    <!-- Sección 2 -->
    <div class="accordion-item">
    <h2 class="accordion-header" id="headingThree">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
        Número de Edificios
        </button>
    </h2>
    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" 
        data-bs-parent="#accordionComparaciones">
        <div class="accordion-body">
            <div class="d-flex justify-content-end mb-2">
                <a href="{{ route('reportes.edificios.exportar') }}" 
                class="btn btn-sm btn-success">
                <i class="fas fa-file-excel"></i> Generar Reporte CSV
                </a>
            </div>

            <table class="table table-striped table-hover">
                <thead class="table">
                <tr>
                    <th>CCT</th>
                    <th>Edificios ingresados por el maestro/director</th>
                    <th>Edificios registrados por el CAPCEE</th>
                    <th>Estado</th>
                </tr>
                </thead>
                <tbody>
                @forelse($registrosEdificios as $row)
                    @php
                    $estado = $row->edificios_comparada == $row->edificios_registrados ? 'Coinciden' : 'Diferentes';
                    $badgeClass = $estado === 'Coinciden' ? 'bg-success' : 'bg-danger';
                    @endphp
                    <tr>
                    <td>{{ $row->cct }}</td>
                    <td>{{ $row->edificios_comparada }}</td>
                    <td>{{ $row->edificios_registrados }}</td>
                    <td><span class="badge {{ $badgeClass }}">{{ $estado }}</span></td>
                    </tr>
                @empty
                    <tr>
                    <td colspan="4" class="text-center">No hay registros de comparación.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>


  </div>
</div>
@endsection
