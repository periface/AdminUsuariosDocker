@extends('layout.app')
@section('estilos')
    <style>
        .error {
            color: red;
            font-weight: normal !important;
            font-size: 14px;
        }
    </style>
@endsection
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <input type="hidden" id="evaluacionId" value="{{ $evaluacionId }}">
    <div class="grid grid-cols-1">

        <div class="row">
            <div class="col-12">
                <nav class="navbar bg-body-tertiary bg-inst">
                    <div class="col-12">
                        <div class="container-fluid">
                            <span class="navbar-text text-bold text-white">
                                <i class="fa-solid fa-users-gear"></i> | REGISTROS DE {{ strtoupper($indicador['nombre']) }}
                            </span>
                        </div>
                    </div>
                </nav>
            </div>

            <div class="col-12">
                <div class="row">
                    <div class="col-3">
                        <div class="row">
                            <div class="col-12">
                                <div class="bg-white rounded-md shadow-sm p-3">

                                    <span class="badge bg-pink-950 rounded-none">
                                        <i class="fa-solid fa-info"></i> | Área
                                    </span>
                                    <p class="m-0 text-md">
                                        <span>
                                            {{ $area['nombre'] }}
                                        </span>
                                    </p>
                                    <span class="badge bg-pink-950 rounded-none">
                                        <i class="fa-solid fa-info"></i> | Indicador
                                    </span>
                                    <p class="m-0 text-md" title="{{ $indicador['descripcion'] }}">
                                        <span>
                                            {{ $indicador['nombre'] }}
                                        </span>
                                    </p>

                                    <br>
                                    <hr>
                                    <div class="grid grid-cols-2 mt-2 text-center items-center">
                                        <div id="status">
                                        </div>

                                        <div id="total">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 mt-2">

                                        <div class="w-full">
                                            <span class="text-xs">
                                                {{ $indicador['metodo_calculo'] }}
                                            </span>
                                        </div>
                                    </div>
                                    <br>
                                    <hr>
                                    <br>
                                    <canvas id="donut-chart" class="w-full h-auto">
                                    </canvas>
                                </div>
                            </div>
                            <div class="col-12 p-10">
                            </div>
                        </div>
                    </div>
                    <div id="table-container" class="col-9 bg-white shadow-sm">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="registroModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="registroModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="registroModalLabel">Capturar Registro</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="registroForm">
                        <div id="registroFields">
                        </div>
                        <div class="modal-footer mt-4">
                            <hr>
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary btn-sm" id="js-guardar-indicador">Guardar</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- /.modal -->

    <!-- Contenedor para los toasts -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <!-- Aquí se inyectarán los toasts dinámicamente -->
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/js/registros/index.js'])
@endsection
