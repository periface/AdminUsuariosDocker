<div class="container">
    <div class="row">
        <div class="col">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <td>
                            Permiso
                        </td>
                        <td>
                            Config
                        </td>
                    </tr>
                </thead>
                <tbody>
                    @if (count($availablePermissions) > 0)
                        @foreach ($availablePermissions as $permission)
                            <tr>
                                <td>
                                    {{ $permission->name }}
                                </td>
                                <td class="text-center">
                                    {{-- <a id="{{ $permission->id }}" class="atach-permission" data-permission="{{ $permission->id }}">
                                        <i class="fa fa-plus"></i>
                                    </a> --}}
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="{{ $permission->id }}" data-permission="{{ $permission->id }}">
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="2">
                                <small>Sin permisos para agregar</small>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class=" modal-footer">
            <button type="button" class="btn btn-inst3 btn-sm" data-bs-dismiss="modal">
                <small>CANCELAR</small>
            </button>
            <button type="submit" class="btn btn-inst btn-sm">
                <small>GUARDAR</small>
            </button>
        </div>
    </div>
</div>