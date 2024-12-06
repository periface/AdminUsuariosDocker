<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response;

class PermissionController extends Controller
{
    //
    public function index(){
        try {
            
            $permissions = [];

            $permissions[] = Permission::all();

            return response()->json([
                'data' => [
                    'attributes' => [
                        'status' => 'success',
                        'data' => $permissions,
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

            $request->merge(['guard_name' => 'web']);
            
            $request->validate([
                'name' => 'required|min:4'
            ]);
            
            $permission = Permission::create($request->all());

            return response()->json([
                'data' => [
                    'attributes' => [
                        'status' => 'success',
                        'data' => $permission,
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

    public function show(Permission $permission){
        try {
            
            return response()->json([
                'data' => [
                    'attributes' => [
                        'status' => 'success',
                        'data' => $permission,
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

    public function update(Request $request, Permission $permission){
        try {
            
            $request->validate([
                'name' => 'required|min:4'
            ]);

            $permission->update($request->all());

            return response()->json([
                'data' => [
                    'attributes' => [
                        'status' => 'success',
                        'data' => $permission,
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

    public function destroy(Permission $permission){
        try {

            $permission->delete();

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

    public function atachPermissionRole(Role $role, Permission $permission){
        try {
            
            $role->givePermissionTo($permission->name);

            return response()->json([
                'data' => [
                    'attributes' => [
                        'status' => 'success',
                        'data' => 'Operación realizada con éxito',
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

    public function detachPermissionRole(Role $role, Permission $permission){
        try {
            
            $role->revokePermissionTo($permission->name);

            return response()->json([
                'data' => [
                    'attributes' => [
                        'status' => 'success',
                        'data' => 'Operación realizada con éxito',
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

    public function atachPermissionUser(User $user, Permission $permission){
        try {
            
            $user->givePermissionTo($permission->name);

            return response()->json([
                'data' => [
                    'attributes' => [
                        'status' => 'success',
                        'data' => 'Operación realizada con éxito',
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

    public function detachPermissionUser(User $user, Permission $permission){
        try {
            
            $user->revokePermissionTo($permission->name);

            return response()->json([
                'data' => [
                    'attributes' => [
                        'status' => 'success',
                        'data' => 'Operación realizada con éxito',
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
}
