<div class="grid items-center justify-center grid-cols-1 md:grid-cols-1 content-evenly justify-items-center"
    style="
    grid-auto-columns: minmax(0, 1fr);
    ">
    <div class="p-2">
        <div class="p-2 rounded-lg shadow-md">
            <p class="m-0">Evaluando <span class="font-bold text-tam-rojo-fuerte">{{ $indicador['nombre'] }}</span> en
                <span class="font-bold text-tam-dorado-fuerte">{{ $area['nombre'] }}</span>
            </p>
        </div>
    </div>
</div>
<div class="grid grid-cols-2 justify-items-center">
    <div class="w-full col-span-2 p-2 config" id="evaluacion_config">
        <div class="grid grid-cols-3">

            <div class="mt-2 w-full">
                <div class="p-2">
                    <label class="form-label" for="periodicidad">Periodicidad</label>
                    @include('partials.periodos_select', [
                        'id' => 'periodicidad',
                        'name' => 'frecuencia_medicion',
                        'selected' => '',
                    ])
                </div>
            </div>
            <div class="mt-2 w-full">
                <div class="p-2">
                    <label class="form-label" for="fecha_inicio">Inicio de Evaluación</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio"
                        placeholder="Fecha de Inicio" />
                </div>
            </div>
            <div class="mt-2 w-full">
                <div class="p-2">
                    <label for="fecha_fin" class="form-label">Termino</label>
                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin"
                        placeholder="Fecha de Fin"/>
                </div>
            </div>
        </div>
        <div id="custom_date_error">
        </div>
        <div class="mt-2">
            @if ($indicador['unidad_medida'] == 'porcentaje')
                <div class="grid grid-cols-1 justify-center justify-items-center">
                    <div>

                        <label for="meta" class="form-label font-bold">Meta Esperada</label><br>
                    </div>
                    <div class="w-1/2">

                        <label for="meta" class="form-label w-full">{{ $indicador['nombre'] }}</label>
                        <div class="flex">
                            <span class="text-sm text-tam-rojo font-bold">0%</span>
                            <input type="range" class="form-range ml-1 mr-1" value="50" min="0"
                                max="100" id="meta" name="meta">
                            <span class="text-sm text-tam-rojo font-bold">100%</span>
                        </div>
                        <div class="text-center">
                            <span class="text-lg text-tam-rojo font-bold" id="total">50%</span>
                        </div>
                    </div>
                </div>
            @else
                <label for="meta" class="form-label">Meta Esperada</label>
                <input type="number" class="form-control" id="meta" name="meta" placeholder="100"
                    value="100" />
            @endif
        </div>
    </div>
    <div id="fechas_captura">
    </div>
</div>
