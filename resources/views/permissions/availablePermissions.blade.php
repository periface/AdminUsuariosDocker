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
                                <td>
                                    <a id="{{ $permission->id }}" class="atach-permission" data-permission="{{ $permission->id }}">
                                        <i class="fa fa-plus"></i>
                                    </a>
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
    </div>
</div>