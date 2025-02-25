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
            height: 100% !important;
        }

        canvas#donut-chart {
            width: 100% !important;
            height: 100% !important;
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

    <div class="card shadow mb-2">
        <div class="card-header py3">
            <div class="flex align-middle items-center">
                <span class="navbar-text ">
                    Ficha de Evaluación:
                    <span class="text-tam-rojo-fuerte">{{ $area['nombre'] }}</span> con
                    <a href="{{ route('indicador.details', $indicador->id) }}"><span class="text-tam-dorado-fuerte">{{ $indicador['nombre'] }}</span>
                    </a>
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-3 text-md">


                <div class="p-3">
                    <p class="text-md m-0"><span class="text-tam-rojo-fuerte">Area: </span>{{ $area['nombre'] }}</p>
                    @if ($area['responsable'] != null)
                        <p class="m-0 text-md"><span class="text-tam-rojo-fuerte">Responsable: </span>{{ $area['responsable'] }}</p>
                    @else
                        <p class="m-0 text-md"><span class="text-tam-rojo-fuerte">Responsable:</span> No asignado</p>
                    @endif
                    <p class="m-0 text-md"><span class="text-tam-rojo-fuerte">Siglas:</span> {{ $area['siglas'] }}</p>
                </div>

                <div class="p-3">
                    <p><span class="text-tam-rojo-fuerte">Indicador: </span>{{ $indicador['nombre'] }}</p>
                    <p><span class="text-tam-rojo-fuerte">Descripción:</span> {{ $indicador['descripcion'] }}</p>
                </div>

                <div class="grid grid-cols-1 p-3">

                    <div class="text-center text-2xl">
                        <p>
                            <span class=" text-center text-tam-dorado-fuerte">Fecha de Inicio</span><br>
                            <span class="text-tam-rojo-fuerte">{{ $evaluacion['fecha_inicio'] }}</span>
                            <br>
                            <span class="text-center text-tam-dorado-fuerte">Fecha de Termino</span><br>
                            <span class="text-tam-rojo-fuerte">{{ $evaluacion['fecha_fin'] }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-3 mb-2">

        <div class="p-1 w-full">
            <div id="status" class="">
            </div>
        </div>
        <div class="p-1 w-full">
            <div id="total" class="hover:shadow-lg cursor-pointer js-show-details">
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="grid grid-cols-1 mt-3">
            <div>
                <canvas id="line-chart">
                </canvas>
            </div>
            <div class="grid grid-cols-1 hidden">

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
