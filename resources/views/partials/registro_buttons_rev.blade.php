@if ($espacio['finalizado'])
    <span class="text-tam-rojo-fuerte font-bold">Finalizada</span>
@else
    <div class="btn-group btn-group-sm" role="group" aria-label="Button group with nested dropdown">
        @if (new DateTime($espacio['fecha']) == new DateTime('today'))
            @if ($espacio['status'] == 'capturado')
                <button type="button" class="btn btnSecondaryOficial btn-primary btn-sm indicadorModalBtn">
                    <i class="fa fa-plus
                                "></i>
                    En validación
                </button>
            @else
                <button type="button" data-id="{{ $espacio['evaluacionId'] }}" data-fecha="{{ $espacio['fecha'] }}"
                    class="js-registrar btn btnSecondaryOficial btn-primary btn-sm indicadorModalBtn">
                    <i class="fa fa-plus
                                "></i>
                    Registrar
                </button>
            @endif
        @else
            <div class="btn-group" role="group">
                @if ($espacio['status'] == 'capturado')
                    <button type="button" class="btn btnSecondaryOficial btn-primary dropdown-toggle"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        En validación
                    </button>
                @elseif ($espacio['status'] == 'aprobado')
                    <button type="button" class="btn btnSecondaryOficial btn-success dropdown-toggle"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Aprobado
                    </button>
                @else
                    <button disabled type="button" class="btn btnSecondaryOficial btn-warning dropdown-toggle"
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
