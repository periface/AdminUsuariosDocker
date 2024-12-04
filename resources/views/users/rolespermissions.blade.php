<div class="container mt-3">
    <div class="row">
        <div class="col-12">
            <nav class="navbar bg-body-tertiary bg-inst3">
                <div class="col-6">
                    <div class="container-fluid">
                        <span class="navbar-text text-bold text-white uppercase">
                            <i class="fa-solid fa-user-gear"></i> | CONFIGURANDO <span>{{ $userDto->name }}</span>
                        </span>
                      </div>
                </div>
                <div class="col-6 d-flex justify-content-end pe-3 pt-2">
                    
                </div>
            </nav>
            <div style="background: #54565a80;" class="pt-1 pb-1">
                <small class=" ps-3 text-white">
                    <i class="fa-regular fa-lightbulb"></i> En esta sección podrá agregar o revocar roles y permisos al usuario seleccionado.
                </small>
            </div>
        </div>
        
    </div>
    {{-- <div class="row">
        <div class="col-6">
            <h3 id="usuario" data-user="{{ $userDto->id }}">User: {{ $userDto->name }} </h3>
        </div>
        <div class="col-6">
        </div>
    </div> --}}
    <div class="row mt-3">
        <div class="col-12">
            <hr> 
        </div>
        <div class="col-6">
            <table class="table table-stripped">
                <thead>
                    <tr>
                        <th>Rol</th>
                        <th>Siglas</th>
                        <th>
                            <span type="button" class="btn btn-outline-success atach-role btn-sm">Agregar Rol</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>
                                {{ $role->alias }}
                            </td>
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
