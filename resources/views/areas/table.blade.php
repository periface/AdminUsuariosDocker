<table class="table table-striped">
    <thead class="thead small">
        <tr>
            <th class=" w-1/12 text-center">#</th>
            <th class=" w-1/3">Nombre</th>
            <th class=" w-1/5 text-center">Siglas</th>
            <th class=" w-1/7 text-center">Responsable</th>
            <th class=" w-1/5 text-center">Fecha registro</th>
            <th class=" w-2/4">Opciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($areas as $area)
        <tr>
            <td class=" w-1/12 text-center">
                {{ $loop->iteration }}
            </td>
            <td class=" w-1/3">
                {{ $area->nombre }}
            </td>
            <td class=" w-1/5 text-center">
                {{ $area->siglas }}
            </td>
            <td class=" w-1/7 text-center">
                @if ($area->responsable == null)
                    <span class=" badge bg-secondary">
                        Sin Asignar
                    </span>
                @else
                    <span class=" badge bg-success">
                        {{ $area->responsable }}
                    </span>
                @endif
            </td>
            <td class=" w-1/5 text-center">
                {{ $area->fecha_creacion }}
            </td>
            <td>
                <div class="btn-group" role="group">
                    <button type="button" class="btn dropdown-toggle btn-sm btn-inst3" data-bs-toggle="dropdown">
                    Administrar
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item edit-area" id="{{ $area->id }}" style="cursor: pointer">Editar</a>
                        </li>
                        <li>
                            <a class="dropdown-item delete-area" id="{{ $area->id }}" style="cursor: pointer">Eliminar</a>
                        </li>
                    </ul>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>