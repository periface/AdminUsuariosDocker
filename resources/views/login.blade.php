@extends('layout')

@section('content')

<div class="d-flex vh-100">
    <div class="container-fluid my-auto">
        <div class="row">
            <div class=" col">
                <h5 class="text-center mb-3">Iniciar Sesión</h5>
                <p class="text-center">
                    <small>
                        Inicie sesión para ingresar al <br> módulo de usuarios.
                    </small>
                </p>
                <form class=" mt-4" method="POST" 
                    action="{{ route('login') }}"
                   >
                    @csrf
                    <div class="row mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" id="email" name="email" class="form-control" id="email" placeholder="Ingresa tu correo">
                    </div>
                    <div class="row mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" id="password" name="password" class="form-control" id="password" placeholder="Ingresa tu contraseña">
                    </div>
                    <div class="row mb-3">
                        <button type="submit" class="btn btn-danger w-100">Iniciar Sesión</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@endsection
