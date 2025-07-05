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
                <th>Nombre</th>
                <th>Nivel Educativo</th>
                <th>Turnos</th>
                <th>Sostenimiento</th>
            </tr>
        </thread>
        <tbody>
            @foreach($planteles as $plantel)
            <tr>
                <td>{{$plantel->cct}}</td>
                <td>{{$plantel->nombre_escuela}}</td>
                <td>{{$plantel->nivel_educativo}}</td>
                <td>{{$plantel->turno}}</td>
                <td>{{$plantel->sostenimiento}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection