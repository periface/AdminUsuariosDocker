@extends('layout')
@section('content')
<div class="container">
    
    <div class="row">
        <div class="col-6">

        </div>
        <div class="col-6">
            <span class="btn btn-success btn-sm add-role">
                Agregar Rol
            </span>
        </div>
    </div>
    <div class="row">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th class="text-center w-1/3">Rol</th>
                    <th class="text-left">Siglas</th>
                    <th class="text-left w-1/3">Descripcion</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>
                         -
                        </td>
                        <td class="text-center w-1/3">
                            {{ $role->alias }}
                        </td>
                        <td class="text-left">
                            {{ $role->name }}
                        </td>
                        <td class="text-left w-1/3">
                            <p class="text-break">
                                {{ $role->description }}
                            </p>
                        </td>
                        <td>
                            <a id="{{ $role->id }}" style="cursor: pointer" class="text-primary text-sm edit-role">Editar</a> | <a id="{{ $role->id }}" style="cursor: pointer" class="text-danger text-sm delete-role">Eliminar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
    
@endsection