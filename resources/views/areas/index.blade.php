@extends('layout.app')
@section('title')
@endsection
@section('content')
<div class="mt-3">
    {{-- <div class="row">
        <div class="col-12">
            <nav class="navbar bg-body-tertiary bg-inst">
                <div class="col-6">
                    <div class="container-fluid">
                        <span class="navbar-text text-bold text-white">
                            <i class="fa-solid fa-sitemap"></i> | ÁREAS REGISTRADAS EN EL SISTEMA
                        </span>
                      </div>
                </div>
                <div class="col-6 d-flex justify-content-end pe-3 pt-2">
                    <span class="mb-2 btn btn-sm add-area btn-inst2">
                        <i class="fa-regular fa-plus"></i> | Agregar Área
                    </span>
                </div>
            </nav>
        </div>
    </div> --}}
    <div class="row">
        
        <div class="col-12 mt-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-gray-800">
                        ÁREAS REGISTRADAS EN EL SISTEMA
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <td class=" w-1/12 text-center">#</td>
                                <td class=" w-1/3">Nombre</td>
                                <td class=" w-1/5 text-center">Siglas</td>
                                <td class=" w-1/7 text-center">Responsable</td>
                                <td class=" w-1/5 text-center">Fecha registro</td>
                                <td class=" w-2/4">Opciones</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($areas as $area)
                            <tr>
                                <td class=" w-1/12 text-center">
                                    {{ $loop->iteration }}
                                </td>
                                <td class=" w-1/3">
                                    {{ $area->nombre }}
                                </td>
                                <td class=" w-1/5 text-center">
                                    {{ $area->siglas }}
                                </td>
                                <td class=" w-1/7 text-center">
                                    @if ($area->responsable == null)
                                        <span class=" badge bg-secondary">
                                            Sin Asignar
                                        </span>
                                    @else
                                        <span class=" badge bg-success">
                                            {{ $area->responsable }}
                                        </span>
                                    @endif
                                </td>
                                <td class=" w-1/5 text-center">
                                    {{ $area->fecha_creacion }}
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn dropdown-toggle btn-sm btn-inst3" data-bs-toggle="dropdown">
                                        Administrar
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item edit-area" id="{{ $area->id }}" style="cursor: pointer">Editar</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item delete-area" id="{{ $area->id }}" style="cursor: pointer">Eliminar</a>
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
            
            {{-- <table class="table table-striped table-sm mt-2">
                <thead>
                    <tr>
                        <th class=" w-1/12 text-center">#</th>
                        <th class=" w-1/5">Nombre</th>
                        <th class=" w-1/5 text-center">Siglas</th>
                        <th class=" w-1/5 text-center">Responsable</th>
                        <th class=" w-1/5 text-center">Fecha registro</th>
                        <th class=" w-2/4">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($areas as $area)
                    <tr>
                        <td class=" w-1/12 text-center">
                            {{ $loop->iteration }}
                        </td>
                        <td class=" w-1/5">
                            {{ $area->nombre }}
                        </td>
                        <td class=" w-1/5 text-center">
                            {{ $area->siglas }}
                        </td>
                        <td class=" w-1/5 text-center">
                            @if ($area->responsable == null)
                                <span class=" badge bg-secondary">
                                    Sin Asignar
                                </span>
                            @else
                                <span class=" badge bg-success">
                                    {{ $area->responsable }}
                                </span>
                            @endif
                        </td>
                        <td class=" w-1/5 text-center">
                            {{ $area->fecha_creacion }}
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn dropdown-toggle btn-sm btn-inst3" data-bs-toggle="dropdown">
                                Administrar
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item edit-area" id="{{ $area->id }}" style="cursor: pointer">Editar</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item delete-area" id="{{ $area->id }}" style="cursor: pointer">Eliminar</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table> --}}
        </div>
    </div>
</div>
@endsection

