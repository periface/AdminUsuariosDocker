<div class="hidden items w-1/3 mb-2 border-black">
    <input type="text" class="form-control form-control-sm js-search" placeholder="Buscar"
        {{ $search ? 'value=' . $search : '' }}>
</div>
<table class="table table-bordered table-striped table-hover" id="table-container">
    <thead>

        <tr>
            <th>Periodo</th>
            <th>Estado</th>
            <th>Resultado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($evaluacion_results as $espacio)
            <tr>
                <td>

                    <span class="text-green-900 text-xs">{{ $espacio['fecha'] }}</span>
                    <br>
                    @include('partials.periodos_counter', [
                        'frecuencia_medicion' => $frecuencia_medicion,
                        'index' => $loop->index + 1,
                    ])

                    @if ($espacio['requiere_anexo'] == 1)
                        <a href="#" target="_blank">
                            <span class="text-red-900 fa fa-paperclip text-sm ml-2"></span>
                        </a>
                    @endif
                    <br>
                    <span class="text-blue-900">{{ $espacio['days_left'] }}</span>
                </td>

                <td>
                    @include('partials.registro_status', [
                        'espacio' => $espacio,
                    ])
                </td>
                <td>
                    @include('partials.registro_capture', [
                        'espacio' => $espacio,
                    ])
                </td>
                <td>
                    @include('partials.registro_buttons', [
                        'espacio' => $espacio,
                    ])
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
