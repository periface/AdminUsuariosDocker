@if ($evaluacion['sentido'] == 'ascendente' && $evaluacion['totalValue'] < $evaluacion['metaValue'])
    <span data-id="{{ $evaluacion['id'] }}"class="badge badge-danger text-xs cursor-pointer js-backdrop"
        data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop">
        {{ $evaluacion['total'] }}
        <i class="fas fa-arrow-down"></i>
    </span>
@elseif ($evaluacion['sentido'] == 'ascendente' && $evaluacion['totalValue'] >= $evaluacion['metaValue'])
    <span data-id="{{ $evaluacion['id'] }}" class="badge badge-success text-xs cursor-pointer js-backdrop"
        data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop">
        {{ $evaluacion['total'] }}
        <i class="fas fa-arrow-up"></i>
    </span>
@elseif ($evaluacion['sentido'] == 'descendente' && $evaluacion['totalValue'] > $evaluacion['metaValue'])
    <span data-id="{{ $evaluacion['id'] }}" class="badge badge-danger text-xs cursor-pointer js-backdrop"
        data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop">
        {{ $evaluacion['total'] }}
        <i class="fas fa-arrow-up"></i>
    </span>
@elseif ($evaluacion['sentido'] == 'descendente' && $evaluacion['totalValue'] <= $evaluacion['metaValue'])
    <span data-id="{{ $evaluacion['id'] }}" class="badge badge-success text-xs cursor-pointer js-backdrop"
        data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop">
        {{ $evaluacion['total'] }}
        <i class="fas fa-arrow-down"></i>
    </span>
@elseif ($evaluacion['sentido'] == 'constante' && $evaluacion['totalValue'] == $evaluacion['metaValue'])
    <span data-id="{{ $evaluacion['id'] }}" class="badge badge-success text-xs cursor-pointer js-backdrop"
        data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop">
        {{ $evaluacion['total'] }}
        <i class="fas fa-equals"></i>
    </span>
@elseif ($evaluacion['sentido'] == 'constante' && $evaluacion['totalValue'] != $evaluacion['metaValue'])
    <span data-id="{{ $evaluacion['id'] }}" class="badge badge-danger text-xs cursor-pointer js-backdrop"
        data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop">
        {{ $evaluacion['total'] }}

        <i class="fas fa-equals"></i>
    </span>
@endif
