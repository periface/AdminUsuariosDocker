@switch($unidad_medida)
    @case('porcentaje')
        <span class="badge bg-info text-white">Porcentaje</span>
    @break

    @case('numero')
        <span class="badge bg-info text-white">Número</span>
    @break

    @case('moneda')
        <span class="badge bg-info text-white">Moneda</span>
    @break

    @case('unidad')
        <span class="badge bg-info text-white">Unidad</span>
    @break

    @case('dias')
        <span class="badge bg-info text-white">Días</span>
    @break
    @default
        <span class="badge bg-info text-white">Sin definir</span>
@endswitch
