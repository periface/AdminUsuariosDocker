            @if ($indicador && $indicador->id)
                <input type="hidden" name="id" value="{{ $indicador->id }}">
                <input class="form-control" type="hidden" id="metodo_calculo" name="metodo_calculo"
                    value="{{ $indicador['metodo_calculo'] ?? '' }}">
                <input type="hidden" name="evaluable_formula" value="{{ $indicador['evaluable_formula'] }}">
                <input type="hidden" name="non_evaluable_formula" value="{{ $indicador['non_evaluable_formula'] }}">
                <input type="hidden" id="indicador_confirmado" name="indicador_confirmado"
                    value="{{ $indicador['indicador_confirmado'] }}">
                <input type="hidden" id="clave" name="clave" value="{{ $indicador['clave'] }}">
            @endif
            @if (!$indicador || !$indicador->id)
                <input type="hidden" name="metodo_calculo" value="">
                <input type="hidden" name="evaluable_formula" value="sin método de cálculo">
                <input type="hidden" name="non_evaluable_formula" value="sin método de cálculo">
                <input type="hidden" id="clave" name="clave" value="__indicador__">
            @endif
            <div class="mt-2">
                <label for="dimension" class="form-label text-sm">Dimensión: </label>
                <select class="form-select" id="dimensionId" name="dimensionId">
                    <option value="">Seleccione una dimensión</option>
                    @foreach ($dimensiones as $dimension)
                        @if ($indicador && $indicador->id)
                            <option value="{{ $dimension['id'] }}"
                                {{ $indicador['dimensionId'] == $dimension['id'] ? 'selected' : '' }}>
                                {{ $dimension['nombre'] }}</option>
                        @elseif ($dimension['id'] == $dimensionId)
                            <option value="{{ $dimension['id'] }}" selected>{{ $dimension['nombre'] }}</option>
                        @else
                            <option value="{{ $dimension['id'] }}">{{ $dimension['nombre'] }}</option>
                        @endif
                    @endforeach
                </select>
                <div class="mt-2">
                    <label for="categoria" class="form-label text-sm">Categoría: </label>
                    <select class="form-select" id="categoriaId" name="categoriaId">
                        <option value="">Seleccione una categoría</option>
                        @foreach ($categorias as $categoria)
                            @if ($indicador && $indicador->id)
                                <option value="{{ $categoria['id'] }}"
                                    {{ $indicador['categoriaId'] == $categoria['id'] ? 'selected' : '' }}>
                                    {{ $categoria['nombre'] }}</option>
                            @else
                                <option value="{{ $categoria['id'] }}">{{ $categoria['nombre'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="mt-2">
                    <label for="nombre" class="form-label text-sm">Nombre: </label>
                    <input class="form-control" id="nombre" name="nombre" placeholder="Nombre del indicador"
                        value="{{ $indicador['nombre'] ?? '' }}">
                </div>

                <div class="mt-2">
                    <label for="descripcion" class="form-label text-sm">Descripción: </label>
                    <input class="form-control" id="descripcion" name="descripcion" placeholder="Descripción"
                        value="{{ $indicador['descripcion'] ?? '' }}">
                </div>
                <div class="mt-2">
                    <label for="unidad_medida" class="form-label text-sm">Unidad de Medida: </label>
                    <select class="form-select" id="unidad_medida" name="unidad_medida">
                        @if ($indicador && $indicador->id)
                            <option value="porcentaje"
                                {{ $indicador['unidad_medida'] == 'porcentaje' ? 'selected' : '' }}>
                                Porcentaje</option>
                            <option value="numero" {{ $indicador['unidad_medida'] == 'numero' ? 'selected' : '' }}>
                                Número
                            </option>
                            <option value="moneda" {{ $indicador['unidad_medida'] == 'moneda' ? 'selected' : '' }}>
                                Moneda
                            </option>
                        @else
                            <option value="porcentaje">Porcentaje</option>
                            <option value="numero">Número</option>
                            <option value="moneda">Moneda</option>
                        @endif
                    </select>
                </div>

                <div class="mt-2">
                    <label for="sentido" class="form-label text-sm">Sentido: </label>
                    <select class="form-select" id="sentido" name="sentido">
                        @if ($indicador && $indicador->id)
                            <option value="ascendente" {{ $indicador['sentido'] == 'ascendente' ? 'selected' : '' }}>
                                Ascendente</option>
                            <option value="descendente" {{ $indicador['sentido'] == 'descendente' ? 'selected' : '' }}>
                                Descendente</option>
                            <option value="constante" {{ $indicador['sentido'] == 'constante' ? 'selected' : '' }}>
                                Constante</option>
                        @else
                            <option value="ascendente">Ascendente</option>
                            <option value="descendente">Descendente</option>
                            <option value="constante">Constante</option>
                        @endif
                    </select>
                </div>
                @if ($indicador && $indicador->id)
                    <div class="mt-2">
                        <label for="medio_verificacion" class="form-label text-sm">Medio de verificación: </label>
                        <input class="form-control" id="medio_verificacion" name="medio_verificacion"
                            placeholder="Ej. Encuesta" value="{{ $indicador['medio_verificacion'] ?? '' }}">
                    </div>
                @else
                    <div class="mt-2">
                        <label for="medio_verificacion" class="form-label text-sm">Medio de verificación: </label>
                        <input class="form-control" id="medio_verificacion" name="medio_verificacion"
                            placeholder="Ej. Encuesta" value="" />
                    </div>
                @endif
                <div class="mt-2">
                    <div class="form-check form-switch">
                        @if ($indicador && $indicador->id)
                            <input class="form-check-input" type="checkbox" role="switch" id="status"
                                name="status" {{ $indicador['status'] == 1 ? 'checked' : '' }} />
                        @else
                            <input class="form-check-input" type="checkbox" role="switch" id="status"
                                name="status" />
                        @endif
                        <label class="form-check-label" for="status">Activo</label>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="form-check form-switch">
                        @if ($indicador && $indicador->id)
                            <input class="form-check-input" type="checkbox" role="switch" id="requiere_anexo"
                                name="requiere_anexo" {{ $indicador['requiere_anexo'] == 1 ? 'checked' : '' }} />
                        @else
                            <input class="form-check-input" type="checkbox" role="switch" id="requiere_anexo"
                                name="requiere_anexo" />
                        @endif
                        <label class="form-check-label" for="requiere_anexo">Requiere Anexo</label>
                    </div>
                </div>
