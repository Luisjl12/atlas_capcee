@extends('layouts.app')
@section('tittle', 'Listado de planteles')

@section ('content')
<div class="container mt-4">
    <h2 class="mb-4">Planteles Registrados</h2>
    <a href="{{route('planteles.create')}}" class="btn btn-success mb-3">Agregar Planteles</a>

    @if ($planteles->isEmpty())
    <div class="alert alert-info">
        No hay planteles registrados aun.
    </div>
    @else
    <table class="table table-bordered">
        <thread>
            <tr>
                <th>CCT</th>
                <th>Nombre Escuela</th>
                <th>Municipio</th>
                <th>Director Asignado</th>
                <th>Estatus</th>
                <th>Acciones</th>
            </tr>
        </thread>
        <tbody>
            @foreach($planteles as $plantel)

            <tr>
                <td>{{$plantel->cct}}</td>
                <td>{{$plantel->nombre_escuela}}</td>
                <td>{{$plantel->municipio->nombre_municipio ?? 'Sin municipio'}}</td>
                <td>{{$plantel->nombre_director_registrado}}</td>
                <td>{{$plantel->estatus_plantel}}</td>
                <td>
                    <a href="{{ route('planteles.show', $plantel->id)}}" class="btn btn-sm btn-primary">Ver</a>
                    <a href="{{ route('planteles.edit', $plantel->id) }}" class="btn btn-sm btn-primary">Editar</a>
                    <form action="{{ route('planteles.destroy', $plantel->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este plantel?')">Eliminar</button>
                    </form>
                </td>


            </tr>
            @endforeach

        </tbody>
    </table>
    @endif
</div>
@endsection