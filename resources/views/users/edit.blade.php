<form id="userForm">
    @csrf
    <div class="row mt-2">
        <input type="hidden" name="id" value="{{ $user->id }}" id="user">
        <div class="col">
            <label for="name">Nombre:</label>
            <input type="text" name="name" value="{{ $user->nombre }}" class="form-control" placeholder="Nombre de usuario">
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-6">
            <label for="apPaterno">Apellido Paterno:</label>
            <input type="text" name="apPaterno" value="{{ $user->apPaterno }}" class="form-control" placeholder="Apellido paterno">
        </div>
        <div class="col-6">
            <label for="materno">Apellido Materno:</label>
            <input type="text" name="apMaterno" value="{{ $user->apMaterno }}" class="form-control" placeholder="Apellido materno">
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <label for="area">Area a la que pertenece:</label>
            <select class="form-select" id="areaId" name="areaId">
                <option value="0">Seleccione</option>
                @foreach ($areas as $area)
                    <option value="{{ $area->id ?? ''}}"
                        @selected((old('id', $user->areaId ?? '') == $area->id)) >
                            {{ $area["nombre"]}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <label for="area">Rol que desempeña:</label>
            <select class="form-select" id="roleId" name="roleId">
                <option value="0">Seleccione</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}"
                        @selected((old('roleId', $user->roleId ?? '') == $role->id)) >
                            {{ $role->alias}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <label for="is_active">Estatus:</label>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_active" role="switch" id="is_active" 
                {{ $user->status ? 'checked' : ''}}>
                <label class="form-check-label" for="is_active">
                    {{ $user->status ? 'Activo' : 'Inactivo' }}
                </label>
            </div>
        </div>
    </div>
    <div class=" modal-footer mt-2">
        <button type="button" class="btn btn-inst3 btn-sm" data-bs-dismiss="modal">
            <small>CANCELAR</small>
        </button>
        <button type="submit" class="btn btn-inst btn-sm">
            <small>GUARDAR</small>
        </button>
    </div>
</form>
