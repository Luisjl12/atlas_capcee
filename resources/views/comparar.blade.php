@extends('layouts.app')

@section('content')
<div style="display:flex; justify-content:center; padding: 2rem 1rem;">
  <div style="background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 2rem; width: 100%; max-width: 520px;">

    {{-- Header --}}
    <div style="display:flex; align-items:center; gap:12px; margin-bottom:1.5rem; padding-bottom:1.25rem; border-bottom:1px solid #f3f4f6;">
      <div style="width:40px; height:40px; background:#eff6ff; border-radius:8px; display:flex; align-items:center; justify-content:center;">
        <i class="fas fa-chart-bar" style="color:#3b82f6;"></i>
      </div>
      <div>
        <p style="font-size:17px; font-weight:600; margin:0;">Comparar infraestructura</p>
        <p style="font-size:13px; color:#6b7280; margin:0;">Verifica o registra datos de un inmueble</p>
      </div>
    </div>

    {{-- Formulario --}}
    <form method="POST" action="{{ route('infraestructura.comparar') }}">
      @csrf

      <div style="margin-bottom:1.25rem;">
        <label for="cct" style="display:block; font-size:13px; font-weight:500; color:#374151; margin-bottom:6px;">
          Clave de centro de trabajo (CCT)
        </label>
        <input type="text" name="cct" id="cct" placeholder="Ej. 21DPR0001A"
          style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; box-sizing:border-box;"
          value="{{ old('cct') }}" required>
      </div>

      <div style="margin-bottom:1.25rem;">
        <label for="nivel" style="display:block; font-size:13px; font-weight:500; color:#374151; margin-bottom:6px;">
          Nivel educativo
        </label>
        <select name="nivel" id="nivel"
          style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; box-sizing:border-box;"
          required>
          <option value="" disabled selected>Selecciona un nivel</option>
          <option value="inicial">Inicial</option>
          <option value="preescolar">Preescolar</option>
          <option value="primaria">Primaria</option>
          <option value="secundaria">Secundaria</option>
        </select>
      </div>

      <div style="margin-bottom:1.5rem;">
        <label for="imparte" style="display:block; font-size:13px; font-weight:500; color:#374151; margin-bottom:6px;">
          ¿Imparte clases actualmente?
        </label>
        <select name="imparte" id="imparte"
          style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; box-sizing:border-box;"
          required>
          <option value="" disabled selected>Selecciona una opción</option>
          <option value="1">Sí imparte</option>
          <option value="0">No imparte</option>
        </select>
      </div>

      <button type="submit"
        style="width:100%; padding:10px; background:#3b82f6; color:white; border:none; border-radius:8px; font-size:15px; font-weight:500; cursor:pointer;">
        Enviar y descargar CSV
      </button>
    </form>

  </div>
</div>
@endsection
