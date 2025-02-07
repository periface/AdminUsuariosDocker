@switch($espacio["status"])
    @case ('capturado')
        <span class="text-blue-900 font-bold" title="{{ $espacio['used_formula'] }}">{{ $espacio['resultado'] }}%</span>
        <br>
    @break

    @case ('rechazado')
        <span class="text-yellow-900 font-bold" title="{{ $espacio['used_formula'] }}">{{ $espacio['resultado'] }}%</span>
    @break

    @case ('aprobado')
        <span class="text-green-900 font-bold" title="{{ $espacio['used_formula'] }}">{{ $espacio['resultado'] }}%</span>
    @break

    @default
        <span class="text-pink-900 font-bold">Sin captura</span>
@endswitch
