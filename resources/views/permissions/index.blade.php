@extends('layout')
@section('content')
<div class="container">
    <div class="row">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Permiso</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permissions as $permission)
                    <tr>
                        <td>
                         -
                        </td>
                        <td>
                            {{ $permission->name }}
                        </td>
                        <td>
                            <a style="cursor: pointer" class="text-primary text-sm">Editar</a> | <a style="cursor: pointer" class="text-danger text-sm">Eliminar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
    
@endsection