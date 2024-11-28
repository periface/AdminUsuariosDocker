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

                $userDto = new UserDTO();

                $userDto->id                = $user->id;
                $userDto->name              = $user->name;
                $userDto->email             = $user->email;
                $userDto->fechaCreacion     = $user->created_at;
                $userDto->fechaModificacion = $user->updated_at;
                
                $userDtoList[] = $userDto;
            }
        }

        return $userDtoList;
    }

    public function getUserById(User $user){

        $userDto = new UserDTO();

        $userDto->id                = $user->id;
        $userDto->name              = $user->name;
        $userDto->email             = $user->email;
        $userDto->fechaCreacion     = $user->created_at;
        $userDto->fechaModificacion = $user->updated_at;

        return $userDto;
    }

}
