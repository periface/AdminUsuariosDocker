@if ($secretaria && $secretaria["id"])
    <input type="hidden" name="id" value="{{ $secretaria["id"] }}">
@endif
<div class="mt-2">
    <label for="nombre" class="form-label text-sm">Nombre: </label>
    <input class="form-control" id="nombre" name="nombre" placeholder="Nombre del secretaria"
        value="{{ $secretaria['nombre'] ?? '' }}">
</div>

<div class="mt-2">
    <label for="siglas" class="form-label text-sm">Siglas: </label>
    <input class="form-control" id="siglas" name="siglas" placeholder="Siglas del secretaria"
        value="{{ $secretaria['siglas'] ?? '' }}">
</div>
