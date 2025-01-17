<form id="editRol">
    @csrf
    <div class="row mt-2">
        <input type="hidden" value="{{ $role->id }}" name="id" id="roleId">

        <label for="name">Nombre:</label>
        <input type="text" name="name" id="name" class="form-control"
        placeholder="Rol" value="{{  $role->name ?? ""  }}">

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-inst3 btn-sm" data-bs-dismiss="modal">
            <small>CANCELAR</small>
        </button>
        <button type="submit" class="btn btn-inst btn-sm">
            <small>GUARDAR</small>
        </button>
    </div>
</form>
