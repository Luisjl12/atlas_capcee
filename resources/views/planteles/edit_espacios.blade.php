@extends('layouts.app')

@section('title', 'Editar Espacios / Áreas')

@section('content')
<div class="container mt-4">
    <h3>Espacios / Áreas del Plantel: {{ $plantel->nombre }}</h3>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Nombre del Espacio</th>
                <th>Cantidad</th>
                <th>Estado de Conservación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($plantel->espacios as $espacio)
            <tr>
                <td>{{ $espacio->nombre_espacio }}</td>
                <td>{{ $espacio->cantidad }}</td>
                <td>{{ $espacio->estado_conservacion }}</td>
                <td>
                    <form action="{{ route('espacios.destroy', $espacio->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este espacio?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection