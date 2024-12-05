<div class="container mt-3">
    <div class="row">
        <div class="col-12">
            <nav class="navbar bg-body-tertiary bg-inst">
                <div class="col-6">
                    <div class="container-fluid">
                        <span class="navbar-text text-bold text-white">
                            <i class="fa-solid fa-users-gear"></i> | USUARIOS REGISTRADOS EN EL SISTEMA
                        </span>
                      </div>
                </div>
                <div class="col-6 d-flex justify-content-end pe-3 pt-2">
                    <span class="mb-2 btn btn-sm add-user btn-inst2">
                        <i class="fa-regular fa-plus"></i> | Agregar Usuario
                    </span>
                </div>
            </nav>
        </div>
    </div>
    <div class="row mt-4">
        <div class=" col-12">
            <hr>
            <table class="table table-striped table-sm mt-2"> 
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Correo Electrónico</th>
                        <th>Fecha registro</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($users)>0)
                        @foreach ($users as $user)
                            <tr>
                                <td></td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->fechaCreacion }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn dropdown-toggle btn-sm btn-inst3" data-bs-toggle="dropdown">
                                        Administrar
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item edit-user" id="{{ $user->id }}" style="cursor: pointer">Editar</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item delete-user" id="{{ $user->id }}" style="cursor: pointer">Eliminar</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item config-user" id="{{ $user->id }}" style="cursor: pointer">Roles / Permisos</a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>