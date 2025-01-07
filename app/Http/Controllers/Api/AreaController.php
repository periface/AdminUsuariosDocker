<?php

namespace App\Http\Controllers\Api;

use App\Models\Area;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class AreaController extends Controller
{

    //
    public function store(Request $request){
        try {

            $request->validate([
                'nombre' => 'required',
                'siglas' => 'required'
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
}
