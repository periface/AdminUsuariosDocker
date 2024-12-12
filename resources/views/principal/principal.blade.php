@extends('layout')

@section('content')
<div class="container-fluid"  style="background-color:#fff;">
    <input type="hidden" value="{{ $token }}" name="token" id="token">
    <div class="row flex-nowrap">
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 sidebar-container">
            <div class="overlay">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                    <a href="/" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <span class="fs-5 d-none d-sm-inline">Administración de Usuarios</span>
                    
                    </a>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start mt-4" >
                        <li>
                            <span class="nav-link px-0 align-middle text-white cursor-pointer" id="evals">
                                <i class="fa-solid fa-list-check"></i> <span class="ms-1 d-none d-sm-inline">Evaluaciones</span>
                            </span>
                        </li>
                        <li>
                            <span class="nav-link px-0 align-middle text-white cursor-pointer" id="areas">
                                <i class="fa-solid fa-sitemap"></i> <span class="ms-1 d-none d-sm-inline">Áreas</span>
                            </span>
                        </li>
                        <li>
                            <span class="nav-link px-0 align-middle text-white cursor-pointer" id="dimensiones">
                                <i class="fa-solid fa-chart-diagram"></i> <span class="ms-1 d-none d-sm-inline">Dimensiones</span>
                            </span>
                        </li>
                        <li>
                            <span class="nav-link px-0 align-middle text-white cursor-pointer" id="users">
                                <i class="fa-solid fa-users"></i> <span class="ms-1 d-none d-sm-inline">Usuarios</span>
                            </span>
                        </li>
                        <li>
                            <span class="nav-link px-0 align-middle text-white cursor-pointer" id="roles">
                                <i class="fa-solid fa-user-shield"></i> <span class="ms-1 d-none d-sm-inline">Roles</span>
                            </span>
                        </li>
                        <li>
                            <span class="nav-link px-0 align-middle text-white cursor-pointer" id="permissions">
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
                                    <button type="submit" class="btn btn-sm text-white">
                                       <small>Cerrar Sessión</small> 
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col py-3">
            <div class="row">
                <div id="content">
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
        'resources/js/auth/permissions/permisos.js',
        'resources/js/evaluaciones/evaluaciones.js',
        'resources/js/areas/index.js',
        'resources/js/dimensiones/index.js'
    ]);
@endsection