<?php

namespace App\Services;

use App\Models\Evaluacion;
use App\Models\Indicador;
use App\Models\Area;
use App\Models\EvaluacionResult;
use App\Models\User;

class EvaluacionService
{

    function get_evaluacion_stats_req($id)
    {
        $evaluacion = Evaluacion::find($id);
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
    function get_evaluacion_stats(Evaluacion $evaluacion)
    {
        $evaluacion["area"] = Area::find($evaluacion["areaId"]);
        $evaluacion["results"] = EvaluacionResult::where('evaluacionId', $evaluacion->id)->count();

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
        if (!$indicador) {
            return $evaluacion;
        }
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
