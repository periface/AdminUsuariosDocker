@extends('layout.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">
            <i class="fa fa-user-lock"></i> | PERMISOS REGISTRADOS EN EL SISTEMA
        </h6>
        
    </div>
    <div class="card-body">
        <table class="table table-striped table-sm mt-2"> 
            <thead>
                <tr>
                    <th class=" w-1/3">#</th>
                    <th class=" w-1/3">Permiso</th>
                    <th class=" w-1/3">Fecha registro</th>
                    <th class=" w-2/4">Opciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permissions as $permission)
                    <tr>
                        <td class=" w-1/3">
                         -
                        </td>
                        <td class=" w-1/3">
                            {{ $permission->name }}
                        </td>
                        <td class=" w-1/3">
                            {{ $permission->fechaCreacion }}
                        </td>
                        <td class=" w-2/4">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn dropdown-toggle btn-sm btn-inst3" data-bs-toggle="dropdown">
                                Administrar
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item edit-permission" id="{{ $permission->id }}" style="cursor: pointer">Editar</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item delete-permission" id="{{ $permission->id }}" style="cursor: pointer">Eliminar</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
{{-- <div class="container mt-3">
    <div class="row">
        <div class="col-12">
            <nav class="navbar bg-body-tertiary bg-inst">
                <div class="col-6">
                    <div class="container-fluid">
                        <span class="navbar-text text-bold text-white">
                            
                        </span>
                      </div>
                </div>
                <div class="col-6 d-flex justify-content-end pe-3 pt-2">
                    <span class="mb-2 btn btn-sm add-permission btn-inst2">
                        <i class="fa-regular fa-plus"></i> | Agregar Permiso
                    </span>
                </div>
            </nav>
        </div>
    </div>
    <div class="row mt-4">
        <div class=" col-12">
            <hr>
            
        </div>
    </div>
</div> --}}