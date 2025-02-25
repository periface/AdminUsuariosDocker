@if ($espacio['finalizado'])
    <span class="text-tam-rojo-fuerte font-bold">Finalizada</span>
@else
    <div class="btn-group btn-group-sm" role="group" aria-label="Button group with nested dropdown">
        @if (new DateTime($espacio['fecha']) == new DateTime('today'))
            @if ($espacio['status'] == 'capturado')
                <button type="button" class="btn btn btn-inst3 btn-sm indicadorModalBtn">
                    <i class="fa fa-plus
                                "></i>
                    En validación
                </button>
            @elseif ($espacio['status'] == 'pendiente')
                <button data-id="{{ $espacio['evaluacionId'] }}" data-fecha="{{ $espacio['fecha'] }}" type="button"
                    class="js-registrar btn btn btn-inst3 btn-sm indicadorModalBtn">
                    Pendiente
                </button>
            @elseif ($espacio['status'] == 'aprobado')
                <button type="button" disabled class="btn btn btn-inst3 btn-sm indicadorModalBtn">
                    Aprobado
                </button>
            @elseif ($espacio['status'] == 'rechazado')
                <button type="button" data-id="{{ $espacio['evaluacionId'] }}" data-fecha="{{ $espacio['fecha'] }}"
                    class="btn js-registrar btn-inst3 btn-sm indicadorModalBtn">
                    Rechazado
                </button>
                <div class="btn-group" role="group">
                    <button type="button" class="btn indicadorModalBtn btn-inst3 btn-sm dropdown-toggle"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="caret"></span>
                    </button>

                    @if ($espacio['requiere_anexo'] == 1)
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item js-anexo cursor-pointer" type="button"
                                    data-id="{{ $espacio['id'] }}" data-espacio="{{ json_encode($espacio) }}">Medio de
                                    Verificación</a>
                            </li>
                        </ul>
                    @endif
                </div>
            @endif
        @else
            <div class="btn-group" role="group">
                @if ($espacio['status'] == 'capturado')
                    <button type="button" class="btn btn-inst3 btn-sm dropdown-toggle" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        En validación
                    </button>
                @elseif ($espacio['status'] == 'aprobado')
                    <button type="button" class="btn btn-sm btn-inst3 dropdown-toggle" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Aprobado
                    </button>
                @else
                    <button disabled type="button" class="btn btn-sm btn-inst3 dropdown-toggle"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        No disponible
                    </button>
                @endif

                @if ($espacio['requiere_anexo'] == 1)
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item js-anexo cursor-pointer" type="button"
                                data-id="{{ $espacio['id'] }}" data-espacio="{{ json_encode($espacio) }}">Medio de
                                Verificación</a>
                        </li>
                    </ul>
                @endif

            </div>
        @endif
    </div>
@endif
