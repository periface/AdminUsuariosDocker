@extends('layout.app')
@section('styles')
    <style>
        .error {
            color: red;
            font-weight: normal !important;
            font-size: 14px;
        }
    </style>
@endsection
@section('content')
    <meta name="token" id="token" content="{{ csrf_token() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="row">
        <div class="col-12">
            <nav class="navbar bg-body-tertiary bg-inst">
                <div class="col-6">
                    <div class="container-fluid">
                        <span class="navbar-text text-bold text-white">
                            <i class="fa-solid fa-users-gear"></i> | INDICADORES
                        </span>
                    </div>
                </div>
                <div class="col-6 d-flex justify-content-end pe-3 pt-2">
                    <span class="mb-2 mr-2 btn btn-sm indicadorModalBtn btn-inst2 bg-gray-200">
                        <i class="fa-regular fa-plus"></i> | Agregar Indicador
                    </span>

                    <span class="mb-2 btn btn-sm btn-inst2" id="subir">
                        <i class="fa-regular fa-file"></i> | Subir Layout
                    </span>

                    <input type="file" id="file" class="d-none" accept=".csv">
                </div>
            </nav>
        </div>
    </div>
    <div id="dimensiones_select" class="row mt-3 ">
        <div class="col-12">
            <select class="form-select form-select-md" id="dimensiones_select" aria-label="Default select example">
                <option value="0" selected>Seleccione una dimensión</option>
                @foreach ($dimensiones as $dimension)
                    <option value="{{ $dimension['id'] }}">{{ $dimension['nombre'] }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div id="table-container" class="mt-3">
        @include('partials.table_loader')
    </div>
    <!-- Modal -->
    <div class="modal fade" id="indicadorModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="indicadorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="indicadorModalLabel">Crear/Editar Indicador</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="indicadorForm">
                        <div id="indicadorFields">
                        </div>
                        <div class="modal-footer mt-4">
                            <hr>
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary btn-sm" id="js-guardar-indicador">Guardar</button>
                            <button type="button" class="btn btn-success btn-sm" id="js-confirm-indicador">Guardar y
                                confirmar</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade modal-lg" id="indicadorBatchModal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="indicadorBatchModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="indicadorBatchModalLabel">Carga de Indicadores</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="indicadorBatchForm">
                        <div id="indicadorBatchFields">
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
    @vite(['resources/js/indicadores/index.js'])
@endsection
