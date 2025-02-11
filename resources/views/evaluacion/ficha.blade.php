@extends('layout.app')
@section('estilos')
    <style>
        .error {
            color: red;
            font-weight: normal !important;
            font-size: 14px;
        }
    </style>

    @vite(['node_modules/bs-stepper/dist/css/bs-stepper.min.css'])
@endsection
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

    <div class="row mt-3">
        <div class="col-6">

            <div class="p-3 bg-white rounded-md shadow-md">

                <h1><span class="font-bold">Area: </span>{{ $area['nombre'] }}</h1>
                @if ($area['responsable'] != null)
                    <p><span class="font-bold">Responsable: </span>{{ $area['responsable'] }}</p>
                @else
                    <p><span class="font-bold">Responsable:</span> No asignado</p>
                @endif
                <p><span class="font-bold">Siglas:</span> {{ $area['siglas'] }}</p>
            </div>
        </div>

        <div class="col-6">

            <div class="p-3 bg-white rounded-md shadow-md">
                <h1><span class="font-bold">Indicador: </span>{{ $indicador['nombre'] }}</h1>
                <p><span class="font-bold">Descripción:</span> {{ $indicador['descripcion'] }}</p>
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
