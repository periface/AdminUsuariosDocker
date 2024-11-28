<form id="addUser">
    @csrf
    <div class="row mt-2">
        <label for="name">Nombre:</label>
        <input type="text" name="name" class="form-control" placeholder="Nombre de usuario">
    </div>
    <div class="row mt-2">
        <label for="email">Email:</label>
        <input type="email" name="email" class="form-control" placeholder="Correo electrónico">
    </div>
    <div class="row mt-2">
        <label for="password">Contraseña:</label>
        <input type="password" name="password" class="form-control" placeholder="Contraseña">
    </div>
    <div class="row mt-2">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit">Guardar</button>
    </div>
</form>