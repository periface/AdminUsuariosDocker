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

            <div id="table-container">
            </div>

            <div class="grid grid-cols-1">
                <div>

                    <p class="m-0 text-sm">
                        <span class="font-bold">
                            Descripción:
                        </span> <br>
                        <span class="text-pink-900">
                        </span>
                        Indicador:
                        <span>
                            {{ $indicador['descripcion'] }}
                        </span>
                    </p>

                    <p class="m-0 text-sm">
                        <span class="font-bold">
                            Método de cálculo:
                        </span> <br>
                        <span>
                            {{ $indicador['metodo_calculo'] }}
                        </span>
                    </p>
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
