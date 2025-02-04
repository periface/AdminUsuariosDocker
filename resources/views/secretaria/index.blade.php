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
    <div class="grid grid-cols-1">
        <div class="row">
            <div class="col-12">
                <nav class="navbar bg-body-tertiary bg-inst">
                    <div class="col-6">
                        <div class="container-fluid">
                            <span class="navbar-text text-bold text-white">
                                <i class="fa-solid fa-users-gear"></i> | SECRETARIAS
                            </span>
                        </div>
                    </div>

                    <div class="col-6 d-flex justify-content-end pe-3 pt-2">
                        <span class="mb-2 btn btn-sm secretariaModalBtn btn-inst2">
                            <i class="fa-regular fa-plus"></i> | Agregar Secretaria
                        </span>
                    </div>
                </nav>

            </div>

            <div id="table-container">
            </div>
        </div>
    </div>
    <!-- /.modal -->

    <div class="modal fade" id="secretariaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="secretariaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="secretariaModalLabel">Crear/Editar Secretaria</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="secretariaForm">
                        <div id="secretariaFields">
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
    <!-- Contenedor para los toasts -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <!-- Aquí se inyectarán los toasts dinámicamente -->
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/js/secretarias/index.js'])
@endsection
