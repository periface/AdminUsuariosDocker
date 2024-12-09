<?php

namespace App\Services;

use App\DTO\Role\RoleDTO;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Services\PermissionService;

class RoleService{


    protected $permissionService;

    public function __construct(PermissionService $permissionService){
        $this->permissionService = $permissionService;
    }

    public function getRole(Role $role){

        return new RoleDTO(
            $role->id,
            $role->name,
            $role->description,
            $role->alias
        );

    }

    public function getAllRoles(){
        $roleDtoList = array();
        $permissions = array();

        $rolesDb = Role::all();

        if(count($rolesDb) > 0) {
            foreach ($rolesDb as $role) {

                $roleDtoList[] = new RoleDTO(
                    $role->id,
                    $role->name,
                    $role->description,
                    $role->alias
                );
            }
        }
        return $roleDtoList;
    }

    public function getAvailableRoles($user){
        
        $roleDtoList = array();
        $roleId = DB::table('model_has_roles')
                    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                    ->where('model_has_roles.model_id', [$user->id])
                    ->pluck('roles.id');

        $rolesDb = Role::whereNotIn('id', $roleId)->get();

        switch (count($rolesDb)) {
            case (count($rolesDb) > 1):
                    foreach ($rolesDb as $role) {

                        $roleDtoList[] = new RoleDTO(
                            $role->id,
                            $role->name,
                            $role->description,
                            $role->alias
                        );
                    }
                break;
            case (count($rolesDb) === 1):

                    $role = $rolesDb->first();

                    $roleDtoList[] = new RoleDTO(
                            $role->id,
                            $role->name,
                            $role->description,
                            $role->alias
                        );
                break;
            default:
                return $roleDtoList;
                break;
        }

        return $roleDtoList;

    }

    public function getUserRoles($user){
        
        $rolesDb = DB::table('model_has_roles')
                    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                    ->where('model_has_roles.model_id', $user->id)
                    ->get(['roles.id','roles.name', 'roles.alias', 'roles.description']);

        $roleDtoList = array();

        if(count($rolesDb) > 0){
            foreach ($rolesDb as $role) {
             
                $roleDtoList[] = new RoleDTO(
                    $role->id,
                    $role->name,
                    $role->description,
                    $role->alias
                );
            }
        }

        return $roleDtoList;
    }

    public function getRolePermissions($role){
        
        $permissions = $this->permissionService->getRolePermissions($role);

        return new RoleDTO(
            $role->id,
            $role->name,
            $role->description,
            $role->alias,
            $permissions
        );

    }

}