<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;


class AuthController extends Controller
{
    /**
     * Método para autenticar a un usuario
     * @param $request: Recibe como parámetro las credenciales para validar si autenticación,
     * se procesan, se validan y si son correctas se genera el token y se redirige a la página princial,
     * caso contrario se retorna mensaje de error de autenticación
     */
    public function store(Request $request){
        
        try {

            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $user = User::where('email', $request->email)->first();

            if ( !$user || !Hash::check($request->password, $user->password)) {

                return response()->json([
                    'message' => 'Las credenciales son incorrectas',
                ], Response::HTTP_UNPROCESSABLE_ENTITY); //422
            }

            auth()->login($user);

            $token = $user->createToken($user->name)->plainTextToken;

            // $cookie = cookie('auth_token', $token, 3, '/', null, null);

            return redirect()->route('principal')->with('token', $token);

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

    /**
     * Cierra e invalida las sesiones del usuario
     * @param $request: Recibe como parámetro información de la sesión del usuario
     */
    public function logout(Request $request) {

        Auth::guard('web')->logout();
 
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();

        return redirect('/');

    }
}
