<?php

namespace App\Services;

use App\Models\Area;
use App\Models\User;
use App\DTO\Area\AreaDTO;
use App\Models\Dimension;
use App\Models\Evaluacion;
use App\Models\EvaluacionResult;
use App\Models\Indicador;
use Illuminate\Support\Facades\Log;

class DimensionData
{
    public $id;
    public $nombre;
    public $value;
    public $divideBy;
}
class IndicePAreaViewModel
{
    public $areaId;
    public $areaNombre;
    public $areaSiglas;
    public $dimensionesResult;
}
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

    public function getAreaById($areaId)
    {
        $areaDb = Area::find($areaId);

        if (!$areaDb) {
            return null;
        }

        $user = User::find($areaDb["responsableId"]);
        $name = "Sin responsable asignado";

        $areaDto = new AreaDTO(
            $areaDb->id,
            $areaDb['nombre'],
            $areaDb["siglas"],
            $name,
            $areaDb["created_at"],
            $areaDb["secretariaId"]
        );
        
        return $areaDto;
    }

    public function setResponsableArea(User $user)
    {
        $area = Area::where('id', $user->areaId)
            ->update(['responsableId' => $user->id]);
        return $area;
    }
    /**
     *  Get the performance of the dimensions of the area
     * @param $evaluaciones
     * @param bool $getAllDimensions
     * @return array
     */
    public static function getDimensionPerformance($evaluaciones, $getAllDimensions = false)
    {
        $dimensionesResult = [];
        $divideBy = 1;
        foreach ($evaluaciones as $evaluacion) {
            $indicador = Indicador::find($evaluacion["indicadorId"]);
            if (!$indicador) {
                throw new \Exception("Indicador de evaluacion no encontrado, id:" . $evaluacion["indicadorId"]);
            }
            $dimension = Dimension::find($indicador["dimensionId"]);
            if (!$dimension) {
                throw new \Exception("Dimension no encontrada id:" . $indicador["dimensionId"]);
            }
            if (!isset($dimensionesResult[$dimension->id])) {
                $dimensionData = new DimensionData();
                $dimensionData->id = $dimension->id;
                $dimensionData->nombre = $dimension["nombre"];
                $dimensionData->value = $evaluacion["rendimiento"];
                $dimensionesResult[$dimension->id] = $dimensionData;
                $dimensionesResult[$dimension->id]->divideBy = $divideBy;
                $divideBy++;
            } else {
                $dimensionesResult[$dimension->id]->value += $evaluacion["rendimiento"];
                $dimensionesResult[$dimension->id]->divideBy++;
                $divideBy++;
            }
        }
        if ($getAllDimensions) {
            $allDimensions = Dimension::all();
            foreach ($allDimensions as $dimension) {
                if (!isset($dimensionesResult[$dimension->id])) {
                    $dimensionData = new DimensionData();
                    $dimensionData->id = $dimension->id;
                    $dimensionData->nombre = $dimension["nombre"];
                    $dimensionData->value = 0;
                    $dimensionData->divideBy = 1;
                    $dimensionesResult[$dimension->id] = $dimensionData;
                }
            }
        }
        foreach ($dimensionesResult as $dimension) {
            $dimension->value = $dimension->value / $dimension->divideBy;
        }
        return $dimensionesResult;
    }
    public function getDimensionesReport($incluirTodasEvaluaciones = false, $incluirTodasDimensiones = false)
    {
        $areas = Area::all();
        $response = [];
        foreach ($areas as $area) {
            $evaluaciones = self::getEvaluacionesFinalizadas($area, $incluirTodasEvaluaciones);
            $dimensionPerformanceCharges = self::getDimensionPerformance($evaluaciones, $incluirTodasDimensiones);
            if (count($evaluaciones) == 0) {
                continue;
            }
            if (count($dimensionPerformanceCharges) == 0) {
                continue;
            }
            $indicePAreaViewModel = new IndicePAreaViewModel();
            $indicePAreaViewModel->areaId = $area["id"];
            $indicePAreaViewModel->areaNombre = $area["nombre"];
            $indicePAreaViewModel->areaSiglas = $area["siglas"];
            $indicePAreaViewModel->dimensionesResult = $dimensionPerformanceCharges;
            $response[] = $indicePAreaViewModel;
        }
        return $response;
    }
    private static function getEvaluacionesFinalizadas($area, $incluirTodos = false)
    {
        if ($incluirTodos == "true") {
            $evaluaciones = Evaluacion::where('areaId', "=", $area->id)
                ->get();
            return $evaluaciones;
        } else {
            $evaluaciones = Evaluacion::where(function ($query) use ($area) {
                $query->where('areaId', "=", $area->id)
                    ->where('finalizado', "=", 1);
            })->get();
            return $evaluaciones;
        }
    }
}
