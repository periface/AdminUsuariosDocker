<form id="addRole">
    @csrf
    <div class="row mt-2">
        <label for="name">Nombre:</label>
        <input type="text" name="alias" class="form-control" placeholder="nombre del rol">
    </div>
    <div class="row mt-2">
        <label for="name">Alias / Siglas:</label>
        <input type="text" name="name" class="form-control" placeholder="alias o siglas del rol">
    </div>
    <div class="row mt-2">
        <label for="name">Descripcion:</label>
        <textarea name="description" id="description" cols="30" rows="5" class="form-control"></textarea>
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