<div class="flex items w-1/3 mb-2 border-black">
    <input type="text" class="form-control form-control-sm js-search" placeholder="Buscar"
        {{ $search ? 'value=' . $search : '' }}>
</div>
<table class="table table-striped projects" id="secretariasTable">
    <thead class="small">
        <tr class="w-full">
            <th style="width: 10%" data-sort="id" data-order="desc" class="sort cursor-pointer ">
                # <i class="fas fa-sort pl-2"></i>
            </th>
            <th style="width: 50%" data-sort="nombre" data-order="asc" class="sort cursor-pointer">
                Nombre <i class="fas fa-sort pl-2"></i>
            </th>

            <th style="width: 20%" data-sort="siglas" data-order="asc" class="sort cursor-pointer">
                Siglas <i class="fas fa-sort pl-2"></i>
            </th>
            <th style="width: 100%" class="flex align-middle items-center">
                Acciones
            </th>
        </tr>
    </thead>
    <tbody id="indicadoresTableBody">
        @if (count($secretarias) === 0)
            <tr>
                <td colspan="6" class="text-center">No hay secretarias registrados</td>
            </tr>
        @else
            @foreach ($secretarias as $secretaria)
                <tr>
                    <td>{{ $secretaria['id'] }}</td>
                    <td>{{ $secretaria['nombre'] }}</td>
                    <td>{{ $secretaria['siglas'] }}</td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group"
                            aria-label="Button group with nested dropdown">
                            <button type="button" class="btn btnSecondaryOficial secretariaModalBtn"
                                data-id="{{ $secretaria->id }}">Editar</button>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btnSecondaryOficial dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item js-delete-secretaria" href="#"
                                            data-id="{{ $secretaria->id }}">Eliminar</a></li>
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
