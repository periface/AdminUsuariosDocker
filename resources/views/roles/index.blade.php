@extends('layout.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">
            <i class="fa fa-user-shield"></i> | ROLES REGISTRADOS EN EL SISTEMA
        </h6>
    </div>
    <div class="card-body">
        <table class="table table-striped table-sm mt-2"> 
            <thead>
                <tr>
                    <th class="w-1/12">#</th>
                    <th class="text-center w-1/5">Rol</th>
                    <th class="text-center w-1/6">Siglas</th>
                    <th class="text-left w-1/3">Descripcion</th>
                    <th class="text-center">Opciones</th>
                </tr>
            </thead>
            <tbody>
                @if (count($roles)>0)
                    @foreach ($roles as $role)
                        <tr>
                            <td class=" w-1/12">
                            {{ $loop->iteration }}
                            </td>
                            <td class="text-center w-1/5">
                                {{ $role->alias }}
                            </td>
                            <td class="text-center w-1/6">
                                {{ $role->name }}
                            </td>
                            <td class="text-left w-1/3">
                                <p class="text-break">
                                    {{ $role->description }}
                                </p>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn dropdown-toggle btn-sm btn-inst3" data-bs-toggle="dropdown">
                                    Administrar
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item edit-role" id="{{ $role->id }}" style="cursor: pointer">Editar</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item delete-role" id="{{ $role->id }}" style="cursor: pointer">Eliminar</a>
                                        </li>
                                        {{-- <li>
                                            <a class="dropdown-item permissions-role" id="{{ $role->id }}" style="cursor: pointer">Permisos</a>
                                        </li> --}}
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
@endsection
