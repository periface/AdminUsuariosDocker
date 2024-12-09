<div class="container">
    <div class="row">
        <div class="col">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <td>
                            Role
                        </td>
                        <td>
                            Config
                        </td>
                    </tr>
                </thead>
                <tbody>
                    @if (count($roles) > 0)
                        @foreach ($roles as $role)
                            <tr>
                                <td>
                                    {{ $role->alias }}
                                </td>
                                <td>
                                    <a id="{{ $role->id }}" class="atach-role" data-role="{{ $role->id }}">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="2">
                                <small>Sin roles para agregar</small>
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
        </div>
    </div>
</div>