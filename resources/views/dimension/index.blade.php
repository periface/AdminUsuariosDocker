@extends('layout.app')
@section('style')
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

    <div class="row">
        <div class="col-12">
            <nav class="navbar bg-body-tertiary bg-inst">
                <div class="col-6">
                    <div class="container-fluid">
                        <span class="navbar-text text-bold text-white">
                            <i class="fa-solid fa-users-gear"></i> | DIMENSIONES REGISTRADAS EN EL SISTEMA
                        </span>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    <div id="table-container">
        @include('partials.table_loader')
    </div>
    <!-- Modal -->
    <div class="modal fade" id="dimensionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="dimensionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="dimensionModalLabel">Crear/Editar Dimensión</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="dimensionForm">
                        <div id="dimensionFields">
                        </div>
                        <div class="modal-footer mt-4">
                            <hr>
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
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
    @vite(['resources/js/dimensiones/index.js'])
@endsection
