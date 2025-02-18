<?php

namespace App\Http\Controllers\Api;

use App\Models\Area;
use Illuminate\Http\Request;
use App\Services\AreaService;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class AreaController extends Controller
{

    protected $areaService;

    public function __construct(AreaService $areaService)
    {
        $this->areaService = $areaService;
    }
    public function dimensiones($incluirTodasLasDimensiones, $incluirEvaluacionesAbiertas)
    {
        $report = $this->areaService->getDimensionesReport(
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
    public function index()
    {
        try {

            $areas = $this->areaService->getAllAreas();

            return response()->json([
                'data' => [
                    'attributes' => [
                        'status' => 'success',
                        'data' => $areas,
                        'statusCode' => Response::HTTP_OK
                    ]
                ]
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [
                    'attributes' => [
                        'status' => 'error',
                        'data' => $th->getMessage(),
                        'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR
                    ]
                ]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //
    public function store(Request $request)
    {

        try {

            $request->validate([
                'nombre' => 'required',
                'siglas' => 'required',
                'secretariaId' => 'required'
            ]);

            $area = Area::create($request->all());

            return response()->json([
                'data' => [
                    'attributes' => [
                        'status' => 'success',
                        'data' => $area,
                        'statusCode' => Response::HTTP_CREATED
                    ]
                ]
            ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [
                    'attributes' => [
                        'status' => 'error',
                        'data' => $th->getMessage(),
                        'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR
                    ]
                ]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, Area $area)
    {
        try {
            $request->validate([
                'nombre' => 'required|min:4',
                'secretariaId' => 'required'
            ]);

            $area->update($request->all());

            return response()->json([
                'data' => [
                    'attributes' => [
                        'status' => 'success',
                        'data' => $area,
                        'statusCode' => Response::HTTP_OK
                    ]
                ]
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {

            return response()->json([
                'data' => [
                    'attributes' => [
                        'status' => 'error',
                        'data' => $th->getMessage(),
                        'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR
                    ]
                ]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Area $area)
    {
        try {
            $area->delete();

            return response()->json([
                'data' => [
                    'attributes' => [
                        'status' => 'success',
                        'data' => null,
                        'statusCode' => Response::HTTP_NO_CONTENT
                    ]
                ]
            ], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {

            return response()->json([
                'data' => [
                    'attributes' => [
                        'status' => 'error',
                        'data' => $th->getMessage(),
                        'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR
                    ]
                ]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
