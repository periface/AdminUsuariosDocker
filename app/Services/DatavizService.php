<?php

namespace App\Services;

use App\Models\Dimension;
use App\Models\Evaluacion;
use App\Models\Indicador;

use App\Models\Area;

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
class DatavizService
{
    private static function getCategoriasPerformance($evaluaciones)
    {
        $dimensionesResult = [];
        $divideBy = 1;
        foreach ($evaluaciones as $evaluacion) {
            $indicador = Indicador::find($evaluacion["indicadorId"]);
            if (!$indicador) {
                throw new \Exception("Indicador de evaluacion no encontrado, id:" . $evaluacion["indicadorId"]);
            }
            $categoria = $indicador["categoria"];
            if (!$categoria) {
                throw new \Exception("Categoria no encontrada id:" . $indicador["dimensionId"]);
            }
            if (!isset($dimensionesResult[$categoria])) {
                $dimensionData = new DimensionData();
                $dimensionData->id = $categoria;
                $dimensionData->nombre = $categoria;
                $dimensionData->value = $evaluacion["rendimiento"];
                $dimensionesResult[$categoria] = $dimensionData;
                $dimensionesResult[$categoria]->divideBy = $divideBy;
                $divideBy++;
            } else {
                $dimensionesResult[$categoria]->value += $evaluacion["rendimiento"];
                $dimensionesResult[$categoria]->divideBy++;
                $divideBy++;
            }
        }
        foreach ($dimensionesResult as $dimension) {
            $dimension->value = $dimension->value / $dimension->divideBy;
        }
        return $dimensionesResult;
    }
    /**
     *  Get the performance of the dimensions of the area
     * @param $evaluaciones
     * @param bool $getAllDimensions
     * @return array
     */
    private static function getDimensionPerformance($evaluaciones, $getAllDimensions = false)
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
    public function getCategoriasReport($id, $incluirtodasEvaluaciones)
    {
        $areas = [];
        if ($id != null && $id != "0") {
            $areas = Area::where('id', $id)
                ->get();
        } else {
            $areas = Area::all();
        }
        $response = [];
        foreach ($areas as $area) {
            $evaluaciones = self::getEvaluacionesFinalizadas($area, $incluirtodasEvaluaciones);
            $dimensionPerformanceCharges = self::getCategoriasPerformance($evaluaciones);
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
    public function getDimensionesReport($id, $incluirTodasEvaluaciones = false, $incluirTodasDimensiones = false)
    {
        $areas = [];
        $response = [];
        if ($id != null && $id != "0") {
            $areas = Area::where('id', $id)
                ->get();
        } else {
            $areas = Area::all();
        }
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
