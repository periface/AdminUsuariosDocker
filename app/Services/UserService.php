<?php

namespace App\Services;

use App\Models\User;
use App\Http\Resources\UserResource;

use App\DTO\Users\UserDTO;

class UserService{

    public function getAllUsers(){

        $usersDb = User::all();
        
        $userDtoList = array();

        if(count($usersDb) > 0){
            
            foreach ($usersDb as $user) {
                $userDtoList[] = new UserDTO(
                    $user->id,
                    $user->name,
                    $user->apPaterno,
                    $user->apMaterno,
                    $user->email,
                    $user->created_at,
                    $user->updated_at,
                    $user->direccion
                );
            }
        }

        return $userDtoList;
    }

    public function getUserById(User $user){

        $userDto = new UserDTO(
            $user->id,
            $user->name,
            $user->apPaterno,
            $user->apMaterno,
            $user->email,
            $user->fechaCreacion,
            $user->fechaModificacion,
            $user->direccion
        );

        return $userDto;
    }

}
