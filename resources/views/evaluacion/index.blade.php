@extends('layout.app')
@section('estilos')
    <style>
        .error {
            color: red;
            font-weight: normal !important;
            font-size: 14px;
        }
    </style>
    @vite(['node_modules/bs-stepper/dist/css/bs-stepper.min.css'])
@endsection

@section('title')
    <h6 class="m-0 font-weight-bold">EVALUACION DE INDICADORES</h6>
@endsection
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <div class="row">
        <div class="col-12">
            <nav class="navbar bg-body-tertiary bg-inst">
                <div class="col-6">
                    <div class="container-fluid">
                        <span class="navbar-text text-bold text-white">
                            <i class="fa-solid fa-users-gear"></i> | MONITOREO DE INDICADORES
                        </span>
                    </div>
                </div>
                <div class="col-6 d-flex justify-content-end pe-3 pt-2">
                    <span class="mb-2 btn btn-sm evaluacionModalBtn btn-inst2">
                        <i class="fa-regular fa-plus"></i> | Monitorear Indicador
                    </span>
                </div>
            </nav>
        </div>
    </div> --}}
    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between py-3">
            <h6 class="m-0 font-weight-bold text-gray-800">
                EVALUACIONES INICIADAS
            </h6>
            <a class="btn bg-inst2 btn-icon-split btn-sm">
                <span class="icon text-white evaluacionModalBtn">
                    <i class="fa fa-plus"></i>
                </span>
                <span class="text text-white evaluacionModalBtn">Evaluar Indicador</span>
            </a>
        </div>
        <div class="card-body">
            <div id="table-container">

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="evaluacionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="evaluacionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="evaluacionModalLabel">Iniciar Evaluación</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="evaluacionForm">
                        <div id="evaluacionFields">
                        </div>

                        <div class="mt-4 modal-footer">
                            <hr>
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>

                            <button type="button" id="js-step-back" class="btn btn-secondary btn-sm">Regresar</button>
                            <button type="button" id="js-step" disabled
                                class="btn btn-primary btn-sm js-step">Siguiente</button>

                            <button type="submit" id="js-submit" disabled
                                class="btn btn-primary btn-sm js-submit">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->

    <!-- Contenedor para los toasts -->
    <div class="top-0 p-3 toast-container position-fixed end-0">
        <!-- Aquí se inyectarán los toasts dinámicamente -->
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/js/evaluacion/index.js'])
@endsection
