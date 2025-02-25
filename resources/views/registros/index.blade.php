@extends('layout.app')
@section('estilos')
    <style>
        .error {
            color: red;
            font-weight: normal !important;
            font-size: 14px;
        }

        canvas {
            padding: 5px;
            width: 30% !important;
            margin: 0 auto;
        }
    </style>
@endsection
@section('title')
    <h6 class="m-0 font-weight-bold">
        Monitoreando {{ $indicador['nombre'] }}
        @if ($evaluacion['finalizado'])
            <span class="text-tam-rojo-fuerte font-bold">Finalizada</span>
        @endif
    </h6>
@endsection
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <input type="hidden" id="evaluacionId" value="{{ $evaluacionId }}">
    <div class="grid grid-cols-1">
        <div class="p-2">
            <div class="card shadow p-0">
                <div class="card-header py-3">
                    <p class="m-0 text-md text-red-950 font-bold">
                        <span>
                            Detalles del Indicador
                        </span>
                    </p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <div class="bg-white">
                                <div class="grid grid-cols-1 p-2">
                                    <div>
                                        <p class="font-bold m-0">Area objetivo: </p>
                                        <p class="text-xs">
                                            {{ $area['nombre'] }}
                                        </p>
                                    </div>
                                    <hr>
                                </div>
                                <div class="hidden mt-4 text-center items-center">
                                    <div id="status">
                                    </div>
                                    <div id="total">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="w-full p-2">
                                <p class=" m-0 font-bold"> Método de Cálculo</p>
                                <p>

                                    <span class="text-xs text-pink-950 font-bold">
                                        {{ $indicador['nombre'] }}= </span>
                                    <span class="text-xs text-blue-950">
                                        {{ $indicador['metodo_calculo'] }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="col-4">

                            <canvas id="donut-chart" class="w-full h-auto">
                            </canvas>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="p-2">
            <div class="card shadow mb-4 p-0">

                <div class="card-header py-3">
                    <p class="m-0 text-md text-red-950 font-bold">
                        <span>
                            Registros
                        </span>
                    </p>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1">
                        <div id="table-container">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="anexoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="anexoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
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
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Cerrar</button>
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
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary btn-sm"
                                    id="js-guardar-indicador">Guardar</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- /.modal -->

        <div class="modal fade" id="rechazarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="rechazarModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="rechazarModalLabel">Rechazar Registro</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="rechazarForm">
                            <div id="rechazarFields">
                                <input type="hidden" name="registroId" id="registroId">
                                <div class="form-group mt-2">
                                    <label for="motivo">Motivo</label>
                                    <textarea class="form-control" id="motivo" name="motivo" rows="3"></textarea>
                                    <span class="error" id="motivo-error"></span>
                                </div>
                            </div>
                            <div class="modal-footer mt-4">
                                <hr>
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary btn-sm"
                                    id="js-rechazar-registro">Confirmar</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- Contenedor para los toasts -->
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <!-- Aquí se inyectarán los toasts dinámicamente -->
        </div>
    @endsection
    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @vite(['resources/js/registros/index.js'])
    @endsection
