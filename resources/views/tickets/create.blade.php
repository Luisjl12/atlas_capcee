@extends('layouts.app')
@section('content')
    <form action="{{ route('tickets.store') }}" method="POST">
        @csrf
        <label>Folio</label>
        <input type="text" name="folio" required>

        <label>Número de Oficio</label>
        <input type="text" name="numero_oficio">

        <label>Áreas Turnadas</label>
        <input type="text" name="areas_turnadas">

        <label>Quién Atiende</label>
        <input type="text" name="quien_atiende">

        <label>Anexo</label>
        <input type="checkbox" name="anexo" value="1">

        <label>Fecha Oficialía</label>
        <input type="date" name="fecha_oficialia">

        <label>Fecha DFE</label>
        <input type="date" name="fecha_dfe">

        <button type="submit">Crear Ticket</button>
    </form>
@endsection
