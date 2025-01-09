<form id="addArea">
    @csrf
    <div class="mt-2">
        <label for="status" class="form-label text-sm">Dirección Gral / Dirección: </label>
        <select class="form-select" id="responsable" name="responsable">
           <option value="0">Seleccione</option>
           <option value="1">Dirección Gral de Compras y Operaciones</option>
           <option value="2">Dirección de Contratos</option>
           <option value="3">Dirección de Patrimonio Estatal</option>
           <option value="3">Contraloría Gubernamental</option>
           <option value="3">Dirección de Planeación y Control Hacendario</option>
        </select>
    </div>
    <div class="mt-2">
        <label for="nombre" class="form-label text-sm">Dirección / Departamento: </label>
        <input class="form-control" id="nombre" name="nombre" placeholder="Dirección / Departamento" value="{{ $area['nombre'] ?? "" }}">
    </div>
    <div class="mt-2">
        <label for="status" class="form-label text-sm">Responsable: </label>
        <select class="form-select" id="responsable" name="responsable">
           <option value="0">Seleccione</option>
           @foreach ($users as $user)
            <option value="{{ $user->id ?? '' }}"
                @selected((old('responsable', $area['responsable'] ?? '') == $user->id))>
                    {{ $user->name }}
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
