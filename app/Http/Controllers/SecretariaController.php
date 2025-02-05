<?php

namespace App\Http\Controllers;

use App\Models\Secretaria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\text;

class SecretariaController extends Controller
{
    public function index()
    {
        return view('secretaria.index');
    }
    public function get()
    {
        $user = (Auth::user());
        $secretarias = [];
        $secretariaId = $user['secretariaId'];
        if ($user['role'] === 'ADMIN') {
            $secretarias = Secretaria::all();
        } else {
            $secretarias = Secretaria::where('id', $secretariaId)->get();
        }
        return response()->json($secretarias);
    }

    #Vistas parciales

    /**
     * Función para obtener las filas de el tabla de secretarias
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
        $secretarias = [];
        $grandTotalRows = 0;
        $totalRows = 0;
        $totalPages = 0;
        $error = null;
        try {
            if ($search !== '') {
                $secretarias = Secretaria::where('nombre', 'like', "%$search%")
                    ->orWhere('nombre', 'like', "%$search%")
                    ->orWhere('siglas', 'like', "%$search%")
                    ->orderBy($sort, $order)
                    ->limit($limit)
                    ->offset($offset)
                    ->get();
                $totalRows = $secretarias::count();
                $grandTotalRows = $totalRows;
                $totalPages = ceil($totalRows / $limit);
            } else {
                $secretarias = Secretaria::orderBy($sort, $order)
                    ->limit($limit)
                    ->offset($offset)
                    ->get();
                $grandTotalRows = Secretaria::count();
                $totalRows = $grandTotalRows;
                $totalPages = ceil($totalRows / $limit);
            }
            // return using compact
        } catch (\Throwable $_) {
            Log::error($_);
            $error = $_;
            return view('secretaria.table_rows', [
                'secretarias' => $secretarias,
                'totalRows' => $totalRows,
                'grandTotalRows' => $grandTotalRows,
                'totalPages' => $totalPages,
                'page' => $page,
                'limit' => $limit,
                'sort' => $sort,
                'order' => $order,
                'search' => $search,
                'error' => $error,
            ]);
        }

        return view('secretaria.table_rows', [
            'secretarias' => $secretarias,
            'totalRows' => $totalRows,
            'grandTotalRows' => $grandTotalRows,
            'totalPages' => $totalPages,
            'page' => $page,
            'limit' => $limit,
            'sort' => $sort,
            'order' => $order,
            'search' => $search,
            'error' => null,
        ]);
    }

    /**
     * Función para obtener los campos de una secretaria
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function get_secretaria_fields(Request $request)
    {
        $id = $request->id;
        if (!$id) {
            return view('secretaria.fields', ['secretaria' => null]);
        }
        $secretaria = Secretaria::find($id);
        return view('secretaria.fields', ['secretaria' => $secretaria]);
    }

    /**
     * Función para validar el información de el secretaria
     * @param Request $request
     * @return array [array|null, Secretaria|null, string|null]
     */
    protected function get_secretaria_from_req(Request $request)
    {
        // Agregamos validación al request para mantener integridad en el información

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|min:4',
            'siglas' => 'required|min:2',
        ]);
        $input_id = $request->id ?? null;
        if ($validator->fails()) {
            return [null, null, $validator->errors()];
        }
        if ($input_id) {
            try {
                $secretaria = Secretaria::find(intval($input_id));
                if (!$secretaria) {
                    return [null, null, 'No se encontró la secretaria'];
                }
                return [$validator->validated(), $secretaria, null];
            } catch (\Throwable $_) {
                return [null, null, $_];
            }
        }
        return [$validator->validated(), null, null];
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        try {
            $secretaria = Secretaria::find($id);
            if (!$secretaria) {
                return response()->json([
                    'status' => 'error',
                    'data' => null,
                    'error' => 'No se encontró la secretaria',
                    'statusCode' => 404
                ], 404);
            }
            $secretaria->delete();
            return response()->json([
                'status' => 'success',
                'data' => $id,
                'error' => null,
                'statusCode' => 200
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'error' => $e->getMessage(),
                'statusCode' => 500
            ], 500);
        }
    }
    public function post(Request $request)
    {
        try {
            [$data, $secretaria_found, $error] = $this->get_secretaria_from_req($request);
            if ($error) {
                return response()->json([
                    'status' => 'error',
                    'data' => null,
                    'statusCode' => 422,
                    'error' => $error
                ], 422);
            }
            if ($secretaria_found) {
                Log::info($secretaria_found);
                $secretaria_found->update($data);
                return response()->json([
                    'status' => 'success',
                    'data' => $secretaria_found["id"],
                    'statusCode' => 200
                ], 200);
            }
            // Si el validación se cumple, guardamos en el base de datos
            log::info($data);
            $id = Secretaria::create($data)->id;
            // Una vez guardado en el base de datos enviamos respuesta exitosa a el vista
            return response()->json([
                'status' => 'success',
                'data' => intval($id),
                'error' => null,
                'statusCode' => 200
            ], 200);
        } catch (\Throwable $e) {
            Log::error($e);
            return response()->json([
                'status' => 'error',
                'data' => null,
                'error' => $e,
                'statusCode' => 500
            ], 500);
        }
    }
    public function put(Request $request)
    {
        [$data, $secretaria, $error] = $this->get_secretaria_from_req($request);
        if ($error) {
            return response()->json([
                'status' => 'error',
                'error' => $error,
                'data' => null,
                'statusCode' => 422
            ], 422);
        }
        try {
            $secretaria->update($data);
            return response()->json([
                'status' => 'success',
                'data' => $secretaria,
                'statusCode' => 200
            ], 200);
        } catch (\Throwable $_) {
            Log::error($_);
            return response()->json([
                'status' => 'error',
                'data' => null,
                'error' => $_,
                'statusCode' => 500
            ], 500);
        }
    }
}
