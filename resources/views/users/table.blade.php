<div class="container">
    <div class="row">
        <div class="col-6">

        </div>
        <div class="col-6">
            <a class="btn btn-success btn-sm add-user">
                Agregar Usuario
            </a>
        </div>
    </div>
</div>
<div class="row">
    <table class="table table-striped"> 
        <thead>
            <tr>
                <th>#</th>
                <th>name</th>
                <th>email</th>
                <th>creado el</th>
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
                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
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
