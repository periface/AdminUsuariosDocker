<div class="container mt-3">
    <div class="row">
        <div class="col-12">
            <nav class="navbar bg-body-tertiary bg-inst">
                <div class="col-6">
                    <div class="container-fluid">
                        <span class="navbar-text text-bold text-white uppercase">
                            <i class="fa-solid fa-user-shield"></i> ROL <span id="role" data-role={{ $rolePermissions->id }}>{{ $rolePermissions->name }}</span><br>
                        </span>
                      </div>
                </div>
                <div class="col-6 d-flex justify-content-end pe-3 pt-2">
                    
                </div>
            </nav>
            <div style="background: #54565a80;" class="pt-1 pb-1">
                <small class=" ps-3 text-white">
                    <i class="fa-regular fa-lightbulb"></i> En esta sección podrá agregar o revocar roles y permisos al role seleccionado.
                </small>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <hr> 
        </div>
        <div class="col-12">
            <div class="row">
                <div class="col mt-3">
                    <span>
                        PERMISOS ASIGNADOS
                    </span>
                </div>
                <div class="col">
                    <div class="d-flex justify-content-end mt-3">
                        <span class="mb-2 btn btn-success btn-sm btn-inst2 atach-permission-role" id="{{ $rolePermissions->id }}">
                            <i class="fa-regular fa-plus"></i> | Agregar Permiso
                        </span>
                    </div>
                </div>
            </div>
            <hr>
            <table class="table table-stripped table-sm mt-4">
                <thead>
                    <tr>
                        <th class="text-center">Permisos</th>
                        <th class="text-center">
                            Opciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($rolePermissions->permissions)>0)
                        @foreach ($rolePermissions->permissions as $permission)
                            <tr>
                                <td class="text-center">
                                    {{ $permission->name }}
                                </td>
                                <td class="text-center">
                                    <span class="detach-permission cursor-pointer" id="{{ $permission->id }}" data-permission="{{ $permission->id }}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="2" class="text-center">
                                
                                <small>NO CUENTA CON PERMISOS ASIGNADOS</small>
                            </td>
                        </tr>
                    @endif
                    
                </tbody>
            </table>
        </div>
    </div>
</div>
