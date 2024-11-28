@extends('layout')

@section('content')
<div class="container">
    <input type="hidden" value="{{ $token }}" name="token" id="token">
    <div class="row">
        <div class="col-6">
            <h4>Bienvenido
                <small>
                    @if (auth())
                        <span>{{ auth()->user()->name }}</span>
                    @endif
                </small>
            </h4>
        </div>
        <div class="col-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">
                    Cerrar Sessión
                </button>
            </form>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col">
            <ul>
                <li>
                    <span id="users" style="cursor: pointer">
                        Ver Usuarios
                    </span>
                </li>
                <li>
                    <span id="permissions" style="cursor: pointer">
                        Ver Permisos
                    </span>
                </li>
                <li>
                    <span id="roles" style="cursor: pointer">
                        Ver Roles
                    </span>
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
        <hr>
        <div class="col text-center" id="content">
            <small>Sin información para mostrar</small>
        </div>
    </div>
</div>

<!-- Section Modal -->
<!-- Modal -->
<div class="modal fade" id="modalConfig" tabindex="-1" aria-labelledby="modalConfig">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        function storeToken(){
            let token = document.getElementById('token').value;
            localStorage.setItem('token', token);
        }
        storeToken();
    </script>
    @vite([
        'resources/js/usuarios/index.js',
        'resources/js/auth/permissions/permisos.js'
    ]);
@endsection