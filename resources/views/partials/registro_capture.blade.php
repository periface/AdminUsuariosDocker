@switch($espacio["status"])
    @case ('capturado')
        <span class="text-blue-900 text-sm" title="{{ $espacio['used_formula'] }}">{{ $espacio['value'] }}</span>
        <br>
    @break
    @case ('rechazado')
        <span class="text-yellow-900 text-sm" title="{{ $espacio['used_formula'] }}">{{ $espacio['value'] }}</span>
    @break

    @case ('aprobado')
        <span class="text-green-900 text-sm" title="{{ $espacio['used_formula'] }}">{{ $espacio['value'] }}</span>
    @break

    @default
        <span class="text-pink-900 text-sm">Sin captura</span>
@endswitch
