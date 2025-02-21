<?php

namespace App\Http\Controllers\Api;

use App\Services\DatavizService;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class DatavizController extends Controller
{

    protected $datavizService;
    public function __construct(DatavizService $datavizService)
    {
        $this->datavizService = $datavizService;
    }
    public function getPerformanceReport(
        $id = 0,
        $incluirTodasLasEtiquetas = false,
        $incluirEvaluacionesAbiertas,
        $tipo = "dimension"
    ) {
        if ($tipo == "dimensiones") {
            return $this->dimensiones($id, $incluirTodasLasEtiquetas, $incluirEvaluacionesAbiertas);
        }
        if ($tipo == "categorias") {
            return $this->categorias($id, $incluirEvaluacionesAbiertas);
        }
        return response()->json([
            'data' => [
                'attributes' => [
                    'status' => 'error',
                    'data' => 'Tipo de reporte no válido',
                    'statusCode' => Response::HTTP_BAD_REQUEST
                ]
            ]
        ], Response::HTTP_BAD_REQUEST);
    }
    public function categorias($id, $incluirEvaluacionesAbiertas)
    {
        $report = $this->datavizService->getCategoriasReport(
            $id,
            $incluirEvaluacionesAbiertas
        );
        return response()->json([
            'data' => [
                'attributes' => [
                    'status' => 'success',
                    'data' => $report,
                    'statusCode' => Response::HTTP_OK
                ]
            ]
        ], Response::HTTP_OK);
    }
    public function dimensiones($id, $incluirTodasLasDimensiones, $incluirEvaluacionesAbiertas)
    {
        $report = $this->datavizService->getDimensionesReport(
            $id,
            $incluirEvaluacionesAbiertas,
            $incluirTodasLasDimensiones
        );
        return response()->json([
            'data' => [
                'attributes' => [
                    'status' => 'success',
                    'data' => $report,
                    'statusCode' => Response::HTTP_OK
                ]
            ]
        ], Response::HTTP_OK);
    }
}
