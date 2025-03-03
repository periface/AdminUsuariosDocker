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

        canvas {
            width: 50% !important;
            height: 50% !important;
        }
    </style>
@endsection
@section('title')
    <h6 class="m-0 font-weight-bold">
                {{ $area['nombre'] }} - {{ $area['siglas'] }} - Responsable {{ $area['responsable'] }}
</h6>
@endsection
@section('content')
    <meta name="id" id="id" content="{{ $area['id'] }}">
    <meta name="token" id="token" content="{{ csrf_token() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="card shadow mb-4">
        <div class="card-header d-flex py-3">
            <h6 class="m-0 font-weight-bold w-full text-gray-800">
            Evaluaciones
            </h6>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-[60%_40%]">
                <div>

                    <div class="grid grid-cols-1">

                        <table class="table projects" id="evaluacionesTable">
                            <thead class="small">
                                <tr class="w-full">
                                    <th style="width: 10%" data-sort="id" data-order="asc" class="cursor-pointer sort">
                                        Estado
                                    </th>

                                    <th style="width: 20%" data-sort="id" data-order="asc" class="cursor-pointer sort">
                                        Indicador
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
                                            @if ($evaluacion->indicador)
                                                <td class="text-sm text-tam-rojo">
                                                    <a
                                                        href="{{ route('indicador.details', ['id' => $evaluacion->indicador['id']]) }}">
                                                        {{ $evaluacion->indicador['nombre'] }}
                                                    </a>

                                                </td>
                                            @else
                                                <td> Error: el indicador de esta evaluación no existe, por favor contacte al
                                                    administrador del
                                                    sistema.
                                                </td>
                                            @endif

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
                <div class="w-full">
                    <div class="w-3/4 m-auto">
                        <canvas id="radar-dimensiones"></canvas>
                        <canvas id="radar-categorias"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <!-- /.modal -->

    <!-- Contenedor para los toasts -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <!-- Aquí se inyectarán los toasts dinámicamente -->
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/js/areas/details.js'])
@endsection
