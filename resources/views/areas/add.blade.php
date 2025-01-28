<form id="addArea">
    @csrf

    <div class="mt-2">
        <label for="secretariaId" class="form-label text-sm">Secretaria: </label>
        <select class="form-select" id="secretariaId" name="secretariaId">
           <option value="0">Seleccione</option>
           @foreach ($secretarias as $secretaria)
            <option value="{{ $secretaria["id"] ?? '' }}"
                @selected((old('secretariaId', $area['secretariaId'] ?? '') == $secretaria["id"]))>
                    {{ $secretaria["nombre"] }}
            </option>
           @endforeach
        </select>
    </div>
    <div class="mt-2">
        <div class="row">
            <div class="col-5">
                <label for="tipo" class="form-label text-sm">Tipo de área: </label>
                <select name="tipo" id="tipo" class="form-select">
                    <option value="0">Seleccione</option>
                    <option value="1">Dirección General</option>
                    <option value="2">Dirección</option>
                </select>
            </div>
            <div class="col-7">
                <label for="nombre" class="form-label text-sm">Nombre: </label>
                <input class="form-control" id="nombre" name="nombre" placeholder="Nombre" value="{{ $area['nombre'] ?? "" }}">
            </div>
        </div>
    </div>
    <div class="mt-2">
        <label for="departamento" class="form-label text-sm">Departamento: </label>
        <input class="form-control" id="departamento" name="departamento" placeholder="Dirección / Departamento" value="{{ $area['nombre'] ?? "" }}">
    </div>
    <div class="mt-2">
        <label for="status" class="form-label text-sm">Responsable: </label>
        <select class="form-select" id="responsableId" name="responsableId">
           <option value="0">Seleccione</option>
           @foreach ($users as $user)
            <option value="{{ $user->id ?? '' }}"
                @selected((old('responsableId', $area['responsableId'] ?? '') == $user->id))>
                    {{ $user->nombre }} {{ $user->apPaterno }} {{ $user->apMaterno }}
            </option>
           @endforeach
        </select>
    </div>
    <div class="mt-2">
        <label for="siglas" class="form-label text-sm">Siglas: </label>
        <input type="text" class="form-control" id="siglas" name="siglas" placeholder="Siglas" value = "{{ $area['siglas'] ?? "" }}">
    </div>
    <div class=" modal-footer">
        <button type="button" class="btn btn-inst3 btn-sm" data-bs-dismiss="modal">
            <small>CANCELAR</small>
        </button>
        <button type="submit" class="btn btn-inst btn-sm">
            <small>GUARDAR</small>
        </button>
    </div>
</form>
