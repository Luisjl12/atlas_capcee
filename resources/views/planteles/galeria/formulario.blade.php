<h4>Galería Fotográfica</h4>
<div id="galeria-alert" class="alert alert-success d-none"></div>


@if (session('foto_subida'))
<div class="alert alert-success">
    Foto subida correctamente
    <img src="{{ session('foto_subida') }}" alt="Foto subida" class="img-thumbnail mt-2" width="200">
</div>
@endif
<form action="{{route ('galeria.store', $plantel->cct)}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="foto" class="form-label">Imagenes (puede elegir varias)</label>
        <input type="file" name="foto" accept="image/png, image/jpeg, image/jpg, image/webp" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="descripcion" class=form-label>Descripción para estas fotos</label>
        <input type="text" name="descripcion" id="descripcion" class="form-control">
    </div>
    <button type="submit" class="btn btn-success"><i class="fas fa-camera"> Subir</i></button>


</form>
<button type="button" id="btnEliminarSeleccionadas" class="btn btn-danger"
    data-url="{{ route('galeria.eliminarSeleccionadas') }}">
    <i class="fas fa-trash"></i>
</button>




<script src="{{ asset('js/galeria.js') }}"></script>