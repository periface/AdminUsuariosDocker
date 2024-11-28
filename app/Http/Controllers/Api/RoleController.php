<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    //
    protected $userController;
    public function __construct(UserController $userController){
        $this->userController = $userController;
    }

    public function index(){
        try {
            
            $roles = [];

            $roles[] = Role::all();

            return response()->json([
                'data' => [
                    'attributes' => [
                        'status' => 'success',
                        'data' => $roles,
                        'statusCode' => Response::HTTP_OK
                    ]
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

    public function store(Request $request){
        
        try {

            $request->validate([
                'name' => 'required|min:4',
            ]);
    
            $role = Role::create($request->all());
    
            return response()->json([
                'data' => [
                    'attributes' => [
                        'status' => 'success',
                        'data' => $role,
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

    public function show(Role $role){

        try {
            
            return response()->json([
                'data' => [
                    'attributes' => [
                        'status' => 'success',
                        'data' => $role,
                        'statusCode' => Response::HTTP_OK
                    ]
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

    public function update(Request $request, Role $role){
        try {
            
            $request->validate([
                'name' => 'required|min:4',
            ]);

            $role->update($request->all());

            return response()->json([
                'data' => [
                    'attributes' => [
                        'status' => 'success',
                        'data' => $role,
                        'statusCode' => Response::HTTP_OK
                    ]
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

    public function destroy(Role $role){
        try {
            
            $role->delete();

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

    public function attachRole(User $user, Role $role){

        try {
            
            $user->assignRole($role->name);

            return response()->json([
                'data' => [
                    'attributes' => [
                        'status' => 'success',
                        'data' => 'Asignación correcta',
                        'statusCode' => Response::HTTP_OK
                    ]
                ]
            ],  Response::HTTP_OK);

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

    public function detachRole(User $user, Role $role){
        try {
            
            $user->removeRole($role->name);

            return response()->json([
                'data' => [
                    'attributes' => [
                        'status' => 'success',
                        'data' => 'Acción completada correctamente',
                        'statusCode' => Response::HTTP_OK
                    ]
                ]
            ],  Response::HTTP_OK);

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
}
