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

use function Illuminate\Log\log;

class DimensionData
{
    public $id;
    public $nombre;
    public $value;
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

    public function getAreaById(Area $area)
    {
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

    public function setResponsableArea(User $user)
    {
        $area = Area::where('id', $user->areaId)
            ->update(['responsableId' => $user->id]);
        return $area;
    }
    private static function getEvaluationPerformanceValue($indicador, $evaluacion)
    {
        $resultados = EvaluacionResult::where('evaluacionId', $evaluacion->id)
            ->get();
        $suma_porcentaje = $resultados->sum('resultado');
        $resultados_count = $resultados->count();
        if ($resultados_count == 0) {
            return 0;
        }
        $sentido = $indicador["sentido"];
        $meta = $evaluacion["meta"];
        if ($meta == 0) {
            return 0;
        }
        if ($suma_porcentaje == 0) {
            return 0;
        }
        if ($sentido == "ascendente") {
            return min(100, ($suma_porcentaje / $meta) * 100);
        }

        if ($sentido == "descendente") {
            return min(100, ($meta / $suma_porcentaje) * 100);
        }

        if ($sentido == "constante") {
            $error = abs($suma_porcentaje - $meta);
            $desempeño = max(0, 100 - (($error / $meta) * 100));
            return $desempeño;
        }

        return 0; // Si el sentido no es válido

    }
    public static  function getDimensionInfo($evaluaciones, $addMissingDimensions = false)
    {
        $dimensionesResult = [];
        foreach ($evaluaciones as $evaluacion) {
            $indicador = Indicador::find($evaluacion["indicadorId"]);
            if (!$indicador) {
                continue;
            }
            $dimension = Dimension::find($indicador["dimensionId"]);
            if (!$dimension) {
                continue;
            }
            $evaluacion_value = self::getEvaluationPerformanceValue($indicador, $evaluacion);
            if (!isset($dimensionesResult[$dimension->id])) {
                $dimensionData = new DimensionData();
                $dimensionData->id = $dimension->id;
                $dimensionData->nombre = $dimension["nombre"];
                $dimensionData->value = $evaluacion_value;
                $dimensionesResult[$dimension->id] = $dimensionData;
            } else {
                $dimensionesResult[$dimension->id]->value += $evaluacion_value;
            }
        }
        if ($addMissingDimensions) {
            $allDimensions = Dimension::all();
            foreach ($allDimensions as $dimension) {

                if (!isset($dimensionesResult[$dimension->id])) {
                    $dimensionData = new DimensionData();
                    $dimensionData->id = $dimension->id;
                    $dimensionData->nombre = $dimension["nombre"];
                    $dimensionData->value = 0;
                    $dimensionesResult[$dimension->id] = $dimensionData;
                }
            }
        }
        return $dimensionesResult;
    }
    public function getDimensionesReport($incluirTodasEvaluaciones = false, $incluirTodasDimensiones = false)
    {
        $areas = Area::all();
        $response = [];
        foreach ($areas as $area) {
            $evaluaciones = self::getEvaluacionesFinalizadas($area, $incluirTodasEvaluaciones);
            $dimensionInfo = self::getDimensionInfo($evaluaciones, $incluirTodasDimensiones);
            if (count($evaluaciones) == 0) {
                continue;
            }
            if (count($dimensionInfo) == 0) {
                continue;
            }
            $indicePAreaViewModel = new IndicePAreaViewModel();
            $indicePAreaViewModel->areaId = $area["id"];
            $indicePAreaViewModel->areaNombre = $area["nombre"];
            $indicePAreaViewModel->areaSiglas = $area["siglas"];
            $indicePAreaViewModel->dimensionesResult = $dimensionInfo;
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
