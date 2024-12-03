@extends('layout')

@section('content')
{{-- <div class="container">
    <input type="hidden" value="{{ $token }}" name="token" id="token">
    <div class="row">
        <div class="col-6">
            <h4>Bienvenido
                <small>
                    @if (auth())
                        <span>{{ auth()->user()->name }}</span>
                    @endif
                </small>
            </h4>
        </div>
        <div class="col-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">
                    Cerrar Sessión
                </button>
            </form>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col">
            <button id="users" style="cursor: pointer">
                Ver Usuarios
            </button><br>
            <button id="permissions" style="cursor: pointer">
                Ver Permisos
            </button><br>
            <button id="roles" style="cursor: pointer">
                Ver Roles
            </button>
        </div>
    </div>
    <div class="row">
        <hr>
        <div class="col text-center" id="content">
            <small>Sin información para mostrar</small>
        </div>
    </div>
</div> --}}
<div class="container-fluid">
    <div class="row flex-nowrap">
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                <a href="/" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <span class="fs-5 d-none d-sm-inline">Administración de Usuarios</span>
                
                </a>
                <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start mt-4" id="menu">
                    <li>
                        <span class="nav-link px-0 align-middle text-white cursor-pointer" id="users">
                            <i class="fa-solid fa-users"></i> <span class="ms-1 d-none d-sm-inline">Usuarios</span>
                        </span>
                    </li>
                    <li>
                        <span class="nav-link px-0 align-middle text-white cursor-pointer" id="permissions">
                            <i class="fa-solid fa-user-shield"></i> <span class="ms-1 d-none d-sm-inline">Roles</span>
                        </span>
                    </li>
                    <li>
                        <span class="nav-link px-0 align-middle text-white cursor-pointer" id="roles">
                            <i class="fa-solid fa-user-lock"></i> <span class="ms-1 d-none d-sm-inline">Permisos</span>
                        </span>
                    </li>
                </ul>
                <hr>
                <div class="dropdown pb-4">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://github.com/mdo.png" alt="hugenerd" width="30" height="30" class="rounded-circle">
                        <span class="d-none d-sm-inline mx-1">{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" >
                                    Cerrar Sessión
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col py-3">
            <div class="row">
                <div class="col text-center" id="content">
                    <small>Sin información para mostrar</small>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Section Modal -->
<!-- Modal -->
<div class="modal fade" id="modalConfig" tabindex="-1" aria-labelledby="modalConfig">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Config Modal</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        function storeToken(){
            let token = document.getElementById('token').value;
            localStorage.setItem('token', token);
        }
        storeToken();
    </script>
    @vite([
        'resources/js/usuarios/index.js',
        'resources/js/auth/permissions/permisos.js'
    ]);
@endsection