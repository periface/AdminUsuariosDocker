<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use App\Models\IndicadorCategoria;
use App\Models\Secretaria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CategoriasController extends Controller
{

    public function index()
    {   // todo: viewmodelllll para vista principal
        return view('categoria.index');
    }

    public function get_by_name(Request $request)
    {
        if (!$request->name) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'statusCode' => 400,
                'error' => "No se encontró la categoria"
            ], 400);
        }
        $name = $request->name;
        $categoria = IndicadorCategoria::where('nombre', $name)->get();
        if ($categoria->count() === 0) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'statusCode' => 404,
                'error' => "No se encontró la categoria"
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $categoria,
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
        [$data, $categoriaFound, $error] = $this->get_categoria_from_req($request);

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
        if ($categoriaFound) {
            $categoriaFound['secretariaId'] = $secretaria->id; //Agregamos el id de la secretaria al request
            $categoriaFound['secretaria'] = $secretaria["nombre"];
            $categoriaFound->update($data);
            return response()->json([
                'status' => 'success',
                'data' => $categoriaFound->id,
                'statusCode' => 200
            ], 200);
        }

        $data['secretariaId'] = $area["secretariaId"]; //Agregamos el id de la secretaria al request
        $data['secretaria'] = $secretaria["nombre"];
        // Si la validación se cumple, guardamos en la base de datos
        $id = IndicadorCategoria::create($data)->id;
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
                $categorias = IndicadorCategoria::all();
                return response()->json([
                    'status' => 'success',
                    'data' => $categorias,
                    'statusCode' => 200
                ], 200);
            }

            $categoria = IndicadorCategoria::find($request->id);
            if (!$categoria) {
                return response()->json([
                    'status' => 'error',
                    'data' => null,
                    'error' => 'No se encontró la categoria',
                    'statusCode' => 404
                ], 404);
            }
            return response()->json([
                'status' => 'success',
                'data' => $categoria,
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

        [$data, $categoria, $error] = $this->get_categoria_from_req($request);
        if ($error) {
            return response()->json([
                'status' => 'error',
                'error' => $error,
                'data' => null,
                'statusCode' => 422
            ], 422);
        }
        try {
            $categoria->update($data);
            return response()->json([
                'status' => 'success',
                'data' => $categoria,
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
            $categoria = IndicadorCategoria::find($request->id);
            $indicadores_categoria = $categoria->indicadores;
            if ($indicadores_categoria->count() > 0) {
                return response()->json([
                    'status' => 'error',
                    'error' => 'No se puede eliminar la categoria porque tiene indicadores asociados',
                    'data' => null,
                    'statusCode' => 400
                ], 400);
            }
            if (!$categoria) {
                return response()->json([
                    'status' => 'error',
                    'error' => 'No se encontró la categoria',
                    'data' => null,
                    'statusCode' => 404
                ], 404);
            }
            $categoria->delete();
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
     * Función para obtener las filas de la tabla de categorias
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
        $categorias = [];
        $grandTotalRows = 0;
        $totalRows = 0;
        $totalPages = 0;
        try {
            if ($search !== '') {
                $categorias = IndicadorCategoria::where('nombre', 'like', "%$search%")
                    ->orWhere('descripcion', 'like', "%$search%")
                    ->orWhere('sentido', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%")
                    ->orderBy($sort, $order)
                    ->limit($limit)
                    ->offset($offset)
                    ->get();
                $totalRows = $categorias->count();
                $grandTotalRows = $totalRows;
                $totalPages = ceil($totalRows / $limit);
            } else {
                $categorias = IndicadorCategoria::orderBy($sort, $order)
                    ->limit($limit)
                    ->offset($offset)
                    ->get();
                $grandTotalRows = IndicadorCategoria::count();
                $totalRows = $grandTotalRows;
                $totalPages = ceil($totalRows / $limit);
            }
            // return using compact
        } catch (\Throwable $_) {
            Log::error($_);
        }

        return view('categoria.table_rows', [
            'categorias' => $categorias,
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
     * Función para obtener los campos de una categoria
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function get_categoria_fields(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $categoria = IndicadorCategoria::find($id);
            return view('categoria.fields', ['categoria' => $categoria]);
        }
        return view('categoria.fields', ['categoria' => null]);
    }

    # Helpers

    /**
     * Función para validar la información de la categoria
     * @param Request $request
     * @return array [array|null, IndicadorCategoria|null, string|null]
     */
    protected function get_categoria_from_req(Request $request)
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
                $categoria = IndicadorCategoria::find(intval($input_id));
                if (!$categoria) {
                    return [null, null, 'No se encontró la categoria'];
                }
                return [$validator->validated(), $categoria, null];
            } catch (\Throwable $_) {
                return [null, null, $_];
            }
        }
        return [$validator->validated(), null, null];
    }
}
