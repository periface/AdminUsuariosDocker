<?php

namespace App\Services;

use App\Models\Area;
use App\Models\User;
use App\DTO\Area\AreaDTO;
use Illuminate\Support\Facades\Log;

class AreaService
{

    protected $userService;

    public function getAllAreas()
    {

        $areaDtoList = array();

        $areasDb = Area::all();

        if (count($areasDb) > 0) {

            foreach ($areasDb as $area) {
                Log::info($area);
                $user = User::find($area["responsableId"]);
                $name = "Sin responsable asignado";
                if ($user != null) {
                    $name = $user->name . ' ' . $user->apPaterno . ' ' . $user->apMaterno;
                }
                $areaDtoList[] = new AreaDTO(
                    $area->id,
                    $area['nombre'],
                    $area["siglas"],
                    $name,
                    $area["created_at"],
                    $area["secretariaId"]
                );
            }
        }
        return $areaDtoList;
    }

    public function getAreaById(Area $area){
        $areasDb = Area::where('id', $area->id)
                        ->get();
        if (count($areasDb) > 0) {

            foreach ($areasDb as $area) {
                Log::info($area);
                $user = User::find($area["responsableId"]);
                $name = "Sin responsable asignado";
                if ($user != null) {
                    $name = $user->name . ' ' . $user->apPaterno . ' ' . $user->apMaterno;
                }
                $areaDtoList[] = new AreaDTO(
                    $area->id,
                    $area['nombre'],
                    $area["siglas"],
                    $name,
                    $area["created_at"],
                    $area["secretariaId"]
                );
            }
        }
        return $areaDtoList;
    }
}
