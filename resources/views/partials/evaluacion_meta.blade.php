@if ($evaluacion['sentido'] == 'ascendente')
    <span class="badge badge-success text-xs">
        {{ $evaluacion['meta'] }}
        <i class="fas fa-arrow-up"></i>
    </span>
@elseif ($evaluacion['sentido'] == 'descendente')
    <span class="badge badge-success text-xs">
        {{ $evaluacion['meta'] }}
        <i class="fas fa-arrow-down"></i>
    </span>
@elseif ($evaluacion['sentido'] == 'constante')
    <span class="badge badge-success text-xs">
        {{ $evaluacion['meta'] }}
        <i class="fas fa-equals"></i>
    </span>
@endif
