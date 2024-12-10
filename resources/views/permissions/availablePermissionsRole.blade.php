<div class="container">
    <div class="row">
        <div class="col-12">
            <form action="" id="permissionsRole">
                @csrf
                @if (count($availablePermissions) > 0)
                    @foreach ($availablePermissions as $permission)
                        <div class="col-8">
                            {{ $permission->name }}
                        </div>
                        <div class="col-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="{{ $permission->id }}" data-permission="{{ $permission->id }}">
                            </div>
                        </div>
                    @endforeach
                @endif
            </form>
        </div>
        <div class=" modal-footer">
            <button type="button" class="btn btn-inst3 btn-sm" data-bs-dismiss="modal">
                <small>CANCELAR</small>
            </button>
        </div>
    </div>
</div>