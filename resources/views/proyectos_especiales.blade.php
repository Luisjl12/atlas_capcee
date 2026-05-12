@extends('layouts.app')

@section('title', 'Nuevo Ticket')

@section('content')
<div class="card-header-custom">
    <a href="" class="btn-icon-only">
        <i class="fas fa-arrow-left"></i>
        <h2><i class="fas fa-ticket-alt"></i> Seguimiento de Proyectos Especiales</h2>
    </a>
</div>

@if(session('success'))
<div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger mt-3">{{ session('error') }}</div>
@endif

<div class="d-flex justify-content-end mb-3">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTicket">
        <i class="fas fa-plus"></i> Nuevo Proyecto
    </button>
</div>



<div class="row profile-container mt-3">
<!-- Modal -->
<div class="modal fade" id="modalTicket" tabindex="-1" aria-labelledby="modalTicketLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTicketLabel">Crear Nuevo Proyecto para su Seguimiento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tickets.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="folio" class="form-label">Folio</label>
                        <input type="text" name="folio" id="folio" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="numero_oficio" class="form-label">Número de Oficio</label>
                        <input type="text" name="numero_oficio" id="numero_oficio" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="areas_turnadas" class="form-label">Áreas Turnadas</label>
                        <input type="text" name="areas_turnadas" id="areas_turnadas" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="quien_atiende" class="form-label">Quién Atiende</label>
                        <input type="text" name="quien_atiende" id="quien_atiende" class="form-control">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="anexo" name="anexo" value="1">
                        <label class="form-check-label" for="anexo">Anexo</label>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_oficialia" class="form-label">Fecha Oficialía</label>
                        <input type="date" name="fecha_oficialia" id="fecha_oficialia" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="fecha_dfe" class="form-label">Fecha DFE</label>
                        <input type="date" name="fecha_dfe" id="fecha_dfe" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="estatus" class="form-label">Estatus</label>
                        <select name="estatus" id="estatus" class="form-select">
                            <option value="En revisión" selected>En revisión</option>
                            <option value="Aprobado">Aprobado</option>
                            <option value="No aprobado">No aprobado</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Ticket
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div>
    <div class="mt-4">
        <h5 style="color:var(--color-vino-terciario);">Listado de Proyectos</h5>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Folio</th>
                    <th>Número de Oficio</th>
                    <th>Áreas Turnadas</th>
                    <th>Quién Atiende</th>
                    <th>Anexo</th>
                    <th>Fecha Oficialía</th>
                    <th>Fecha DFE</th>
                    <th>Seguimiento</th>
                    <th>Estatus</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->folio }}</td>
                        <td>{{ $ticket->numero_oficio }}</td>
                        <td>{{ $ticket->areas_turnadas }}</td>
                        <td>{{ $ticket->quien_atiende }}</td>
                        <td>{{ $ticket->anexo ? 'Sí' : 'No' }}</td>
                        <td>{{ $ticket->fecha_oficialia }}</td>
                        <td>{{ $ticket->fecha_dfe }}</td>
                        <td>
                            <a href="{{ route('tickets.dictamen', $ticket->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-file-alt"></i> Dictaminar
                            </a>
                        </td>
                        <td>
                            @if($ticket->estatus == 'Aprobado')
                                <span class="badge bg-success">Aprobado</span>
                            @elseif($ticket->estatus == 'En revisión')
                                <span class="badge bg-warning text-dark">En revisión</span>
                            @else
                                <span class="badge bg-danger">No aprobado</span>
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No hay tickets registrados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection


