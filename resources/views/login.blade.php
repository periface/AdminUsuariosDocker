@extends('layout')

@section('content')

<div class="d-flex vh-100" style="background: #54565a20;">
    <div class="container-fluid my-auto">
        <div class="row">
            <div class="col px-52">
                <!-- Login 5 - Bootstrap Brain Component -->
            <section class="p-3 p-md-4 p-xl-5">
                <div class="container">
                <div class="card shadow-sm">
                    <div class="row g-0">
                    <div class="col-12 col-md-6 bg-inst">
                        <div class="d-flex align-items-center justify-content-center h-100">
                        <div class="col-10 col-xl-8 py-3 text-center text-white">
                            <img class="img-fluid rounded mb-4" loading="lazy" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSLcBubmZ-qQsYes0JJ-Dd9S_Gi5vGYqEXMaA&s" alt="BootstrapBrain Logo">
                            <hr class="border-primary-subtle mb-4">
                            <h2 class=" h3 mb-4">Sistema de Indicadores</h2>
                            <p class="lead m-0">Secretaría de Administración <br>
                            Gobierno del Estado de Tamaulipas</p>
                        </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 text-gray-500">
                        <div class="card-body p-3 p-md-4 p-xl-5">
                        <div class="row">
                            <div class="col-12">
                            <div class="mb-5">
                                <h1 class="h4">Iniciar sesión</h1>
                            </div>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('login') }}" >
                            @csrf
                            <div class="row gy-3 gy-md-4 overflow-hidden">
                            <div class="col-12">
                                <label for="email" class="form-label">Correo Electrónico: <span class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">
                                        <i class="fa-solid fa-at"></i>
                                    </span>
                                    <input type="email" name="email" class="form-control" placeholder="Correo electrónico"
                                    name="email" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="password" class="form-label">Contraseña: <span class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">
                                        <i class="fa-solid fa-key"></i>
                                    </span>
                                    <input type="password" name="password" class="form-control" placeholder="Contraseña"
                                    name="Passowrd" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-grid">
                                <button class="btn bsb-btn-xl btn-inst" type="submit">Iniciar Sesión</button>
                                </div>
                            </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-12">
                            <hr class="mt-5 border-secondary-subtle">
                            <div class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-md-end">
                                <a class="link-secondary text-decoration-none cursor-pointer">¿Olvidó su contraseña?</a>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </section>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@endsection
