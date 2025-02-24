@if ($categoria && $categoria->id)
    <input type="hidden" name="id" value="{{ $categoria->id }}">
@endif
<div class="mt-2">
    <label for="nombre" class="form-label text-sm">Nombre: </label>
    <input class="form-control" id="nombre" name="nombre" placeholder="Nombre de la categoria"
        value="{{ $categoria['nombre'] ?? '' }}">
</div>

<div class="mt-2">
    <label for="descripcion" class="form-label text-sm">Descripción: </label>
    <input class="form-control" id="descripcion" name="descripcion" placeholder="Descripción"
        value="{{ $categoria['descripcion'] ?? '' }}">
</div>

<div class="mt-2">
    <label for="status" class="form-label text-sm">Status: </label>
    <select class="form-select" id="status" name="status">
        @if ($categoria && $categoria->id)
            <option value="1" {{ $categoria['status'] == 1 ? 'selected' : '' }}>Activo</option>
            <option value="0" {{ $categoria['status'] == 0 ? 'selected' : '' }}>Inactivo</option>
        @else
            <option value="1">Activo</option>
            <option value="0">Inactivo</option>
        @endif
    </select>
</div>
