@if ($espacio['status'] == 'capturado')
    <h6>En validación</h6>
@endif
@if ($espacio['status'] == 'rechazado')
    <h6>Rechazado</h6>
@endif
@if ($espacio['status'] == 'aprobado')
    <h6>Aprobado</h6>
@endif
@if ($espacio['status'] == 'pendiente')
    <h6>Pendiente</h6>
@endif
