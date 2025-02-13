<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema de Indicadores">
    <meta name="author" content="Secretaría de Administración">

    <title>Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Encode+Sans:wght@100..900&display=swap" rel="stylesheet">

     <!-- Frameworks -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
         integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
         crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Icons -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"> --}}

    <!-- Custom styles for this template-->
    @vite(['resources/css/app.css','resources/css/sb-admin-2.min.css',
            'resources/fontawesome-free/css/all.min.css'])
    <style>
        body
        {
            font-family: "Encode Sans", serif;
        }
   </style>
</head>
<body class="bg-inst">
    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-20">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image">
                                <img class=" mt-20" src="https://img.freepik.com/free-vector/data-analysis-concept-illustration_114360-8053.jpg?t=st=1739474291~exp=1739477891~hmac=5273104c5a720b104d124d04ffa098466d5395462a3690fb3af2294822823788&w=826" alt="">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="">
                                        <h1 class="h4 text-gray-900 mb-2">Sistema de Indicadores</h1>
                                        <p>
                                            Secretaría de Administración <br>
                                            Gobierno del Estado de Tamaulipas
                                        </p>
                                    </div>
                                    <form  method="POST" action="{{ route('login') }}"  class="user mt-4">
                                        @csrf
                                        <div class="form-group">
                                            <label for="email" class="form-label">Correo Electrónico: <span class="text-danger">*</span></label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon1">
                                                    <i class="fas fa-at"></i>
                                                </span>
                                                <input type="email" name="email" class="form-control" placeholder="Correo electrónico"
                                                name="email" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="password" class="form-label">Contraseña: <span class="text-danger">*</span></label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon1">
                                                    <i class="fas fa-key"></i>
                                                </span>
                                                <input type="password" name="password" class="form-control" placeholder="Contraseña"
                                                name="Passowrd" required>
                                            </div>
                                            @if ($message !== null)
                                            <span class="text-danger">* {{ $message }}</span>
                                            @endif
                                        </div>
                                        <button class="btn bsb-btn-xl btn-inst btn-block" type="submit">Iniciar Sesión</button>
                                        <hr>
                                    </form>
                                    <hr>
                                    <div class="text-right">
                                        <a class="link-secondary text-decoration-none cursor-pointer">¿Olvidó su contraseña?</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</body>
</html>