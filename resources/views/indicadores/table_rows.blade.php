<div class="flex items w-1/3 mb-2 border-black">
    <input type="text" class="form-control form-control-sm js-search" placeholder="Buscar"
        {{ $search ? 'value=' . $search : '' }}>
</div>
<table class="table table-sm table-striped projects" id="indicadoresTable">
    <thead class="small thead">
        <tr class="w-full text-sm">

            <th style="width: 30%" data-sort="nombre" data-order="asc" class="sort text-sm cursor-pointer">
                Nombre <i class="fas fa-sort pl-2"></i>
            </th>

            <th style="width: 15%" data-sort="dimension" data-order="asc" class="sort text-sm cursor-pointer">
                Dimension <i class="fas fa-sort pl-2"></i>
            </th>
            <th style="width: 10%" data-sort="categoria" data-order="asc" class="sort cursor-pointer">
                Categoría <i class="fas fa-sort pl-2"></i>
            </th>
            <th style="width: 10%" data-sort="sentido" data-order="asc" class="sort cursor-pointer">
                Sentido <i class="fas fa-sort pl-2"></i>
            </th>
            <th style="width: 20%" data-sort="metodo_calculo" data-order="asc" class="sort cursor-pointer">
                Método de Cálculo <i class="fas fa-sort pl-2[a"></i>
            </th>
            <th style="width: 10%" data-sort="status" data-order="asc" class="sort cursor-pointer">
                Status <i class="fas fa-sort pl-2"></i>
            </th>
            <th style="width: 100%" class="flex align-middle">
                Acciones
            </th>
        </tr>
    </thead>
    <tbody id="indicadoresTableBody">
        @if (count($indicadores) === 0)
            <tr>
                <td colspan="6" class="text-center">No hay indicadores registrados</td>
            </tr>
        @else
            @foreach ($indicadores as $indicador)
                <tr>
                    <td class="text-sm">{{ $indicador['nombre'] }}

                    </td>

                    <td class="text-sm">{{ $indicador['dimension'] }}</td>
                    <td class="text-sm">{{ $indicador['categoria'] }}</td>
                    <td class="text-center text-sm">
                        @if ($indicador['sentido'] == 'ascendente')
                            <span class="text-xs badge badge-success text-tam-rojo-fuerte font-semibold">
                                Ascendente <i class="fas fa-arrow-up"></i>
                            </span>
                        @elseif ($indicador['sentido'] == 'descendente')
                            <span class="text-xs badge badge-primary text-tam-dorado-fuerte font-semibold">
                                Descendente <i class="fas fa-arrow-down"></i>
                            </span>
                        @else
                            <span class="text-xs badge badge-secondary text-teal-600 font-semibold">
                                Constante <i class="fas fa-arrows-alt-h"></i>
                            </span>
                        @endif
                    </td>
                    <td class="text-sm">
                        @if ($indicador['indicador_confirmado'])
                            <span class="badge badge-success">
                                <i class="fas fa-check"></i>

                        <a href="#" class="js-set-formula text-white" data-id="{{ $indicador->id }}"
                            title="{{ $indicador['metodo_calculo'] }}">{{ $indicador['non_evaluable_formula'] }}
                            <a>
                            </span>
                        @else
                            <span class="badge badge-danger">
                                <i class="fas fa-hourglass-half"></i>

                        <a href="#" class="js-set-formula text-white" data-id="{{ $indicador->id }}"
                            title="{{ $indicador['metodo_calculo'] }}">{{ $indicador['non_evaluable_formula'] }}
                            <a>
                            </span>
                        @endif
                    </td>
                    <td class="text-sm">
                        @if ($indicador['status'] === 1)
                            <span class="badge badge-success">Activo</span>
                        @else
                            <span class="badge badge-danger">Inactivo</span>
                        @endif
                    </td>
                    <td class="text-sm">
                        <div class="btn-group btn-group-sm" role="group"
                            aria-label="Button group with nested dropdown">
                            <button type="button" class="btn btn-inst3 btn-sm indicadorModalBtn"
                                data-id="{{ $indicador->id }}">Editar</button>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-inst3 btn-sm dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                </button>
                                <ul class="dropdown-menu">

                                    <li><a class="dropdown-item" href="{{ route('indicador.details', $indicador->id) }}"
                                            data-id="{{ $indicador->id }}">Detalles</a></li>
                                    <li><a class="dropdown-item js-set-formula" href="#"
                                            data-id="{{ $indicador->id }}">Definir formula</a></li>
                                    <li><a class="dropdown-item js-delete-indicador" href="#"
                                            data-id="{{ $indicador->id }}">Eliminar</a></li>

                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            <tr>

                <td colspan="2">
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
                <td colspan="5">

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
