<div class="flex w-1/3 mb-2 border-black items">
    <input type="text" class="form-control form-control-sm js-search" placeholder="Buscar"
        {{ $search ? 'value=' . $search : '' }}>
</div>
<table class="table table-striped projects" id="evaluacionesTable">
    <thead class="small">
        <tr class="w-full">
            <th style="width: 10%" data-sort="id" data-order="asc" class="cursor-pointer sort">
                Estado
            </th>

            <th style="width: 20%" data-sort="id" data-order="asc" class="cursor-pointer sort">
                Indicador
            </th>
            <th style="width: 20%" data-sort="id" data-order="asc" class="cursor-pointer sort">
                Area
            </th>

            <th style="width: 10%" data-sort="id" data-order="asc" class="cursor-pointer sort">
                Total
            </th>
            <th style="width: 10%" data-sort="meta" data-order="asc" class="cursor-pointer sort">
                Meta
            </th>

            <th style="width: 10%" data-sort="meta" data-order="asc" class="cursor-pointer sort">
                Rendimiento
            </th>
            <th style="width: 10%" class="">
                Acciones
            </th>
        </tr>
    </thead>
    <tbody id="evaluacionesTableBody">
        @if (count($evaluaciones) === 0)
            <tr>
                <td colspan="6" class="text-center">No hay evaluaciones registrados</td>
            </tr>
        @else
            @foreach ($evaluaciones as $evaluacion)
                <tr>
                    <td class="text-sm text-center">
                            @if ($evaluacion['finalizado'] == 1 && $evaluacion['meta_alcanzada'] == 1)
                                <a href="#" class="btn btn-success btn-circle btn-sm">
                                    <i class="fas fa-check"></i>
                                </a><br>
                                <span class="text-sm text-tam-rojo">Finalizado</span>
                            @elseif ($evaluacion['finalizado'] == 1 && $evaluacion['meta_alcanzada'] == 0)
                                <a href="#" class="btn btn-warning btn-circle btn-sm">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </a><br>
                                <span class="text-sm text-tam-rojo">Finalizado</span>
                            @elseif ($evaluacion['finalizado'] == 0)
                                <a href="#" class="btn btn-info btn-circle btn-sm">
                                    <i class="fas fa-sync animate-spin"></i>
                                </a><br>
                                <span class="text-sm text-tam-rojo">En proceso</span>
                            @endif
                    </td>
                    @if ($evaluacion->indicador)
                        <td class="text-sm text-tam-rojo">
                            <a href="{{ route('indicador.details', ['id' => $evaluacion->indicador['id']]) }}">
                                {{ $evaluacion->indicador['nombre'] }}
                            </a>

                        </td>
                    @else
                        <td> Error: el indicador de esta evaluación no existe, por favor contacte al administrador del
                            sistema.
                        </td>
                    @endif

                    <td class="">
                        <span class="text-sm text-pink-950">{{ $evaluacion->area['nombre'] }}</span>
                    </td>
                    <td class="">
                        @include('partials.evaluacion_total', [
                            'evaluacion' => $evaluacion,
                        ])
                    </td>
                    <td class="">

                        @include('partials.evaluacion_meta', [
                            'evaluacion' => $evaluacion,
                        ])
                    </td>

                    <td class="">

                        @if ($evaluacion['rendimiento'] == null)
                            <span class="badge badge-danger">Sin rendimiento</span>
                        @elseif ($evaluacion['rendimiento'] < 0.7)
                            <span class="badge badge-danger">
                                {{ $evaluacion['rendimiento'] }}%
                            </span>
                        @elseif ($evaluacion['rendimiento'] < 0.85)
                            <span class="badge badge-warning">
                                {{ $evaluacion['rendimiento'] }}%
                            </span>
                        @else
                            <span class="badge badge-success">
                                {{ $evaluacion['rendimiento'] }}%
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm " role="group"
                            aria-label="Button group with nested dropdown">
                            <button type="button" class="btn btn-sm btn-inst3 js-view-registros"
                                data-id="{{ $evaluacion->id }}">Registros</button>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-inst3 dropdown-toggle btn-sm" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                </button>
                                <ul class="dropdown-menu">

                                    <li><a class="dropdown-item ficha cursor-pointer"
                                            href="{{ route('evaluacion.ficha', ['id' => $evaluacion->id]) }}"
                                            data-id="{{ $evaluacion->id }}">Ficha</a>
                                    </li>

                                    @if (Auth::user()->hasRole('ADM') || Auth::user()->hasRole('GDI') || Auth::user()->hasRole('SPA'))
                                        @if ($evaluacion['finalizado'])
                                            <li><a class="dropdown-item js-cerrar-evaluacion" href="#"
                                                    data-id="{{ $evaluacion->id }}">Abrir evaluación</a></li>
                                        @else
                                            <li><a class="dropdown-item js-cerrar-evaluacion" href="#"
                                                    data-id="{{ $evaluacion->id }}">Cerrar evaluación</a></li>
                                        @endif
                                        <li><a class="dropdown-item js-delete-evaluacion" href="#"
                                                data-id="{{ $evaluacion->id }}">Eliminar</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            <tr>

                <td colspan="3">
                    <div class="flex items-center justify-start">
                        <div class="w-1/2">
                            {{ $totalRows }} de {{ $grandTotalRows }} registros
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
                            <button {{ $i == $page ? 'disabled' : '' }} class="btn btn-sm js-change-page"
                                type="button" data-page="{{ $i }}">{{ $i }}</button>
                        @endfor
                    </div>
                </td>
            <tr>
        @endif

    </tbody>
</table>
