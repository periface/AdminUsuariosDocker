<form id="userForm">
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
    <div class="row mt-2">
        <div class="col">
            <label for="area">Seleccione la secretaría:</label>
            <select class="form-select" id="indicadorId" name="indicadorId">
                <option value="">Seleccione su secretaría</option>
                @foreach ($secretarias as $secretaria)
                    @if (count($secretaria->areas) == 0)
                        @continue
                    @endif
                    <optgroup label="{{ $secretaria->nombre }}">
                        @foreach ($secretaria->areas as $area)
                                <option value="0">
                                    {{ $area->nombre }} </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <label for="area">Area a la que pertenece:</label>
            <select class="form-select" id="areaId" name="areaId">
                <option value="0">Seleccione</option>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}">
                            {{ $area->sria_siglas.' - '.$area->nombre}}
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
                    <option value="{{ $role->id }}">
                            {{ $role->alias}}
                    </option>
                @endforeach
            </select>
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
