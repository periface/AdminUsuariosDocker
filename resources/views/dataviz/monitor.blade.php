<div class="container mt-3">
    <div class="row">
        <div class="col-12">
            <nav class="navbar bg-body-tertiary bg-inst">
                <div class="col-6">
                    <div class="container-fluid">
                        <span class="navbar-text text-bold text-white">
                            <i class="fas fa-chart-line"></i> | Monitor de Indicadores
                        </span>
                      </div>
                </div>
                <div class="col-6 d-flex justify-content-end pe-3 pt-2">
                    <span class="mb-2 btn btn-sm add-area btn-inst2">
                        <i class="fas fa-file-export"></i> | Exportar reporte
                    </span>
                </div>
            </nav>
        </div>
    </div>
    <div class="row mt-2">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h2 class="font-bold">Objetivo</h2>
                    <p class="mt-2">
                        El objetivo del presente dashboard es presentar un análisis centrado en la eficiencia operativa, la gestión de recursos
                        y el cumplimiento de normas y la toma de deciones estratégicas.
                    </p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <h2 class="font-bold">Tipos de análisis propuestos</h2>
                    <hr class="mt-2">
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <label class=" ml-5 mt-2 text-gray-500 font-bold"> 1.- Medición de dimensiones por área</label>
                    <p class=" ml-7 text-gray-500">
                        Propuesta: Gráfica de radar <br>
                        Objetivo: Comparar el desempeño por área en cada una de las dimensiones. 
                    </p>
                    <label class=" ml-5 mt-2 text-gray-500 font-bold"> 2.- Análisis de desempeño interno por área</label>
                    <p class=" ml-7 text-gray-500">
                        Propuesta: Gráfica de barras apiladas <br>
                        Objetivo: Mostrar el desempeño interno de las dimensiones por cada área.
                    </p>
                </div>
                <div class="col-6">
                    <label class=" ml-5 mt-2 text-gray-500 font-bold"> 
                        3.- % Comparación de cumplimiento por área en un mismo
                        indicador 
                    </label>
                    <p class=" ml-7 text-gray-500">
                        Propuesta: Gráfica de barras agrupadas que permita ver que porcentaje de cumplimiento 
                        tiene cada área en un mismo indicador
                    </p>
                    <label class=" ml-5 mt-2 text-gray-500 font-bold"> 
                        4.- Detalle por área
                    </label>
                    <p class=" ml-7 text-gray-500">
                        Propuesta: Tabla con el detalle de cumplimiento por cada área, permitir elegir una área o indicador en especifico
                        y mostrar una página de detalle con esa área o indicador.
                    </p>
                </div>
                <div class="col">
                    <hr>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-6">
            <h1 class="text-center">
                Desempeño de Dimensiones por área
            </h1>
            <br>
            <hr>
            <div id="radarContainer" class="mt-2 p-3">
                <canvas id="radarChart"></canvas>
            </div>
        </div>
        <div class="col-6">
            <h1 class="text-center">
                Desempeño interno por área
            </h1>
            <br>
            <hr>
            <div id="stackedContainer" class="mt-2 p-3">
                <canvas id="stackedBar"></canvas>
            </div>
        </div>
    </div>
</div>
@section('scripts')
    <script src="https://github.com/chartjs/Chart.js/blob/master/docs/scripts/utils.js"></script>
@endsection