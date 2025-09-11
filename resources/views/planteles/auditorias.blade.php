@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card-header bg-white border-bottom mb-4">

        <a href="{{ route('planteles.index') }}" class="text-decoration-none d-inline-flex align-items-center text-dark">
            <h4 class="mb-0">
                <i class="fas fa-arrow-left "></i>
                <i class="fas fa-school me-2"></i> Historial de Cambios: {{ $plantel->nombre_escuela }}
                <small class="text-muted">(CCT: {{ $plantel->cct }})</small>
            </h4>
        </a>

    </div>

    <table class="table data-table">
        <thead class="thead-custom">
            <tr>
                <th>Usuario</th>
                <th>Evento</th>
                <th>Fecha</th>
                <th>Tags</th>
                <th>Cambios</th>
            </tr>
        </thead>
        <tbody>
            @forelse($plantel->auditorias as $audit)
            <tr>
                <td>{{ $audit->user->name ?? 'Sistema' }}</td>
                <td><span class="badge bg-{{ $audit->event === 'updated' ? 'warning' : ($audit->event === 'created' ? 'success' : 'danger') }}">
                        {{ ucfirst($audit->event) }}
                    </span></td>
                <td>{{ $audit->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    @foreach(json_decode($audit->tags ?? '[]', true) as $tag)
                    <span class="badge bg-info">{{ $tag }}</span>
                    @endforeach

                </td>
                <td>
                    <strong>Antes:</strong>
                    <ul>
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