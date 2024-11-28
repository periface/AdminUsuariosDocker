<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    //
    public function store(Request $request){

        try {
            
            // Aplicamos la validación al request
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            // Pasada la validación obtenemos el usuario de la base a través de su email
            $user = User::where('email', $request->email)->first();

            // Una vez obtenido el usuario, validamos si la contraseña del usuario de la base, coincide con la
            // contraseña que viene en el request
            if ( !$user || !Hash::check($request->password, $user->password)) {

                // Si las credenciales no coinciden mandamos mensaje de error y le notificamos al usuario
                return response()->json([
                    'message' => 'Las credenciales son incorrectas',
                ], Response::HTTP_UNPROCESSABLE_ENTITY); //422
            }

            // Si las credenciales son válidas lo autenticamos
            auth()->login($user);

            // Generamos su token
            $token = $user->createToken($user->name)->plainTextToken;

            // $cookie = cookie('auth_token', $token, 3, '/', null, null);

            return response()->json([
                'data' => [
                    'attributes' => [
                        'status' => 'success',
                        'data' => $token,
                        'statusCode' => Response::HTTP_OK
                    ]
                ]
            ], Response::HTTP_OK);

            return redirect()->route('principal')->with(['user' => $user]); 

        } catch (\Throwable $th) {
            return response()->json([
                'data' => [
                    'attributes' => [
                        'status' => 'error',
                        'data' => $th->getMessage(),
                        'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR
                    ]
                ]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function logout(Request $request){
        Auth::logout();
 
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
