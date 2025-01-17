<div class="bs-stepper">
    <div class="bs-stepper-header" role="tablist">
        <!-- your steps here -->
        <div class="step" data-target="#asignacion-part">
            <button type="button" class="step-trigger" role="tab" aria-controls="asignacion-part"
                id="asignacion-part-trigger">
                <span class="bs-stepper-circle">1</span>
                <span class="bs-stepper-label">Seleccion de area/indicador</span>
            </button>
        </div>
        <div class="line"></div>
        <div class="step" data-target="#meta-part">
            <button type="button" class="step-trigger" role="tab" aria-controls="meta-part" id="meta-part-trigger">
                <span class="bs-stepper-circle">2</span>
                <span class="bs-stepper-label">Configurar meta</span>
            </button>
        </div>

        <div class="line"></div>
        <div class="step" data-target="#resultados-part">
            <button type="button" class="step-trigger" role="tab" aria-controls="resultados-part"
                id="resultados-part-trigger">
                <span class="bs-stepper-circle">3</span>
                <span class="bs-stepper-label">Fechas de captura</span>
            </button>
        </div>
    </div>
    <div class="bs-stepper-content">
        <!-- your steps content here -->
        <div id="asignacion-part" class="content" role="tabpanel" aria-labelledby="asignacion-part-trigger">

            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="p-2 mt-2">
                    <select class="form-select" id="areaId" name="areaId">
                        <option value="">Seleccione un área</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}" data-json="{{ $area }}">{{ $area['nombre'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="p-2 mt-2">
                    <select class="form-select" id="indicadorId" name="indicadorId">
                        <option value="">Seleccione un indicador</option>
                        @foreach ($dimensiones as $dimension)
                            @if (count($dimension['indicadores']) == 0)
                                @continue
                            @endif
                            <optgroup label="{{ $dimension['nombre'] }}">
                                @foreach ($dimension['indicadores'] as $indicador)
                                    @if ($indicador['indicador_confirmado'] == 0)
                                        <option disabled value="{{ $indicador->id }}" data-json="{{ $indicador }}">
                                            {{ $indicador['nombre'] }} </option>
                                    @else
                                        <option value="{{ $indicador->id }}" data-json="{{ $indicador }}">
                                            {{ $indicador['nombre'] }} </option>
                                    @endif
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div id="asignacion-details" class="p-2 mt-2 md:col-span-2">
                </div>
            </div>
        </div>
        <div id="meta-part" class="content" role="tabpanel" aria-labelledby="meta-part-trigger">
            <div class="grid grid-cols-1">
                <div class="" id="evaluacion_details">
                </div>
            </div>
        </div>
        <div id="resultados-part" class="content" role="tabpanel" aria-labelledby="meta-part-trigger">
            <h2>Fechas de captura</h2>
            <div id="capturas-table">
            </div>
        </div>
    </div>
</div>
