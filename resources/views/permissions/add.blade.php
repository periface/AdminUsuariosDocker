<form id="addPermission">
    @csrf
    <div class="row mt-2">
        <label for="name">Nombre:</label>
        <input type="text" name="name" class="form-control" placeholder="Nombre del permiso">
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