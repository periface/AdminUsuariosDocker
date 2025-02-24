@if ($espacio['status'] == 'capturado')
    <h6 class="text-sm text-yellow-950">En validación</h6>
@endif
@if ($espacio['status'] == 'rechazado')
    <h6 class="text-sm text-red-950">Rechazado</h6>
@endif
@if ($espacio['status'] == 'aprobado')
    <h6 class="text-sm text-green-950">Aprobado</h6>
@endif
@if ($espacio['status'] == 'pendiente')
    <h6 class="text-sm text-blue-950">Pendiente</h6>
@endif
