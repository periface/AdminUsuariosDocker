<?php

namespace App\Services;

use App\Models\Evaluacion;
use App\Models\Indicador;
use App\Models\Area;
use App\Models\EvaluacionResult;
use App\Models\Secretaria;
use App\Models\User;
use App\Models\VariableValue;

class EvaluacionService
{

    function get_evaluacion_stats_req($id)
    {
        $evaluacion = Evaluacion::find($id);
        return $this->get_report($evaluacion);
    }
    function get_evaluacion_stats(Evaluacion $evaluacion)
    {
        return $this->get_report($evaluacion);
    }

    public function get_by_area($areaId)
    {
        $area = Area::find($areaId);
        $secretaria = Secretaria::find($area["secretariaId"]);
        if (!$secretaria) {
            throw new \Exception('No se encontró la secretaria');
        }
        return $secretaria;
    }
    public static function create_evaluation_result($fecha, $evaluacion_id)
    {
        try {
            $evaluacion_result = [
                'id' => null,
                'evaluacionId' => $evaluacion_id,
                'resultado' => 0,
                'status' => 'pendiente',
                'fecha' => $fecha->fecha_captura,
                'aprobadoPorId' => null
            ];
            $eval_result = EvaluacionResult::create($evaluacion_result);
            return $eval_result;
        } catch (\Exception $e) {
            throw new \Exception('Error al crear el resultado de la evaluación\n' . $e->getMessage());
        }
    }
    public static function create_variable_value($fecha, $evaluacion_id, $variable, $eval_result, $user_id)
    {
        $evaluacion_result = EvaluacionResult::find($eval_result["id"]);
        $variable_valor = [
            'evaluacionResultId' => $evaluacion_result->id,
            'fecha' => $fecha->fecha_captura,
            'valor' => 0,
            'meta_esperada' => floatval($fecha->meta),
            'evaluacionId' => $evaluacion_id,
            'variableId' => $variable->id,
            'usuarioId' => $user_id,
            'status' => 'pendiente',
        ];
        $db_variable = VariableValue::create($variable_valor);
        return $db_variable;
    }

    public function create_capture_dates(
        $fechas_captura,
        $evaluacion_id,
        $indicador_id,
        $user_id,
    ) {
        try {
            $variables_valor = [];
            $evaluation_results = [];
            $variables = Indicador::find($indicador_id)->variables;
            foreach ($fechas_captura as $fecha) {
                $eval_result = $this->create_evaluation_result($fecha, $evaluacion_id);
                $evaluation_results[] = $eval_result;
                foreach ($variables as $variable) {
                    $db_variable = $this->create_variable_value(
                        $fecha,
                        $evaluacion_id,
                        $variable,
                        $eval_result,
                        $user_id
                    );
                    $variables_valor[] = $db_variable;
                }
            }
            return [$variables_valor, $evaluation_results];
        } catch (\Exception $e) {
            throw new \Exception('Error al crear las fechas de captura \n' . $e->getMessage());
        }
    }
    static function get_report($evaluacion)
    {

        $evaluacion["area"] = Area::find($evaluacion["areaId"]);

        $evaluacion["evaluation_results"] = EvaluacionResult::where('evaluacionId', $evaluacion->id)->get();
        $evaluacion["results"] = count($evaluacion["evaluation_results"]);

        $evaluacion["results_capturado"] = EvaluacionResult::where('evaluacionId', $evaluacion->id)
            ->where('status', 'capturado')
            ->count();
        $porcentaje_capturados = 0;
        if ($evaluacion["results"] > 0 && $evaluacion["results_capturado"] > 0) {
            $porcentaje_capturados = $evaluacion["results_capturado"] / $evaluacion["results"] * 100;
        }
        $evaluacion["porcentaje_capturados"] = $porcentaje_capturados;
        $aprobados_count = 0;
        $aprobados = EvaluacionResult::where('evaluacionId', $evaluacion->id)
            ->where('status', 'aprobado')->get();

        $suma_porcentaje_aprobados = 0;
        foreach ($aprobados as $aprobado) {
            $aprobados_count++;
            $suma_porcentaje_aprobados += $aprobado["resultado"];
        }

        $indicador = Indicador::find($evaluacion["indicadorId"]);
        $evaluacion["results_aprobado"] = $aprobados_count;
        $evaluacion["total"] = 0;
        $evaluacion["totalValue"] = 0;
        $porcentaje_aprobados = 0;
        if ($evaluacion["results"] > 0 && $evaluacion["results_aprobado"] > 0) {
            $porcentaje_aprobados = $evaluacion["results_aprobado"] / $evaluacion["results"] * 100;
            $total =  strval($suma_porcentaje_aprobados / $aprobados_count);
            $evaluacion["totalValue"] = $total;
            $evaluacion["total"] = Indicador::get_value($total, $indicador["unidad_medida"]);
        }
        $evaluacion["porcentaje_aprobados"] = $porcentaje_aprobados;
        $evaluacion["results_rechazado"] = EvaluacionResult::where('evaluacionId', $evaluacion->id)
            ->where('status', 'rechazado')
            ->count();
        $porcentaje_rechazados = 0;
        if ($evaluacion["results"] > 0 && $evaluacion["results_rechazado"] > 0) {
            $porcentaje_rechazados = $evaluacion["results_rechazado"] / $evaluacion["results"] * 100;
        }
        $evaluacion["porcentaje_rechazados"] = $porcentaje_rechazados;
        $evaluacion["results_pendiente"] = EvaluacionResult::where('evaluacionId', $evaluacion->id)
            ->where('status', 'pendiente')
            ->count();
        $porcentaje_pendientes = 0;
        if ($evaluacion["results"] > 0 && $evaluacion["results_pendiente"] > 0) {
            $porcentaje_pendientes = $evaluacion["results_pendiente"] / $evaluacion["results"] * 100;
        }
        $evaluacion["porcentaje_pendientes"] = $porcentaje_pendientes;
        $evaluacion["metaValue"] = $evaluacion["meta"];
        $evaluacion["meta"] = Indicador::get_value($evaluacion["meta"], $indicador["unidad_medida"]);
        $evaluacion["sentido"] = $indicador["sentido"];
        // porcentaje total de evaluaciones aprobadas en relacion a la meta
        $evaluacion["indicador"] = $indicador;
        $evaluacion["finalizado_por_user"] = null;
        if ($evaluacion["finalizado_por"]) {
            $evaluacion["finalizado_por_user"] = User::find($evaluacion["finalizado_por"]);
        }
        return $evaluacion;
    }
}
