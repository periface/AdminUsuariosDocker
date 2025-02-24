<?php

namespace App\Services;

use App\Models\Area;

use App\Models\User;
use App\Models\UserArea;
use App\DTO\Users\UserDTO;
use App\Models\Secretaria;

class UserService
{

    public function getAllUsers()
    {

        $usersDb = User::all();

        $userDtoList = array();
        if (count($usersDb) > 0) {

            foreach ($usersDb as $user) {
                $area = Area::find($user->areaId);
                $userDtoList[] = new UserDTO(
                    $user->id,
                    $user->name,
                    $user->apPaterno,
                    $user->apMaterno,
                    $user->email,
                    $user->created_at,
                    $user->updated_at,
                    $user->areaId,
                    $area != null ? $area['nombre'] : null,
                    null, null,
                    $user->secretariaId,
                    $user->is_active
                );
            }
        }
        return $userDtoList;
    }

    /**
     * 
    */
    public function getAllUsersArea($areaId){
        
        $usersDtoList = [];
        $users = User::find($areaId)
                    ->get();

        if ( !$users && count($users) > 0) {
            foreach ($users as $user) {
                $userDtoList[] = new UserDTO(
                    $user->id,
                    $user->name,
                    $user->apPaterno,
                    $user->apMaterno,
                    $user->email,
                    $user->created_at,
                    $user->updated_at,
                    $user->areaId,
                    null,
                    null, null,
                    $user->secretariaId,
                    $user->is_active
                );
            }
        }
        return $userDtoList;        
    }

    public function getUserById(User $user)
    {

        
        $area = Area::find($user->areaId);
        $userDto = new UserDTO(
            $user->id,
            $user->name,
            $user->apPaterno,
            $user->apMaterno,
            $user->email,
            $user->created_at,
            $user->updated_at,
            $user->areaId,
            $area != null ? $area['nombre'] : null,
            null, null,
            $user->secretariaId,
            $user->is_active
        );


        return $userDto;
    }

    public function getUsersByArea(User $user){

        $usersDb = User::where('areaId', $user->areaId)
                        ->get();
                        
        $userDtoList = array();
        if (count($usersDb) > 0) {

            foreach ($usersDb as $user) {
                $area = Area::find($user->areaId);
                $userDtoList[] = new UserDTO(
                    $user->id,
                    $user->name,
                    $user->apPaterno,
                    $user->apMaterno,
                    $user->email,
                    $user->created_at,
                    $user->updated_at,
                    $user->areaId,
                    $area['nombre'],
                    null, null,
                    $user->secretariaId,
                    $user->is_active
                );
            }
        }

        return $userDtoList;
        
    }

    public function deleteUserArea(User $user){
        $userAreas = UserArea::where('userId', $user->id)
                            ->get();
        if(count($userAreas) > 0){
           foreach ($userAreas as $userArea) {
                UserArea::where('userId', $user->id)->delete();
           }
        }
    }
}
