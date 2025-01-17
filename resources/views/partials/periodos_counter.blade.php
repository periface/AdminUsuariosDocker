@switch($frecuencia_medicion)
    @case('diaria')
        <span class="text-pink-900"> Día {{ $index }} </span>
    @break

    @case('semanal')
        <span class="text-pink-900"> Semana {{ $index }} </span>
    @break

    @case('quincenal')
        <span class="text-pink-900"> Quincena {{ $index }} </span>
    @break

    @case('mensual')
        <span class="text-pink-900"> Mes {{ $index }} </span>
    @break

    @case('bimestral')
        <span class="text-pink-900"> Bimestre {{ $index }}</span>
    @break

    @case('trimestral')
        <span class="text-pink-900"> Trimestre {{ $index }} </span>
    @break

    @case('semestral')
        <span class="text-pink-900"> Semestre {{ $index }} </span>
    @break

    @case('anual')
        <span class="text-pink-900"> Año {{ $index }} </span>
    @break
@endswitch
