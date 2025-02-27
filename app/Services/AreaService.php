<?php

namespace App\Services;

use App\Models\Area;
use App\Models\User;
use App\DTO\Area\AreaDTO;
use App\Models\Secretaria;
use Illuminate\Support\Facades\Log;

class AreaService
{

    protected $userService;

    public function getAllAreas()
    {

        $areaDtoList = array();

        $areasDb = Area::all();
        $secretariasDb = Secretaria::all();
        if (count($areasDb) > 0) {

            foreach ($areasDb as $area) {
                Log::info($area);

                // Datos del responsable
                $user = User::find($area["responsableId"]);
                $name = "Sin responsable asignado";
                // Datos de la secretaría
                $secretaria = $secretariasDb->where('id', $area['secretariaId'])->first();

                if ($user != null) {
                    $name = $user->name . ' ' . $user->apPaterno . ' ' . $user->apMaterno;
                }
                $areaDtoList[] = new AreaDTO(
                    $area->id,
                    $secretaria['nombre'],
                    $secretaria['siglas'],
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

    public function getAreaById($areaId, $responsableId)
    {
        $areaDb = Area::where('responsableId', $responsableId)
                        ->get();
        if (!$areaDb) {
            return null;
        }
        
        $areaDtoList = [];
        $user = User::where('id', $responsableId)->first();
        $name = ($user !== null) ? $user->name.' '.$user->apPaterno.' '.$user->apMaterno : "Sin responsable asignado";
        if(count($areaDb) > 0){
            foreach ($areaDb as $area) {
                $areaDtoList[] = new AreaDTO(
                    $area['id'],
                    $area['nombre'],
                    $area['siglas'],
                    $name,
                    $area['created_at'],
                    $area['secretariaId']
                );
            }
        }
        return $areaDtoList;
    }

    public function setResponsableArea(User $user)
    {
        $area = Area::where('id', $user->areaId)
            ->update(['responsableId' => $user->id]);
        return $area;
    }

    public function hasResponsableArea($areaId){
        $area = Area::find($areaId);
        return $area['responsableId'];
    }
}
