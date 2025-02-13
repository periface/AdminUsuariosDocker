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
                                <i class="fa-solid fa-users-gear"></i> | MONITOREO DE {{ strtoupper($indicador['nombre']) }}
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
                                    <p class="m-0 text-lg text-red-950 font-bold">
                                        <span>
                                            {{ $area['nombre'] }}
                                        </span>
                                    </p>
                                    <hr>
                                    <br>
                                    <div class="grid grid-cols-1">

                                        <div class="w-full">
                                            <p class="text-center m-0"> Método de Cálculo</p>
                                            <p>

                                                <span class="text-xs text-pink-950 font-bold">
                                                    {{ $indicador['nombre'] }}= </span>
                                                <span class="text-xs text-blue-950">
                                                    {{ $indicador['metodo_calculo'] }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    <br>
                                    <hr>
                                    <div class="hidden grid grid-cols-2 mt-4 text-center items-center">
                                        <div id="status">
                                        </div>
                                        <div id="total">
                                        </div>
                                    </div>
                                    <br>
                                    <canvas id="donut-chart" class="w-full h-auto">
                                    </canvas>
                                </div>
                            </div>
                            <div class="col-12 p-10">
                            </div>
                        </div>
                    </div>

                    <div class="col-9">
                        <div class="grid grid-cols-1 bg-white">
                            <div id="table-container" class="p-2">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-lg" id="anexoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="anexoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="anexoModalLabel">Medio de Verificación</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="anexoForm">
                        <div id="anexoFields">
                        </div>
                        <div class="modal-footer mt-4">
                            <hr>
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </form>
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
