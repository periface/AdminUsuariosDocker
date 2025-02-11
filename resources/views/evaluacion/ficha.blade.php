@extends('layout.app')
@section('estilos')
    <style>
        .error {
            color: red;
            font-weight: normal !important;
            font-size: 14px;
        }

        canvas#line-chart {
            width: 100% !important;
            height: 50vh !important;
        }

        canvas#donut-chart {
            width: 100% !important;
            height: 50vh !important;
            margin: 0 auto;
        }
    </style>

    @vite(['node_modules/bs-stepper/dist/css/bs-stepper.min.css'])
@endsection
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <input type="hidden" id="areaId" value="{{ $area['id'] }}">
    <input type="hidden" id="indicadorId" value="{{ $indicador['id'] }}">
    <input type="hidden" id="evaluacionId" value="{{ $evaluacion['id'] }}">

    <div class="row">
        <div class="col-12">
            <nav class="navbar bg-body-tertiary bg-inst">
                <div class="col-12">
                    <div class="container-fluid">
                        <span class="navbar-text text-bold text-white">
                            <i class="fa-solid fa-users-gear"></i> | Ficha de Evaluación:
                            <span class="font-bold">{{ $area['nombre'] }}</span> con
                            <span class="font-bold">{{ $indicador['nombre'] }}</span>
                        </span>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    <div class="row mt-3 bg-white rounded-md shadow-md">

        <div class="col-4">
            <div class="grid grid-cols-1 p-3">
                <div class="grid grid-cols-1">
                    <div id="status" class="w-full">
                    </div>
                    <div id="total" class="w-full">
                    </div>
                </div>
                <hr>
                <div>
                    <p><span class="font-bold">De:</span> {{ $evaluacion['fecha_inicio'] }} a {{ $evaluacion['fecha_fin'] }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="p-3">
                <h1><span class="font-bold">Area: </span>{{ $area['nombre'] }}</h1>
                @if ($area['responsable'] != null)
                    <p><span class="font-bold">Responsable: </span>{{ $area['responsable'] }}</p>
                @else
                    <p><span class="font-bold">Responsable:</span> No asignado</p>
                @endif
                <p><span class="font-bold">Siglas:</span> {{ $area['siglas'] }}</p>
            </div>
        </div>

        <div class="col-4">
            <div class="p-3">
                <h1><span class="font-bold">Indicador: </span>{{ $indicador['nombre'] }}</h1>
                <p><span class="font-bold">Descripción:</span> {{ $indicador['descripcion'] }}</p>
            </div>
        </div>


    </div>
    <div class="grid grid-cols-[30%_70%] mt-3">
        <div class="grid grid-cols-1">

            <canvas id="donut-chart">
            </canvas>
        </div>
        <div>
            <canvas id="line-chart">
            </canvas>
        </div>
    </div>
    <!-- Contenedor para los toasts -->
    <div class="top-0 p-3 toast-container position-fixed end-0">
        <!-- Aquí se inyectarán los toasts dinámicamente -->
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/js/evaluacion/ficha.js'])
@endsection
