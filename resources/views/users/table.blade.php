<div class="row">
    <div class=" col-12">
        <hr>
        <table class="table table-striped table-sm mt-2">
            <thead class="small">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Área a la que pertenece</th>
                    <th class="text-center">Rol</th>
                    <th class="text-center">Correo Electrónico</th>
                    <th class="text-center">Fecha registro</th>
                    <th class="text-center">Estatus</th>
                    @if (Auth::user()->hasRole('ADM'))
                    <th class="text-center">Opciones</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if (count($users)>0)
                    @foreach ($users as $user)
                        <tr>
                            <td>
                                {{ $loop->iteration }}
                            </td>
                            <td>{{ $user->nombre }} {{ $user->apPaterno }} {{ $user->apMaterno }}</td>
                            <td>
                                    {{ $user->areaName }}
                            </td>
                            <td class="text-center">
                                @if ($user->rol === null)
                                    <span class="badge bg-secondary">
                                        Sin Asignar
                                    </span>
                                @else
                                 @switch($user->rol)
                                     @case("Evaluador")
                                            <span class="badge bg-primary">
                                                {{ $user->rol }}
                                            </span>
                                         @break
                                     @case("Validador")
                                        <span class="badge bg-success">
                                            {{ $user->rol }}
                                        </span>
                                         @break
                                    @case("Responsable de Área")
                                        <span class="badge bg-danger">
                                            {{ $user->rol }}
                                        </span>
                                        @break
                                     @default
                                        <span class="badge bg-info">
                                            {{ $user->rol }}
                                        </span>
                                 @endswitch
                                @endif
                            </td>
                            <td class="text-center">{{ $user->email }}</td>
                            <td class="text-center">{{ $user->fechaCreacion }}</td>
                            <td class="text-center">
                                @if (!$user->status)
                                    <span class="badge bg-danger">
                                        Inactivo
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        Activo
                                    </span>
                                @endif
                            </td>
                            @if (Auth::user()->hasRole('ADM'))
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn dropdown-toggle btn-sm btn-inst3" data-bs-toggle="dropdown">
                                    Administrar
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item edit-user" data-id="{{ $user->id }}" style="cursor: pointer">Editar</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item delete-user" data-id="{{ $user->id }}" style="cursor: pointer">Eliminar</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item config-user" id="{{ $user->id }}" style="cursor: pointer">Roles / Permisos</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                            @endif
                            
                        </tr>
                    @endforeach
                @else

                @endif
            </tbody>
        </table>
    </div>
</div>