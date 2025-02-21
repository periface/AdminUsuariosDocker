<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Dimension;
use App\Models\Secretaria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DimensionController extends BaseController
{

    public function index()
    {   // todo: viewmodelllll para vista principal
        return view('dimension.index');
    }

    public function get_by_name(Request $request)
    {
        if (!$request->name) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'statusCode' => 400,
                'error' => "No se encontró la dimensión"
            ], 400);
        }
        $name = $request->name;
        $dimension = Dimension::where('nombre', $name)->get();
        if ($dimension->count() === 0) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'statusCode' => 404,
                'error' => "No se encontró la evaluación"
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $dimension,
            'statusCode' => 200
        ], 200);
    }
    private function resolve_secretaria($secretariaId)
    {
        $secretaria = Secretaria::find($secretariaId);
        if (!$secretaria) {
            return null;
        }
        return $secretaria;
    }
    public function post(Request $request)
    {
        // Agregamos validación al request para mantener integridad en la información
        [$data, $dimensionFound, $error] = $this->get_dimension_from_req($request);

        if ($error) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'statusCode' => 422,
                'error' => $error
            ], 422);
        }

        $user = (Auth::user()); //Obtenemos el usuario autenticado
        $area = Area::find($user['areaId']);
        $secretaria = $this->resolve_secretaria($area['secretariaId']);
        if (!$secretaria) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'statusCode' => 404,
                'error' => 'No se encontró la secretaría'
            ], 404);
        }
        if ($dimensionFound) {
            $dimensionFound['secretariaId'] = $secretaria->id; //Agregamos el id de la secretaria al request
            $dimensionFound['secretaria'] = $secretaria["nombre"];
            $dimensionFound->update($data);
            return response()->json([
                'status' => 'success',
                'data' => $dimensionFound->id,
                'statusCode' => 200
            ], 200);
        }

        $data['secretariaId'] = $user["secretariaId"]; //Agregamos el id de la secretaria al request
        $data['secretaria'] = $secretaria["nombre"];
        Log::info($data);
        // Si la validación se cumple, guardamos en la base de datos
        $id = Dimension::create($data)->id;

        // Una vez guardado en la base de datos enviamos respuesta exitosa a la vista
        return response()->json([
            'status' => 'success',
            'data' => $id,
            'error' => null,
            'statusCode' => 200
        ], 200);
    }
    public function get(Request $request)
    {
        try {

            if (!$request->id) {
                $dimensiones = Dimension::all();
                return response()->json([
                    'status' => 'success',
                    'data' => $dimensiones,
                    'statusCode' => 200
                ], 200);
            }

            $dimension = Dimension::find($request->id);
            if (!$dimension) {
                return response()->json([
                    'status' => 'error',
                    'data' => null,
                    'error' => 'No se encontró la dimensión',
                    'statusCode' => 404
                ], 404);
            }
            return response()->json([
                'status' => 'success',
                'data' => $dimension,
                'statusCode' => 200
            ], 200);
        } catch (\Throwable $_) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'error' => $_,
                'statusCode' => 500
            ], 500);
        }
    }
    public function put(Request $request)
    {

        [$data, $dimension, $error] = $this->get_dimension_from_req($request);
        if ($error) {
            return response()->json([
                'status' => 'error',
                'error' => $error,
                'data' => null,
                'statusCode' => 422
            ], 422);
        }
        try {
            $dimension->update($data);
            return response()->json([
                'status' => 'success',
                'data' => $dimension,
                'statusCode' => 200
            ], 200);
        } catch (\Throwable $_) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'error' => $_,
                'statusCode' => 500
            ], 500);
        }
    }
    public function delete(Request $request)
    {
        try {
            $dimension = Dimension::find($request->id);
            $indicadores_dimension = $dimension->indicadores;
            if ($indicadores_dimension->count() > 0) {
                return response()->json([
                    'status' => 'error',
                    'error' => 'No se puede eliminar la dimensión porque tiene indicadores asociados',
                    'data' => null,
                    'statusCode' => 400
                ], 400);
            }
            if (!$dimension) {
                return response()->json([
                    'status' => 'error',
                    'error' => 'No se encontró la dimensión',
                    'data' => null,
                    'statusCode' => 404
                ], 404);
            }
            $dimension->delete();
            return response()->json([
                'status' => 'success',
                'data' => request()->id,
                'statusCode' => 200
            ], 200);
        } catch (\Throwable $_) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'error' => $_,
                'statusCode' => 500
            ], 500);
        }
    }

    #Vistas parciales

    /**
     * Función para obtener las filas de la tabla de dimensiones
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function get_rows(Request $request)
    {
        $page = $request->input("page") ?? 1;
        $limit = $request->input("limit") ?? 10;
        $offset = ($page - 1) * $limit;
        $sort = $request->input("sort") ?? 'id';
        $order = $request->input("order") ?? 'desc';
        $search = $request->input("search") ?? '';
        $dimensiones = [];
        $grandTotalRows = 0;
        $totalRows = 0;
        $totalPages = 0;
        try {
            if ($search !== '') {
                $dimensiones = Dimension::where('nombre', 'like', "%$search%")
                    ->orWhere('descripcion', 'like', "%$search%")
                    ->orWhere('sentido', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%")
                    ->orderBy($sort, $order)
                    ->limit($limit)
                    ->offset($offset)
                    ->get();
                $totalRows = $dimensiones->count();
                $grandTotalRows = $totalRows;
                $totalPages = ceil($totalRows / $limit);
            } else {
                $dimensiones = Dimension::orderBy($sort, $order)
                    ->limit($limit)
                    ->offset($offset)
                    ->get();
                $grandTotalRows = Dimension::count();
                $totalRows = $grandTotalRows;
                $totalPages = ceil($totalRows / $limit);
            }
            // return using compact
        } catch (\Throwable $_) {
            Log::error($_);
        }

        return view('dimension.table_rows', [
            'dimensiones' => $dimensiones,
            'totalRows' => $totalRows,
            'grandTotalRows' => $grandTotalRows,
            'totalPages' => $totalPages,
            'page' => $page,
            'limit' => $limit,
            'sort' => $sort,
            'order' => $order,
            'search' => $search
        ]);
    }
    /**
     * Función para obtener los campos de una dimensión
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function get_dimension_fields(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $dimension = Dimension::find($id);
            return view('dimension.fields', ['dimension' => $dimension]);
        }
        return view('dimension.fields', ['dimension' => null]);
    }

    # Helpers

    /**
     * Función para validar la información de la dimensión
     * @param Request $request
     * @return array [array|null, Dimension|null, string|null]
     */
    protected function get_dimension_from_req(Request $request)
    {
        // Agregamos validación al request para mantener integridad en la información
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|min:4',
            'descripcion' => 'required|min:4',
            'status' => 'required'
        ]);
        $input_id = $request->input('id') ?? null;
        if ($validator->fails()) {
            return [null, null, $validator->errors()];
        }
        if ($input_id) {
            try {
                $dimension = Dimension::find(intval($input_id));
                if (!$dimension) {
                    return [null, null, 'No se encontró la dimensión'];
                }
                return [$validator->validated(), $dimension, null];
            } catch (\Throwable $_) {
                return [null, null, $_];
            }
        }
        return [$validator->validated(), null, null];
    }
}
