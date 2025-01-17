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
    <div class="grid grid-cols-[60%_40%]">

        <div class="card card-outline card-danger m-2">

            <div class="card-header">
                <h6 class="card-title m-0 font-bold">Registros</h6>
            </div>
            <div class="card-body">

                <div class="grid grid-cols-1">
                    <table class="table table-bordered table-striped table-hover" id="table-container">
                        <thead>

                            <tr>
                                <th>Periodo</th>
                                <th>Fecha</th>
                                <th>Resultado</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($espacios as $espacio)
                                <tr>
                                    <td>
                                        @include('partials.periodos_counter', [
                                            'frecuencia_medicion' => $evaluacion['frecuencia_medicion'],
                                            'index' => $loop->index + 1,
                                        ])
                                    </td>
                                    <td>
                                        <span class="text-pink-900">{{ $espacio['fecha'] }}</span>
                                    </td>

                                    <td>
                                        @include('partials.registro_capture', [
                                            'espacio' => $espacio,
                                        ])
                                    </td>
                                    <td>
                                        @include('partials.registro_status', [
                                            'espacio' => $espacio,
                                        ])
                                    </td>
                                    <td>
                                        @include('partials.registro_buttons', [
                                            'espacio' => $espacio,
                                        ])
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card card-outline card-danger m-2">
            <div class="card-header">
                <h6 class="card-title m-0 font-bold">
                    {{ $indicador['nombre'] }} <br>
                </h6>
            </div>
            <div class="card-body">
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
