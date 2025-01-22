<div class="flex items w-1/3 mb-2 border-black hidden">
    <input type="text" class="form-control form-control-sm js-search" placeholder="Buscar"
        {{ $search ? 'value=' . $search : '' }}>
</div>
<table class="table table-bordered table-striped table-hover" id="table-container">
    <thead>

        <tr>
            <th>Periodo</th>
            <th>Fecha</th>
            <th>Resultado</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($evaluacion_results as $espacio)
            <tr>
                <td>
                    @include('partials.periodos_counter', [
                        'frecuencia_medicion' => $frecuencia_medicion,
                        'index' => $loop->index + 1,
                    ])
                </td>
                <td>
                    <span class="text-pink-900">{{ $espacio['fecha'] }}</span>
                </td>

                <td>
                    @include('partials.registro_capture', [
                        'espacio' => $espacio,
                    ])
                </td>
                <td>
                    @include('partials.registro_status', [
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
