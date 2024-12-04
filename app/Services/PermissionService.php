<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\DTO\Permission\PermissionDTO;
use Spatie\Permission\Models\Permission;

class PermissionService{

    public function getAllPermissions(){
        $permissionsDtoList = array();

        $permissionsDb = Permission::all();
        if(count($permissionsDb) > 0){
            
            foreach ($permissionsDb as $permission) {
                $permissionDto = new PermissionDTO();

                $permissionDto->id = $permission->id;
                $permissionDto->name = $permission->name;
                $permissionDto->fechaCreacion = $permission->created_at;

                $permissionsDtoList[] = $permissionDto;
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
                    $permissionDto = new PermissionDTO();
    
                    $permissionDto->id = $permission->id;
                    $permissionDto->name = $permission->name;
    
                    $permissionDtoList[] = $permissionDto;
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

}