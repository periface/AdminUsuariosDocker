<form id="editRol">
    @csrf
    <div class="row mt-2">
        <input type="hidden" value="{{ $role->id }}" name="id" id="role_id">

        <label for="name">Nombre:</label>
        <input type="text" name="name" id="name" class="form-control" 
        placeholder="Rol" value="{{  $role->name ?? ""  }}">

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success btn-sm">Guardar</button>
    </div>
</form>