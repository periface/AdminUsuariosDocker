<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Encode+Sans:wght@100..900&display=swap" rel="stylesheet">

    <!-- Frameworks -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
        integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            body {
                font-family: "Encode Sans", sans-serif;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        </style>
    @endif
    @yield('estilos')
</head>

<body>

    <div class="container-fluid" style="background-color:#fff;">

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
        <div class="row flex-nowrap">
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 sidebar-container">
                <div class="overlay">
                    <div
                        class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                        <a href="/"
                            class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                            <span class="fs-5 d-none d-sm-inline">Administración de Usuarios</span>

                        </a>
                        <ul
                            class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start mt-4">
                            <li>
                                <span class="nav-link px-0 align-middle text-white cursor-pointer">
                                    <a href="{{ route('evaluacion.index') }}" class="">
                                        <i class="fa-solid fa-chart-diagram"></i> <span
                                            class="ms-1 d-none d-sm-inline">Monitoreo</span>
                                    </a>
                                </span>
                            </li>

                            <li>
                                <span class="nav-link px-0 align-middle text-white cursor-pointer" id="dimensiones">
                                    <a href="{{ route('secretaria.index') }}" class="">
                                        <i class="fa-solid fa-chart-diagram"></i> <span
                                            class="ms-1 d-none d-sm-inline">Secretarias</span>
                                    </a>
                                </span>
                            </li>
                            <li>
                                <span class="nav-link px-0 align-middle text-white cursor-pointer" id="areas">
                                    <i class="fa-solid fa-sitemap"></i> <span
                                        class="ms-1 d-none d-sm-inline">Áreas</span>
                                </span>
                            </li>
                            <li>

                                <span class="nav-link px-0 align-middle text-white cursor-pointer">
                                    <a href="{{ route('dimension.index') }}" class="">
                                        <i class="fa-solid fa-chart-diagram"></i> <span
                                            class="ms-1 d-none d-sm-inline">Dimensiones</span>
                                    </a>
                                </span>
                            </li>
                            <li>

                                <span class="nav-link px-0 align-middle text-white cursor-pointer">
                                    <a href="{{ route('indicador.index') }}" class="">
                                        <i class="fa-solid fa-chart-diagram"></i> <span
                                            class="ms-1 d-none d-sm-inline">Indicadores</span>
                                    </a>
                                </span>
                            </li>

                            <li>
                                <span class="nav-link px-0 align-middle text-white cursor-pointer" id="users">
                                    <i class="fa-solid fa-users"></i> <span
                                        class="ms-1 d-none d-sm-inline">Usuarios</span>
                                </span>
                            </li>
                            <li>
                                <span class="nav-link px-0 align-middle text-white cursor-pointer" id="roles">
                                    <i class="fa-solid fa-user-shield"></i> <span
                                        class="ms-1 d-none d-sm-inline">Roles</span>
                                </span>
                            </li>
                            <li>
                                <span class="nav-link px-0 align-middle text-white cursor-pointer" id="permissions">
                                    <i class="fa-solid fa-user-lock"></i> <span
                                        class="ms-1 d-none d-sm-inline">Permisos</span>
                                </span>
                            </li>
                        </ul>
                        <hr>
                        <div class="dropdown pb-4">
                            <a href="#"
                                class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                                id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://github.com/mdo.png" alt="hugenerd" width="30" height="30"
                                    class="rounded-circle">
                                <span class="d-none d-sm-inline mx-1">{{ auth()->user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark text-small shadow"
                                aria-labelledby="dropdownUser1">
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
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js"
        integrity="sha512-KFHXdr2oObHKI9w4Hv1XPKc898mE4kgYx58oqsc/JqqdLMDI4YjOLzom+EMlW8HFUd0QfjfAvxSL6sEq/a42fQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @yield('scripts')

</body>

</html>
