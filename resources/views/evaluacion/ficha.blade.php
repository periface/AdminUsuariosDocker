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

@section('title')
    <h6 class="m-0 font-weight-bold">FICHA DE EVALUACIÓN</h6>
@endsection
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <input type="hidden" id="areaId" value="{{ $area['id'] }}">
    <input type="hidden" id="indicadorId" value="{{ $indicador['id'] }}">
    <input type="hidden" id="evaluacionId" value="{{ $evaluacion['id'] }}">

    <div class="card shadow mb-4">
        <div class="card-header py3">
            <div class="flex align-middle items-center">
                <span class="navbar-text text-bold ">
                    Ficha de Evaluación:
                    <span class="font-bold">{{ $area['nombre'] }}</span> con
                    <span class="font-bold">{{ $indicador['nombre'] }}</span>
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-3">


                <div class="p-3">
                    <h6><span class="font-bold">Area: </span>{{ $area['nombre'] }}</h6>
                    @if ($area['responsable'] != null)
                        <p class="m-0"><span class="font-bold">Responsable: </span>{{ $area['responsable'] }}</p>
                    @else
                        <p class="m-0"><span class="font-bold">Responsable:</span> No asignado</p>
                    @endif
                    <p><span class="font-bold">Siglas:</span> {{ $area['siglas'] }}</p>
                </div>

                <div class="p-3">
                    <h6><span class="font-bold">Indicador: </span>{{ $indicador['nombre'] }}</h6>
                    <p><span class="font-bold">Descripción:</span> {{ $indicador['descripcion'] }}</p>
                </div>

                <div class="grid grid-cols-1 p-3">


                    <div class="text-center text-2xl">
                        <p>
                            <span class="font-bold text-center text-tam-dorado-fuerte">Fecha de Inicio</span><br>
                            <span class="font-bold text-tam-rojo-fuerte">{{ $evaluacion['fecha_inicio'] }}</span>
                            <br>
                            <span class="font-bold text-center text-tam-dorado-fuerte">Fecha de Termino</span><br>
                            <span class="font-bold text-tam-rojo-fuerte">{{ $evaluacion['fecha_fin'] }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-3 mb-4">

        <div class="p-1 w-full">
            <div id="status" class="">
            </div>
        </div>
        <div class="p-1 w-full">
            <div id="total" class="">
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="grid grid-cols-[70%_30%] mt-3">
            <div>
                <canvas id="line-chart">
                </canvas>
            </div>
            <div class="grid grid-cols-1">

                <canvas id="donut-chart">
                </canvas>
            </div>
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
