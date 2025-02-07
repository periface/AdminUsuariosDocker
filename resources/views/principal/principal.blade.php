@extends('layout')

@section('content')
<div class="container-fluid"  style="background-color:#fff;">
    <input type="hidden" value="{{ $token }}" name="token" id="token">
    <div class="row flex-nowrap">
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 sidebar-container">
            <div class="overlay">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                    <a href="/" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none py-3">
                        <span class="fs-5 d-none d-sm-inline">Sistema de Indicadores <br> <small>Dirección de Control Administrativo</small></span>
                    </a>
                    <hr width="100%">
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start mt-4" >
                        <li>
                            <span class="nav-link px-0 align-middle text-white cursor-pointer" id="evals">
                                                        <a href="{{ route('evaluacion.index') }}" class="">
                                <i class="fa-solid fa-chart-diagram"></i> <span class="ms-1 d-none d-sm-inline">Evaluaciones</span>
                                </a>
                            </span>
                        </li>
                        <li>
                            <span class="nav-link px-0 align-middle text-white cursor-pointer" id="areas">
                                <i class="fa-solid fa-sitemap"></i> <span class="ms-1 d-none d-sm-inline">Áreas</span>
                            </span>
                        </li>
                        <li>
                            <span class="nav-link px-0 align-middle text-white cursor-pointer" id="dimensiones">
                                                        <a href="{{ route('dimension.index') }}" class="">
                                <i class="fa-solid fa-chart-diagram"></i> <span class="ms-1 d-none d-sm-inline">Dimensiones</span>
                                </a>
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
                        <li>
                            <span class="nav-link px-0 align-middle text-white cursor-pointer" id="monitor">
                                <i class="fas fa-chart-line"></i> <span class="ms-1 d-none d-sm-inline">Monitor de Indicadores</span>
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
        <div class="col">
            <div class="row">
                <div id="content">
                    <div class="container welcome-container">
                        <div class="card">
                            <h1 class="lead mb-3 text-gray-500 font-bold text-principal">Bienvenido al <br> Sistema de Indicadores</h1>
                            <p class="lead mb-4 text-gray-500 text-secundario">Una plataforma diseñada para la gestión y <br> análisis de indicadores clave de desempeño en la <br> Dirección de Control Administrativo.</p>
                
                            <hr>
                
                            <h3 class="mt-3 text-gray-700">¿Qué puedes hacer con este sistema?</h3>
                            <ul class="list-group list-group-flush text-start mx-auto mt-3 " style="max-width: 400px;">
                                <li class="list-group-item text-gray-700">📊 Registrar y gestionar indicadores de desempeño.</li>
                                <li class="list-group-item text-gray-700">📈 Visualizar reportes y gráficos en tiempo real.</li>
                                <li class="list-group-item text-gray-700">📌 Evaluar la eficiencia y eficacia de los procesos.</li>
                                <li class="list-group-item text-gray-700">⚡ Optimizar la toma de decisiones administrativas.</li>
                            </ul>
                
                            <div class="mt-4">
                                <a href="manual_usuario.pdf" class="btn btn-outline-secondary btn-sm">Ver Manual de Usuario</a>
                            </div>
                
                            <p class="footer mt-4">Para soporte técnico, contacte a: <br> <a href="mailto:soportecontroladministrativo@tamaulipas.gob.mx" class=" text-white lowercase badge bg-danger">soportecontroladministrativo@tamaulipas.gob.mx</a></p>
                        </div>
                    </div>
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
        'resources/js/utils/pageLoader.js',
        'resources/js/usuarios/index.js',
        'resources/js/auth/permissions/permisos.js',
        'resources/js/areas/index.js',
        'resources/js/dataviz/index.js'
    ])
@endsection
