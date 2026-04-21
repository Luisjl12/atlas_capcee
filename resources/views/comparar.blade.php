@extends('layouts.app')

@section('content')
<div class="container mt-4">

  {{-- Formulario para Niveles --}}
  <div class="card mb-4">
    <div class="card-header bg-primary text-white">
      Comparar Niveles Educativos
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('infraestructura.comparar') }}">
        @csrf
        <div class="mb-3">
          <label for="cct" class="form-label">CCT</label>
          <input type="text" name="cct" id="cct" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="nivel" class="form-label">Nivel educativo</label>
          <select name="nivel" id="nivel" class="form-select" required>
            <option value="" disabled selected>Selecciona un nivel</option>
            <option value="inicial">Inicial</option>
            <option value="preescolar">Preescolar</option>
            <option value="primaria">Primaria</option>
            <option value="secundaria">Secundaria</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="imparte" class="form-label">¿Imparte clases actualmente?</label>
          <select name="imparte" id="imparte" class="form-select" required>
            <option value="" disabled selected>Selecciona una opción</option>
            <option value="1">Sí imparte</option>
            <option value="0">No imparte</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Nivel</button>
      </form>
    </div>
  </div>

  {{-- Formulario para Número de Edificios --}}
  <div class="card">
    <div class="card-header bg-success text-white">
      Comparar Número de Edificios
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('comparacion.edificios.store') }}">
        @csrf
        <div class="mb-3">
          <label for="cct" class="form-label">CCT</label>
          <input type="text" name="cct" id="cct" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="numero_edificios" class="form-label">Número de edificios</label>
          <input type="number" name="numero_edificios" id="numero_edificios" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="fuente" class="form-label">Fuente (opcional)</label>
          <input type="text" name="fuente" id="fuente" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Guardar Edificios</button>
      </form>
    </div>
  </div>

</div>
@endsection