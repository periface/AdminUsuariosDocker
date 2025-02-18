@extends('layout.app')
@section('title')
@endsection
@section('content')
<div class="card shadow mb-4">
    <div class="card-header d-flex justify-content-between  py-3">
        <h6 class="m-0 font-weight-bold">
            <i class="fa-solid fa-users-gear"></i> | USUARIOS REGISTRADOS EN EL SISTEMA
        </h6>
        <a class="btn bg-inst2 btn-icon-split btn-sm" id="add-user">
            <span class="icon text-white">
                <i class="fa fa-plus"></i>
            </span>
            <span class="text text-white">Agregar Usuario</span>
        </a>
    </div>
    <div class="card-body" id="table-users">
       
    </div>
</div>
@endsection
@section('scripts')
    @vite([ 'resources/js/usuarios/index.js'])
@endsection