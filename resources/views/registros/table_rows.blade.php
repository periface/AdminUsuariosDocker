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

                    <span class="text-red-950 text-md font-bold">{{ $espacio['fecha'] }}</span>
                    <br>
                    <span class="text-2xl">
                        @include('partials.periodos_counter', [
                            'frecuencia_medicion' => $frecuencia_medicion,
                            'resultNumber' => $espacio['resultNumber'],
                        ])
                    </span>
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

                    @if (Auth::user()->hasRole('ADM')
                    || Auth::user()->hasRole('GDI')
                    || Auth::user()->hasRole('SPA')
                    )
                        @include('partials.registro_buttons_admin', [
                            'espacio' => $espacio,
                        ])
                    @elseif (Auth::user()->hasRole('REV'))
                        @include('partials.registro_buttons_rev', [
                            'espacio' => $espacio,
                        ])
                    @else
                        @include('partials.registro_buttons_rev', [
                            'espacio' => $espacio,
                        ])
                    @endif
                </td>
            </tr>
        @endforeach

        <tr>

            <td colspan="2">
                <div class="flex items-center justify-start">
                    <div class="w-1/2">
                        {{ count($evaluacion_results) }} de {{ $grandTotalRows }} registros
                    </div>
                    <div class="w-1/2 ml-3 justify-self-end">
                        <select class="form-control form-control-sm js-change-rows select">
                            <option value="5" {{ $limit == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ $limit == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ $limit == 20 ? 'selected' : '' }}>20</option>
                            <option value="50" {{ $limit == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $limit == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                </div>

            </td>
            <td colspan="4">

                <div class="flex items-center flex-nowrap">
                    @for ($i = 1; $i <= $totalPages; $i++)
                        @if ($i == 6 && $totalPages > 5)
                            <button class="btn btn-sm js-change-page" type="button"
                                data-page="{{ $i - 1 }}">...</button>
                            @continue
                        @endif
                        <button {{ $i == $page ? 'disabled' : '' }} class="btn btn-sm js-change-page" type="button"
                            data-page="{{ $i }}">{{ $i }}</button>
                    @endfor
                </div>
            </td>
        <tr>
    </tbody>
</table>
