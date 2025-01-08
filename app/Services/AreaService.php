<?php
namespace App\Services;

use App\Models\Area;
use App\Models\User;
use App\DTO\Area\AreaDTO;

class AreaService {

    protected $userService;

    public function getAllAreas(){
        
        $areaDtoList = array();

        $areasDb = Area::all();
        
        if(count($areasDb) > 0){
            
            foreach ($areasDb as $area) {

                $user = User::find($area->responsable);

                $areaDtoList[] = new AreaDTO(
                    $area->id,
                    $area->nombre,
                    $area->siglas,
                    $user->name,
                    $area->created_at
                );

            }

        }
        return $areaDtoList;
    }
}
