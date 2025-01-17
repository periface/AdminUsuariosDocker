<div class="grid items-center justify-center grid-cols-1 md:grid-cols-1 content-evenly justify-items-center"
    style="
    grid-auto-columns: minmax(0, 1fr);
    ">
    <div class="p-2">
        <div class="p-2 rounded-lg shadow-md">
            <p class="m-0">Evaluando <span class="font-bold text-pink-900">{{ $area['nombre'] }}</span> con <span
                    class="font-bold text-yellow-800">{{ $indicador['nombre'] }}</span></p>
        </div>
    </div>
</div>
<div class="grid grid-cols-2 justify-items-center">
    <div class="w-full col-span-2 p-2 config" id="evaluacion_config">
        <div class="mt-2">

            <label class="form-label" for="periodicidad">Periodicidad</label>
            @include('partials.periodos_select', [
                'id' => 'periodicidad',
                'name' => 'frecuencia_medicion',
                'selected' => '',
            ])
        </div>
        <div class="mt-2">
            <label class="form-label" for="fecha_inicio">Fecha de primer captura</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio"
                placeholder="Fecha de Inicio" />
        </div>
        <div class="mt-2">
            <label for="fecha_fin" class="form-label">Fecha de última captura</label>
            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" placeholder="Fecha de Fin/">
        </div>
        <div id="custom_date_error">
        </div>
        <div class="mt-2">
            <label for="meta" class="form-label">Meta</label>
            <input type="number" class="form-control" id="meta" name="meta" placeholder="100" value="100" />
        </div>
    </div>
    <div id="fechas_captura">
    </div>
</div>
