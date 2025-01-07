<form id="addArea">
    @csrf
    <div class="mt-2">
        <label for="nombre" class="form-label text-sm">Nombre: </label>
        <input class="form-control" id="nombre" name="nombre" placeholder="Nombre del área" value="{{ $area['nombre'] ?? "" }}">
    </div>
    <div class="mt-2">
        <label for="status" class="form-label text-sm">Responsable: </label>
        <select class="form-select" id="responsable" name="responsable">
           <option value="0">Seleccione</option>
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
