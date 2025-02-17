@if ($espacio['finalizado'])
    <span class="text-tam-rojo-fuerte font-bold">Finalizada</span>
@else
<div class="btn-group btn-group-sm" role="group" aria-label="Button group with nested dropdown">
    @if (new DateTime($espacio['fecha']) == new DateTime('today'))
        @if ($espacio['status'] == 'capturado')
            @if (Auth::user()->hasRole('ADM'))
                <button type="button" data-id="{{ $espacio['evaluacionId'] }}" data-fecha="{{ $espacio['fecha'] }}"
                    class="js-registrar btn btnSecondaryOficial btn-primary btn-sm indicadorModalBtn">
                    <i class="fa fa-plus
                                "></i>
                    Editar
                </button>
            @else
                <button type="button" class="btn btnSecondaryOficial btn-primary btn-sm indicadorModalBtn">
                    <i class="fa fa-plus
                                "></i>
                    En validación
                </button>
            @endif
        @else
            <button type="button" data-id="{{ $espacio['evaluacionId'] }}" data-fecha="{{ $espacio['fecha'] }}"
                class="js-registrar btn btnSecondaryOficial btn-primary btn-sm indicadorModalBtn">
                <i class="fa fa-plus
                                "></i>
                Registrar
            </button>
        @endif
    @else
        @if (auth()->user()->hasRole('ADM'))
            <button type="button" data-id="{{ $espacio['evaluacionId'] }}" data-fecha="{{ $espacio['fecha'] }}"
                class="js-registrar btn btnSecondaryOficial btn-primary btn-sm indicadorModalBtn">
                <i class="fa fa-plus
                                "></i>
                Editar
            </button>
        @endif
    @endif

    <div class="btn-group" role="group">
        <button type="button" class="btn btnSecondaryOficial btn-primary dropdown-toggle" data-bs-toggle="dropdown"
            aria-expanded="false">
        </button>
        <ul class="dropdown-menu">

            @if (Auth::user()->hasRole('ADM'))
                <li><a class="dropdown-item js-validar cursor-pointer" data-id="{{ $espacio['id'] }}"
                        data-espacio="{{ json_encode($espacio) }}">Validar</a>
                </li>
                <li><a class="dropdown-item js-rechazar cursor-pointer" data-id="{{ $espacio['id'] }}"
                        data-espacio="{{ json_encode($espacio) }}">Rechazar</a>
                </li>
            @endif

            @if ($espacio['requiere_anexo'] == 1)
                <li><a class="dropdown-item js-anexo cursor-pointer" type="button" data-id="{{ $espacio['id'] }}"
                        data-espacio="{{ json_encode($espacio) }}">Medio de Verificación</a>
                </li>
            @endif
        </ul>
    </div>
</div>
@endif
