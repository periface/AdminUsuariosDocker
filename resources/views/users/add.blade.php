<form id="addUser">
    @csrf
    <div class="row mt-2">
        <div class="col">
            <label for="name">Nombre:</label>
            <input type="text" name="name" class="form-control" placeholder="Nombre de usuario">
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-6">
            <label for="apPaterno">Apellido Paterno:</label>
            <input type="text" name="apPaterno" class="form-control" placeholder="Apellido paterno">
        </div>
        <div class="col-6">
            <label for="materno">Apellido Materno:</label>
            <input type="text" name="materno" class="form-control" placeholder="Apellido materno">
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <label for="area">Area:</label>
            <select class="form-select" id="areaId" name="areaId">

                @foreach ($areas as $area)
                    <option value="{{ $area->id }}">
                            {{ $area->nombre }}
                    </option>
                @endforeach

            </select>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <label for="email">Email:</label>
            <input type="email" name="email" class="form-control" placeholder="Correo electrónico">
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <label for="password">Contraseña:</label>
            <input type="password" name="password" class="form-control" placeholder="Contraseña">
        </div>
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
