<div class="container mt-4">
    <div class="row">
        <div class="col-6">
            <h3 id="usuario" data-user="{{ $userDto->id }}">User: {{ $userDto->name }} </h3>
        </div>
        <div class="col-6">
        </div>
    </div>
    <div class="row">
        <hr>
        <div class="col-6">
            <table class="table table-stripped">
                <thead>
                    <tr>
                        <th>Roles</th>
                        <th>
                            <span type="button" class="btn btn-outline-success atach-role btn-sm">Agregar Rol</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>
                                {{ $role->name }}
                            </td>
                            <td>
                                <a class="detach-role" id="{{ $role->id }}" data-role="{{ $role->id }}">
                                    <i class="fa fa-trash text-danger"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-6">
            <table class="table table-stripped">
                <thead>
                    <tr>
                        <th>Permisos</th>
                        <th>
                            <span class="btn btn-outline-success atach-permission btn-sm">Agregar Permiso</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissions as $permission)
                        <tr>
                            <td>
                                {{ $permission->name }}
                            </td>
                            <td>
                                <a class="detach-permission" id="{{ $permission->id }}" data-permission="{{ $permission->id }}">
                                    <i class="fa fa-trash text-danger"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
