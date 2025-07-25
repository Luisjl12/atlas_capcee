<h4>Subir foto al plantel (CCT: {{$plantel->cct}})</h4>

@if (session('foto_subida'))
<div class="alert alert-success">
    Foto subida correctamente
    <img src="{{ session('foto_subida') }}" alt="Foto subida" class="img-thumbnail mt-2" width="200">
</div>
@endif
<form action="{{route ('galeria.store', $plantel->cct)}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="foto" class="form-label">Seleccionar foto</label>
        <input type="file" name="foto" accept="image/png, image/jpeg, image/jpg, image/webp" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="descripcion" class=form-label>Descripción</label>
        <input type="text" name="descripcion" id="descripcion" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Subir foto</button>
</form>