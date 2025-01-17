@switch($frecuencia_medicion)
    @case('diaria')
        <span class="text-teal-900"> Días </span>
    @break

    @case('semanal')
        <span class=" text-teal-900"> Semanas </span>
    @break

    @case('quincenal')
        <span class="text-teal-900"> Quincenas </span>
    @break

    @case('mensual')
        <span class="text-teal-900"> Meses </span>
    @break

    @case('bimestral')
        <span class="text-teal-900"> Bimestres </span>
    @break

    @case('trimestral')
        <span class="text-teal-900"> Trimestres </span>
    @break

    @case('semestral')
        <span class="text-teal-900"> Semestres </span>
    @break

    @case('anual')
        <span class="text-teal-900"> Años </span>
    @break
@endswitch
