@switch($frecuencia_medicion)
    @case('diaria')
        <span class="text-pink-900 font-bold"> Día {{ $index }} </span>
    @break

    @case('semanal')
        <span class="text-pink-900 font-bold"> Semana {{ $index }} </span>
    @break

    @case('quincenal')
        <span class="text-pink-900 font-bold"> Quincena {{ $index }} </span>
    @break

    @case('mensual')
        <span class="text-pink-900 font-bold"> Mes {{ $index }} </span>
    @break

    @case('bimestral')
        <span class="text-pink-900 font-bold"> Bimestre {{ $index }}</span>
    @break

    @case('trimestral')
        <span class="text-pink-900 font-bold"> Trimestre {{ $index }} </span>
    @break

    @case('semestral')
        <span class="text-pink-900 font-bold"> Semestre {{ $index }} </span>
    @break

    @case('anual')
        <span class="text-pink-900 font-bold"> Año {{ $index }} </span>
    @break
@endswitch
