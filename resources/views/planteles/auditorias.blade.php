@extends('layouts.app')

@section('content')


<div class="container">
    <div class="card-header bg-white border-bottom mb-4">
        <a href="{{ route('planteles.index') }}" class="text-decoration-none d-inline-flex align-items-center text-dark">
            <h4 class="mb-0">
                <i class="fas fa-arrow-left"></i>
                <i class="fas fa-school me-2"></i> Historial de Cambios: {{ $plantel->nombre_escuela }}
                <small class="text-muted">(CCT: {{ $plantel->cct }})</small>
            </h4>
        </a>
    </div>

    <table class="table table-hover">
        <thead class="thead-custom">
            <tr>
                <th></th>
                <th>Usuario</th>
                <th>Evento</th>
                <th>Fecha</th>
                <th>Tags</th>
            </tr>
        </thead>
        <tbody>
            @forelse($plantel->auditorias as $index => $audit)
            <tr class="audit-toggle" data-index="{{ $index }}">
                <td class="toggle-icon text-center">
                    <i class="fas fa-chevron-down cursor-pointer"></i>
                </td>
                <td>{{ $audit->user->name ?? 'Sistema' }}</td>
                <td>
                    <span class="badge bg-{{ $audit->event === 'updated' ? 'warning' : ($audit->event === 'created' ? 'success' : 'danger') }}">
                        {{ ucfirst($audit->event) }}
                    </span>
                </td>
                <td>{{ $audit->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    @foreach(json_decode($audit->tags ?? '[]', true) as $tag)
                    <span class="badge bg-info">{{ $tag }}</span>
                    @endforeach
                </td>
            </tr>
            <tr class="audit-detail d-none" data-index="{{ $index }}">
                <td colspan="5">
                    <div class="p-2 bg-light rounded">
                        @php
                        $municipioAnterior = \App\Models\Municipio::find($audit->old_values['id_municipio'] ?? null)?->nombre_municipio;
                        $municipioNuevo = \App\Models\Municipio::find($audit->new_values['id_municipio'] ?? null)?->nombre_municipio;
                        @endphp

                        @if($municipioAnterior && $municipioNuevo && $municipioAnterior !== $municipioNuevo)
                        <p><strong>Municipio:</strong> {{ $municipioAnterior }} → {{ $municipioNuevo }}</p>
                        @endif

                        @if(isset($audit->municipio_cambio))
                        <p><strong>Municipio:</strong> {{ $audit->municipio_cambio['de'] }} → {{ $audit->municipio_cambio['a'] }}</p>
                        @endif
                        <!--Tag para localidades--->
                        @php
                        $localidadAnterior = \App\Models\Localidad::find($audit->old_values['id_localidad'] ?? null)?->nombre_localidad;
                        $localidadNueva = \App\Models\Localidad::find($audit->new_values['id_localidad'] ?? null)?->nombre_localidad;
                        @endphp

                        @if($localidadAnterior && $localidadNueva && $localidadAnterior !== $localidadNueva)
                        <p><strong>Localidad:</strong> {{ $localidadAnterior }} → {{ $localidadNueva }}</p>
                        @endif
                        <!--Tag de corde-->
                        @php
                        $cordeAnterior = \App\Models\Corde::find($audit->old_values['id_corde'] ?? null)?->nombre_corde;
                        $cordeNueva = \App\Models\Corde::find($audit->new_values['id_corde'] ?? null)?->nombre_corde;
                        @endphp

                        @if($cordeAnterior && $cordeNueva && $cordeAnterior !== $cordeNueva)
                        <p><strong>Localidad:</strong> {{ $cordeAnterior }} → {{ $cordeNueva }}</p>
                        @endif

                        <strong>Antes:</strong>
                        <ul class="mb-2">
                            @foreach($audit->old_values ?? [] as $key => $value)
                            <li><strong>{{ $key }}:</strong> {{ $value }}</li>
                            @endforeach
                        </ul>

                        <strong>Después:</strong>
                        <ul>
                            @foreach($audit->new_values ?? [] as $key => $value)
                            <li><strong>{{ $key }}:</strong> {{ $value }}</li>
                            @endforeach
                        </ul>
                    </div>

                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">No hay historial de cambios registrados para este plantel.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.audit-toggle').forEach(row => {
            row.addEventListener('click', function() {
                const index = row.getAttribute('data-index');
                const detailRow = document.querySelector(`.audit-detail[data-index="${index}"]`);
                const icon = row.querySelector('.toggle-icon i');

                detailRow.classList.toggle('d-none');

                if (detailRow.classList.contains('d-none')) {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                } else {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                }
            });
        });
    });
</script>
@endpush