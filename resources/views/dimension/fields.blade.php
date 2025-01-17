            @if($dimension && $dimension->id)
                <input type="hidden" name="id" value="{{ $dimension->id }}">
            @endif
            <div class="mt-2">
                <label for="nombre" class="form-label text-sm">Nombre: </label>
                <input class="form-control" id="nombre" name="nombre" placeholder="Nombre del dimensión" value="{{ $dimension["nombre"] ?? "" }}">
            </div>

            <div class="mt-2">
                <label for="descripcion" class="form-label text-sm">Descripción: </label>
                <input class="form-control" id="descripcion" name="descripcion" placeholder="Descripción" value="{{ $dimension["descripcion"] ??"" }}">
            </div>

            <div class="mt-2">
                <label for="status" class="form-label text-sm">Status: </label>
                <select class="form-select" id="status" name="status">
                    @if($dimension && $dimension->id)
                    <option value="1" {{ $dimension["status"] == 1 ? "selected" : "" }}>Activo</option>
                    <option value="0" {{ $dimension["status"] == 0 ? "selected" : "" }}>Inactivo</option>
                    @else
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                    @endif
                </select>
            </div>
