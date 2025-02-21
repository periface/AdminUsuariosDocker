@switch($frecuencia_medicion)
    @case('diaria')
        <span class="text-pink-900"> Día {{ $resultNumber }} </span>
    @break

    @case('semanal')
        <span class="text-pink-900"> Semana {{ $resultNumber }} </span>
    @break

    @case('quincenal')
        <span class="text-pink-900"> Quincena {{ $resultNumber }} </span>
    @break

    @case('mensual')
        <span class="text-pink-900"> Mes {{ $resultNumber }} </span>
    @break

    @case('bimestral')
        <span class="text-pink-900"> Bimestre {{ $resultNumber }}</span>
    @break

    @case('trimestral')
        <span class="text-pink-900"> Trimestre {{ $resultNumber }} </span>
    @break

    @case('semestral')
        <span class="text-pink-900"> Semestre {{ $resultNumber }} </span>
    @break

    @case('anual')
        <span class="text-pink-900"> Año {{ $resultNumber }} </span>
    @break
@endswitch
