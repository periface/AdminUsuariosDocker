<?php

namespace App\Services;

use App\Models\Secretaria;
use App\Services\AreaService;
use App\DTO\Secretaria\SecretariaDTO;

class SecretariaService {
 
    protected $areaService;

    public function __construct(AreaService $areaService){
        $this->areaService = $areaService;
    }

    public function getAllSecretarias(){

        $secretariaDtoList = array();
        $secretariasDb = Secretaria::all();

        if(count($secretariasDb) > 0){
            foreach ($secretariasDb as $secretaria) {
                $secretariaDtoList[] = $this->createSecretariaDTO($secretaria);
            }
        }

        return $secretariaDtoList;
        
    }

    public function getAllSecretariasWithAreas(){
        $secretariaDtoList = [];
        $secretariasDb = Secretaria::with('areas')->get();
        if(count($secretariasDb) > 0){
            foreach ($secretariasDb as $secretaria) {
                $areas = $this->areaService->getAreaBySecretaria($secretaria['id']);
                $secretariaDtoList[] = $this->createSecretariaDTO($secretaria, $areas);
            }
        }

        return $secretariaDtoList;
    }
    
    private function createSecretariaDTO($secretaria, $areas) {

        $areas = empty($areas) ? [] : $areas;
        
        return new SecretariaDTO(
            $secretaria['id'],
            $secretaria['nombre'],
            $secretaria['siglas'],
            $secretaria['type'],
            $secretaria['status'],
            $areas
        );
    }
}