<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\RoleService;

// Services
use App\Services\UserService;
use App\Services\PermissionService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    protected $userService;
    protected $roleService;
    protected $permissionService;

    public function __construct(UserService $userService, RoleService $roleService, PermissionService $permissionService){
        $this->userService          = $userService;
        $this->roleService          = $roleService;
        $this->permissionService    = $permissionService;
    }

    /**
     * Obtiene una lista de los usuarios registrados en sistema
     */
    public function index(){
        
        try {

            $users = $this->userService->getAllUsers();

            return response()->json([
                'data' => [
                    'status' => 'success',
                    'data' => $users,
                    'statusCode' => Response::HTTP_OK
                ]
            ], Response::HTTP_OK);

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
     * Función para registrar usuarios en el sistema, la función aplica reglas de negocio para validar la entrada
     * de datos, si las reglas se cumplen continua con el flujo, caso contraro mostrará los mensajes de error
     * @param Illuminate\Http\Request $request contiene la solicitud HTTP que trae consigo 
     * los valores esperados: email, password, name
     */
    public function store(Request $request){

        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'name' => 'required'
        ],[
            'email.unique' => 'La dirección de correo electrónico utilizada, ya se encuentra registrada'
        ]);

        $data = $request->all();
        $data['activation_token'] = Str::random(60);
        $data['is_active'] = false;

        $user = User::create($data);

        $wb_data = [
            "email" => $user->email,
            "activation_route" => 'http://localhost:8000/activate/'.$user->activation_token
        ];


        $webhook_response = Http::post('https://servandodev.app.n8n.cloud/webhook-test/f45dc5db-14d6-4e1c-85d2-44fecabc8e69', $wb_data);

        return response()->json([
            'data' => [
                'attributes' => [
                    'status' => 'success',
                    // 'data' => $user,
                    'data' => 'Para la activación de la cuenta, enviamos un correo a la dirección registrada',
                    'statusCode' => Response::HTTP_CREATED
                ]
            ]
        ], Response::HTTP_CREATED);
    }

    /**
     * Regresa el usuario que coincida con el parámetro de búsqueda
     * @param User $user contiene el usuario que desea buscar en la base
     */
    public function show(User $user){

        $response = $this->userService->getUserById($user);

        return response()->json([
            'data' => [
                'attributes' => [
                    'status' => 'success',
                    'data' => $response,
                    'statusCode' => Response::HTTP_OK
                ]
            ]
        ], Response::HTTP_OK);
    }

    /**
     * Función para actualizar información de un usuario en específico
     * * @param User $user contiene el usuario que desea actualizar en la base
     */
    public function update(Request $request, User $user){

        $request->validate([
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'required',
            'name' => 'required'
        ]);

        $user->update($request->all());

        return response()->json([
            'data' => [
                'attributes' => [
                    'status' => 'success',
                    'data' => $user,
                    'statusCode' => Response::HTTP_OK
                ]
            ]
        ]);
    }

    public function destroy(User $user){
        try {

            // Has Roles
            $roles = $user->roles()->pluck('name');

            $rolesCount = $user->roles()->count();

            switch ($rolesCount) {
                case ($rolesCount > 1):
                    foreach ($roles as $rol) {
                        $user->removeRole($rol[0]);    
                    }
                    break;
                case ($rolesCount == 1):
                    $user->removeRole($roles[0]);  
                    break;
            }

            // Has Permissions
            $permissions = $user->permissions()->pluck('name');
            $permissionsCount = $user->permissions()->count();
            
            switch ($permissionsCount) {
                case ($permissionsCount > 1):
                    foreach ($permissions as $permission) {
                        $user->removePermission($permission[0]);    
                    }
                    break;
                case ($permissionsCount == 1):
                    $user->removeRole($permissions[0]);   
                    break;
            }

            $user->delete();

            return response()->json([
                'data' => [
                    'attributes' => [
                        'status' => 'success',
                        'data' => null,
                        'statusCode' => Response::HTTP_NO_CONTENT
                    ]
                ]
            ], Response::HTTP_NO_CONTENT);

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

    public function userRolesAndPermissions(User $user){

        $userDto = $this->userService->getUserById($user);

        $roles = $this->roleService->getUserRoles($user);

        $permissions = $this->permissionService->getUserPermissions($user);


        return response()->json([
            'data' => [
                'attributes' => [
                    'status' => 'success',
                    'data' => [
                        'user' => $userDto,
                        'roles' => $roles,
                        'permissions' => $permissions
                    ],
                    'statusCode' => Response::HTTP_OK
                ]
            ]
        ], Response::HTTP_OK);

    }

}
