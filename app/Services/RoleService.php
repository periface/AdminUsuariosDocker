<?php

namespace App\Services;

use App\DTO\Role\RoleDTO;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleService{


    public function getRole(Role $role){

        $roleDto = new RoleDTO();
        $roleDto->id = $role->id;
        $roleDto->name = $role->name;

        return $roleDto;

    }

    public function getAllRoles(){
        $roleDtoList = array();

        $rolesDb = Role::all();

        if(count($rolesDb) > 0) {
            foreach ($rolesDb as $role) {

                $roleDto = new RoleDTO();

                $roleDto->id = $role->id;
                $roleDto->name = $role->name;
                $roleDto->description = $role->description;
                $roleDto->alias = $role->alias;
            
                $roleDtoList[] = $roleDto;
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
                        $roleDto = new RoleDTO();
        
                        $roleDto->id = $role->id;
                        $roleDto->name = $role->name;
        
                        $roleDtoList[] = $roleDto;
                    }
                break;
            case (count($rolesDb) === 1):
                    $roleDto = new RoleDTO();

                    $role = $rolesDb->first();

                    $roleDto->id   = $role->id;
                    $roleDto->name = $role->name;

                    $roleDtoList[] = $roleDto;
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
                    ->get(['roles.id','roles.name']);

        $roleDtoList = array();

        if(count($rolesDb) > 0){
            foreach ($rolesDb as $role) {

                $roleDto = new RoleDTO();

                $roleDto->id    = $role->id;
                $roleDto->name  = $role->name;
             
                $roleDtoList[] = $roleDto;
            }
        }

        return $roleDtoList;
    }

}