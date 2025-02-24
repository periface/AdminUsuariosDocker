
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
@section('title')
    <h6 class="m-0 font-weight-bold">CATALOGOS: CATEGORIAS</h6>
@endsection
@section('content')
    <meta name="token" id="token" content="{{ csrf_token() }}">
    <div class="card shadow mb-4">

        <div class="card-header d-flex justify-content-between py-3">
            <h6 class="m-0 font-weight-bold text-gray-800">
                CATEGORIAS REGISTRADAS
            </h6>
            <a class="btn bg-inst2 btn-icon-split btn-sm">
                <span class="icon text-white categoriaModalBtn">
                    <i class="fa fa-plus"></i>
                </span>
                <span class="text text-white categoriaModalBtn">Crear Categoria</span>
            </a>
        </div>
        <div class="card-body">
            <div id="table-container">
                @include('partials.table_loader')
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="categoriaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="categoriaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="categoriaModalLabel">Crear/Editar Categoria</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="categoriaForm">
                        <div id="categoriaFields">
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
    @vite(['resources/js/categorias/index.js'])
@endsection
