<div class="btn-group btn-group-sm " role="group" aria-label="Button group with nested dropdown">
    @if (new DateTime($espacio['fecha']) == new DateTime('today'))
        @if ($espacio['status'] == 'capturado')
            <button type="button" data-id="{{ $espacio['evaluacionId'] }}" data-fecha="{{ $espacio['fecha'] }}"
                class="js-registrar btn btn-inst3 btn-sm indicadorModalBtn">
                <i class="fa fa-plus
                                "></i>
                Editar
            </button>
        @else
            <button type="button" data-id="{{ $espacio['evaluacionId'] }}" data-fecha="{{ $espacio['fecha'] }}"
                class="js-registrar btn btn-inst3 btn-sm indicadorModalBtn">
                <i class="fa fa-plus
                                "></i>
                Registrar
            </button>
        @endif
    @else
        <button type="button" data-id="{{ $espacio['evaluacionId'] }}" data-fecha="{{ $espacio['fecha'] }}"
            class="js-registrar btn btn-inst3 btn-sm indicadorModalBtn">
            <i class="fa fa-plus
                                "></i>
            Editar
        </button>
    @endif
    <div class="btn-group" role="group">

        @if ($espacio['status'] == 'pendiente')
            <button type="button" disabled class="btn btn-inst3 btn-sm dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">
            </button>
        @elseif (
            $espacio['status'] == 'capturado' ||
                $espacio['status'] == 'rechazado' ||
                $espacio['status'] == 'pendiente' ||
                $espacio['status'] == 'aprobado')
            <button type="button" class="btn btn-inst3 btn-sm dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">
            </button>
        @endif
        <ul class="dropdown-menu">
            @if ($espacio['status'] == 'capturado' || $espacio['status'] == 'rechazado' || $espacio['status'] == 'pendiente')
                <li><a class="dropdown-item js-aprobar cursor-pointer" data-id="{{ $espacio['id'] }}"
                        data-espacio="{{ json_encode($espacio) }}">Aprobar</a>
                </li>
            @endif
            @if ($espacio['status'] != 'rechazado')
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
