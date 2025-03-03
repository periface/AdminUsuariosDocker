@extends('layout.app')
@section('styles')
    <style>
        .error {
            color: red;
            font-weight: normal !important;
            font-size: 14px;
        }

        .form-switch {
            padding-left: 2.5em !important;
        }
    </style>
@endsection
@section('title')
    <h6 class="m-0 font-weight-bold">DETALLES DEL INDICADOR</h6>
@endsection
@section('content')
    <meta name="token" id="token" content="{{ csrf_token() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="card shadow mb-4">

        <div class="card-header d-flex py-3">
            <h6 class="m-0 font-weight-bold w-full text-gray-800">
                {{ $indicador['nombre'] }}
            </h6>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-[40%_60%]">

                <div class="p-3">

                    <p class="mt-2">
                        <span class="font-bold">Categoría: </span>{{ $indicador['categoria'] }}
                    </p>
                    <p class="mt-2">
                        <span class="font-bold">Dimensión: </span>{{ $dimension['nombre'] }}
                    </p>

                    <p class="mt-2">
                        <span class="font-bold">Sentido: </span>{{ $indicador['sentido'] }}
                    </p>
                </div>
                <div class="p-3">
                    <p class="text-justify mt-2">
                        <span class="font-bold">Descripción: </span>{{ $indicador['descripcion'] }}
                    </p>

                    <p class="text-justify mt-2">
                        <span class="font-bold">Método de Cálculo: </span>{{ $indicador['metodo_calculo'] }}
                    </p>

                    <p class="text-justify mt-2">
                        <span class="font-bold">Medio de Verificación: </span>{{ $indicador['medio_verificacion'] }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">

        <div class="card-header d-flex py-3">
            <h6 class="m-0 font-weight-bold w-full text-gray-800">
                Evaluaciones del Indicador
            </h6>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1">
                <table class="table table-striped projects" id="evaluacionesTable">
                    <thead class="small">
                        <tr class="w-full">
                            <th style="width: 10%" data-sort="id" data-order="asc" class="cursor-pointer sort">
                                Estado
                            </th>

                            <th style="width: 20%" data-sort="id" data-order="asc" class="cursor-pointer sort">
                                Area
                            </th>

                            <th style="width: 10%" data-sort="id" data-order="asc" class="cursor-pointer sort">
                                Total
                            </th>
                            <th style="width: 10%" data-sort="meta" data-order="asc" class="cursor-pointer sort">
                                Meta
                            </th>

                            <th style="width: 10%" data-sort="meta" data-order="asc" class="cursor-pointer sort">
                                Rendimiento
                            </th>
                        </tr>
                    </thead>
                    <tbody id="evaluacionesTableBody">
                        @if (count($evaluaciones) === 0)
                            <tr>
                                <td colspan="6" class="text-center">No hay evaluaciones registrados</td>
                            </tr>
                        @else
                            @foreach ($evaluaciones as $evaluacion)
                                <tr>
                                    <td class="text-sm text-center">
                                        @if ($evaluacion['finalizado'] == 1 && $evaluacion['meta_alcanzada'] == 1)
                                            <a href="#" class="btn btn-success btn-circle btn-sm">
                                                <i class="fas fa-check"></i>
                                            </a><br>
                                            <span class="text-sm text-tam-rojo">Finalizado</span>
                                        @elseif ($evaluacion['finalizado'] == 1 && $evaluacion['meta_alcanzada'] == 0)
                                            <a href="#" class="btn btn-warning btn-circle btn-sm">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </a><br>
                                            <span class="text-sm text-tam-rojo">Finalizado</span>
                                        @elseif ($evaluacion['finalizado'] == 0)
                                            <a href="#" class="btn btn-info btn-circle btn-sm">
                                                <i class="fas fa-sync animate-spin"></i>
                                            </a><br>
                                            <span class="text-sm text-tam-rojo">En proceso</span>
                                        @endif
                                    </td>

                                    <td class="">
                        <a href="/areas/details/{{$evaluacion->area["id"]}}">
                            {{ $evaluacion->area['nombre'] }}
                        </a>
                                    </td>
                                    <td class="">
                                        @include('partials.evaluacion_total', [
                                            'evaluacion' => $evaluacion,
                                        ])
                                    </td>
                                    <td class="">

                                        @include('partials.evaluacion_meta', [
                                            'evaluacion' => $evaluacion,
                                        ])
                                    </td>

                                    <td class="">

                                        @if ($evaluacion['rendimiento'] == null)
                                            <span class="badge badge-danger">Sin rendimiento</span>
                                        @elseif ($evaluacion['rendimiento'] < 0.7)
                                            <span class="badge badge-danger">
                                                {{ $evaluacion['rendimiento'] }}%
                                            </span>
                                        @elseif ($evaluacion['rendimiento'] < 0.85)
                                            <span class="badge badge-warning">
                                                {{ $evaluacion['rendimiento'] }}%
                                            </span>
                                        @else
                                            <span class="badge badge-success">
                                                {{ $evaluacion['rendimiento'] }}%
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
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
                            <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary btn-sm"
                                id="js-guardar-indicador">Guardar</button>
                            <button type="button" class="btn btn-success btn-sm" id="js-confirm-indicador">Guardar y
                                confirmar</button>
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
    @vite(['resources/js/indicadores/details.js'])
@endsection
