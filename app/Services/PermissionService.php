<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\DTO\Permission\PermissionDTO;
use Spatie\Permission\Models\Permission;

class PermissionService{

    /**
     * Obtiene todos los permisos registrados en el sistema.
     *
     * Este método recupera todos los registros de la tabla de permisos,
     * los transforma en una lista de objetos DTO (Data Transfer Object)
     * para encapsular su información de manera estructurada.
     *
     * @return array Una lista de objetos PermissionDTO que representan los permisos.
    */
    public function getAllPermissions(){
        $permissionsDtoList = array();

        $permissionsDb = Permission::all();
        if(count($permissionsDb) > 0){
            
            foreach ($permissionsDb as $permission) {

                $permissionsDtoList[] = new PermissionDTO(
                    $permission->id,
                    $permission->name,
                    $permission->created_at
                );
            }
        }

        return $permissionsDtoList;
    }

    public function getUserPermissions($user){

        $permissionDtoList = array();

        $permissionsDb = DB::Table('model_has_permissions')
                        ->join('permissions', 'model_has_permissions.permission_id', '=', 'permissions.id')
                        ->where('model_has_permissions.model_id', $user->id)
                        ->get(['permissions.id', 'permissions.name']);

        if(count($permissionsDb) > 0){
            foreach ($permissionsDb as $permission) {
                $permissionDto = new PermissionDTO();

                $permissionDto->id = $permission->id;
                $permissionDto->name = $permission->name;

                $permissionDtoList[] = $permissionDto;
            }
        }

        return $permissionDtoList;
    }

    public function getAvailablePermissions($user){
        $permissionDtoList = array();
        
        $permissionId = DB::table('model_has_permissions')
                    ->join('permissions', 'model_has_permissions.permission_id', '=', 'permissions.id')
                    ->where('model_has_permissions.model_id', [$user->id])
                    ->pluck('permissions.id');

        $permissionsDb = Permission::whereNotIn('id', $permissionId)->get();

        switch (count($permissionsDb)) {
            case (count($permissionsDb) > 1):
                foreach ($permissionsDb as $permission) {

                    $permissionDtoList[] =  new PermissionDTO(
                        $permission->id,
                        $permission->name,
                        $permission->created_at
                    );
                    
                }
                break;
            case (count($permissionsDb) === 1):
                $permissionDto = new PermissionDTO();

                $permission = $permissionsDb->first();

                $permissionDto->id   = $permission->id;
                $permissionDto->name = $permission->name;

                $permissionDtoList[] = $permissionDto;
                break;
            default:
                return $permissionDtoList;
                break;
        }

        return $permissionDtoList;
    }

    public function getAvailablePermissionsRole($role){
        $permissionDtoList = array();
        
        $permissionId = DB::table('role_has_permissions')
                    ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                    ->where('role_has_permissions.role_id', [$role->id])
                    ->pluck('permissions.id');

        $permissionsDb = Permission::whereNotIn('id', $permissionId)->get();

        switch (count($permissionsDb)) {
            case (count($permissionsDb) > 1):
                foreach ($permissionsDb as $permission) {

                    $permissionDtoList[] = new PermissionDTO(
                        $permission->id,
                        $permission->name
                    );
                }
                break;
            case (count($permissionsDb) === 1):

                $permission = $permissionsDb->first();

                $permissionDtoList[] = new PermissionDTO(
                    $permission->id,
                    $permission->name
                );

                break;
            default:
                return $permissionDtoList;
                break;
        }

        return $permissionDtoList;
    }

    public function getRolePermissions($role){

        $permissionDtoList = array();

        $permissionsDb = DB::Table('role_has_permissions')
                        ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                        ->where('role_has_permissions.role_id', $role->id)
                        ->get(['permissions.id', 'permissions.name']);

        if(count($permissionsDb) > 0)
        {
            foreach ($permissionsDb as $permission) {
                
                $permissionDtoList[] = new PermissionDTO(
                    $permission->id,
                    $permission->name,
                    $permission->created_at
                );

            }
        }
        return $permissionDtoList;
    }
}