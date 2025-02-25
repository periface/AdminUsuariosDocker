@if ($espacio['status'] == 'capturado')
    <h6 class="text-sm text-yellow-950">En validación</h6>
@endif
@if ($espacio['status'] == 'rechazado')
    <h6 class="text-sm text-red-950">
        Rechazado
        @if ($espacio['motivo'] != null)
                <i class="fa fa-info-circle text-red-950 cursor-pointer" data-bs-toggle="popover" data-bs-title="Comentarios"
                data-bs-content="{{$espacio['motivo']}} - {{$espacio['aprobadoPor']}}"
                title="{{ $espacio['motivo'] }}"></i>
        @endif

    </h6>
@endif
@if ($espacio['status'] == 'aprobado')
    <h6 class="text-sm text-green-950">Aprobado</h6>
@endif
@if ($espacio['status'] == 'pendiente')
    <h6 class="text-sm text-blue-950">Pendiente</h6>
@endif
