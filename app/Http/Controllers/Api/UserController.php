<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\UserArea;
use Illuminate\Support\Str;

// Services
use Illuminate\Http\Request;
use App\Services\AreaService;
use App\Services\RoleService;
use App\Services\UserService;
use Spatie\Permission\Models\Role;
use App\Services\PermissionService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Api\RoleController;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    protected $userService;
    protected $roleService;
    protected $permissionService;
    protected $roleController;
    protected $areaService;

    public function __construct(UserService $userService, 
                                RoleService $roleService, 
                                PermissionService $permissionService,
                                RoleController $roleController,
                                AreaService $areaService)
    {
        $this->userService          = $userService;
        $this->roleService          = $roleService;
        $this->permissionService    = $permissionService;
        $this->roleController       = $roleController;
        $this->areaService          = $areaService;
    }

    /**
     * Obtiene una lista de los usuarios registrados en sistema
     */
    public function index()
    {

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
    public function store(Request $request)
    {

        // Validacion del request
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'name' => 'required',
            'apPaterno' => 'required'
        ], [
            'email.unique' => 'La dirección de correo electrónico utilizada, ya se encuentra registrada'
        ]);

        try {

            // 1.- Obtenemos el rol antes de proceder
            $role = Role::find($request->roleId);

            // 2.- Si el rol es SPA, validamos que no tenga un responsable ya asignado el área 
            if($role->name === "SPA" && $this->areaService->hasResponsableArea($request->areaId)){
                return response()->json([
                    'data' => [
                    'attributes' => [
                        'status' => 'Error',
                        // 'data' => $user,
                        'data' => 'El área ya cuenta con un responsable, si desea cambiarlo, deberá actualizar el área.',
                        'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY
                    ]
                ]
                ]);
            }

            // 3.- Creamos el usuario
            $data = $request->all();
            $data['activation_token'] = Str::random(60);
            $data['is_active'] = false;
            $user = User::create($data);
            
            // 4.- Asignamos el rol al usuario
            $assignRoleResponse = $this->roleController->attachRole($user, $role);

            // 5.- Si el rol es SPA, lo establecemos como responsable del área
            if($role->name == "SPA"){
                $area = $this->areaService->setResponsableArea($user);
            }
            
            // 6.- Creamos la relación Usuario - Area
            UserArea::create([
                'userId' => $user->id,
                'areaId' => $data['areaId'],
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'rolId' => $role->id
            ]);

            $wb_data = [
                "email" => $user->email,
                "activation_link" => 'http://localhost:8000/activate/' . $user->activation_token
            ];

            // $webhook_response = Http::post('http://localhost:5678/webhook-test/f45dc5db-14d6-4e1c-85d2-44fecabc8e69', $wb_data);

            return response()->json([
                'data' => [
                    'attributes' => [
                        'status' => 'success',
                        'data' => 'Para la activación de la cuenta, enviamos un correo a la dirección registrada',
                        'statusCode' => Response::HTTP_CREATED
                    ]
                ]
            ], Response::HTTP_CREATED);
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
     * Regresa el usuario que coincida con el parámetro de búsqueda
     * @param User $user contiene el usuario que desea buscar en la base
     */
    public function show(User $user)
    {

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
    public function update(Request $request, User $user)
    {
        $request->merge(['is_active' => $request->has('is_active') ? 1 : 0]);

        $request->validate([
            // 'email' => 'required|email|unique:users,email,' . $user->id,
            // 'password' => 'required',
            'name' => 'required',
            'apPaterno' => 'required'
        ]);

        $user->update($request->all());
        $user->roles()->sync([$request->input('roleId')]);

        return response()->json([
            'data' => [
                'attributes' => [
                    'status' => 'success',
                    'data' => 'Su solicitud se ha realizado correctamente', // $user,
                    'statusCode' => Response::HTTP_OK
                ]
            ]
        ]);
    }

    public function destroy(User $user)
    {
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

            // Has user-area
            $this->userService->deleteUserArea($user);

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

    public function userRolesAndPermissions(User $user)
    {

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
