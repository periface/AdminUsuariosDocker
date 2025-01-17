<?php

namespace App\Http\Controllers;

use App\Models\Dimension;
use App\Models\Indicador;
use App\Models\Secretaria;
use App\Models\Variable;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class IndicadorController extends BaseController
{

    // SI NO SE MIDE, CREAN ESQUEMAS
    public function index()
    {
        return view('indicador.index');
    }
    public function dimension_indicadores(Request $request)
    {
        $dimensionId = $request->dimensionId;
        $dimension = Dimension::find($dimensionId);
        if (!$dimension) {
            return redirect()->route('dimension.index');
        }
        return view('indicadores.dimension_indicadores', [
            'dimension' => $dimension,
            'dimensionId' => $dimensionId
        ]);
    }

    private function resolve_secretaria($secretariaId)
    {
        $secretaria = Secretaria::find($secretariaId);
        if (!$secretaria) {
            throw new \Exception('No se encontró la secretaria');
        }
        return $secretaria;
    }

    public function create_variables(array $variables, int $indicadorId)
    {
        foreach ($variables as $variable) {

            Log::info($variables);
            $variable_exists = Variable::where('code', $variable->code)
                ->where('indicadorId', $indicadorId)->first();
            if ($variable_exists) {
                continue;
            }
            $db_variable = new Variable();
            $db_variable["code"] = $variable->code;
            $db_variable["nombre"] = $variable->literal;
            $db_variable["indicadorId"] = $indicadorId;
            Log::info($db_variable);
            $db_variable->save();
        }
    }
    public function post(Request $request)
    {
        try {

            // Agregamos validación al request para mantener integridad en el información
            [$data, $indicador_found, $error] = $this->get_indicador_from_req($request);
            $variables = $request->variables;
            if (!$variables) {
                $variables = "[]";
            }
            $variables = json_decode($variables);
            if ($error) {
                return response()->json([
                    'status' => 'error',
                    'data' => null,
                    'statusCode' => 422,
                    'error' => $error
                ], 422);
            }

            $user = (Auth::user()); //Obtenemos el usuario autenticado
            $secretaria = $this->resolve_secretaria($user['secretariaId']);
            if ($indicador_found) {
                $indicador_found['secretariaId'] = $secretaria->id; //Agregamos el id de la secretaria al request
                $indicador_found['secretaria'] = $secretaria["nombre"];
                $indicador_found->update($data);
                if ($indicador_found["indicador_confirmado"] == 1) {
                    $this->create_variables($variables, $indicador_found->id);
                }
                return response()->json([
                    'status' => 'success',
                    'data' => $indicador_found->id,
                    'statusCode' => 200
                ], 200);
            }
            $data['secretariaId'] = $user["secretariaId"]; //Agregamos el id de la secretaria al request
            $data['secretaria'] = $secretaria["nombre"];
            // Si el validación se cumple, guardamos en el base de datos
            $id = Indicador::create($data)->id;

            // Una vez guardado en el base de datos enviamos respuesta exitosa a el vista
            return response()->json([
                'status' => 'success',
                'data' => intval($id),
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
    public function get(Request $request)
    {
        try {

            if (!$request->id) {
                $indicadores = Indicador::all();
                return response()->json([
                    'status' => 'success',
                    'data' => $indicadores,
                    'statusCode' => 200
                ], 200);
            }

            $indicador = Indicador::find($request->id);
            if (!$indicador) {
                return response()->json([
                    'status' => 'error',
                    'data' => null,
                    'error' => 'No se encontró el indicador',
                    'statusCode' => 404
                ], 404);
            }
            return response()->json([
                'status' => 'success',
                'data' => $indicador,
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

        [$data, $indicador, $error] = $this->get_indicador_from_req($request);
        if ($error) {
            return response()->json([
                'status' => 'error',
                'error' => $error,
                'data' => null,
                'statusCode' => 422
            ], 422);
        }
        try {
            $indicador->update($data);
            return response()->json([
                'status' => 'success',
                'data' => $indicador,
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
            $indicador = Indicador::find($request->id);
            if (!$indicador) {
                return response()->json([
                    'status' => 'error',
                    'error' => 'No se encontró el indicador',
                    'data' => null,
                    'statusCode' => 404
                ], 404);
            }
            $indicador->delete();
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
     * Función para obtener las filas de el tabla de indicadores
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function get_rows(Request $request, int $dimensionId)
    {
        $page = $request->input("page") ?? 1;
        $limit = $request->input("limit") ?? 10;
        $offset = ($page - 1) * $limit;
        $sort = $request->input("sort") ?? 'id';
        $order = $request->input("order") ?? 'desc';
        $search = $request->input("search") ?? '';
        $indicadores = [];
        $grandTotalRows = 0;
        $totalRows = 0;
        $totalPages = 0;
        $error = null;
        try {
            if ($search !== '') {
                $indicadores = Indicador::where('nombre', 'like', "%$search%")
                    ->orWhere('descripcion', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%")
                    ->where('dimensionId', $dimensionId)
                    ->orderBy($sort, $order)
                    ->limit($limit)
                    ->offset($offset)
                    ->get();
                $totalRows = $indicadores::where('dimensionId', $dimensionId)->count();
                $grandTotalRows = $totalRows;
                $totalPages = ceil($totalRows / $limit);
            } else {
                $indicadores = Indicador::orderBy($sort, $order)
                    ->where('dimensionId', $dimensionId)
                    ->limit($limit)
                    ->offset($offset)
                    ->get();
                $grandTotalRows = Indicador::where('dimensionId', $dimensionId)->count();
                $totalRows = $grandTotalRows;
                $totalPages = ceil($totalRows / $limit);
            }
            // return using compact
        } catch (\Throwable $_) {
            Log::error($_);
            $error = $_;
            return view('indicadores.table_rows', [
                'indicadores' => $indicadores,
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

        return view('indicadores.table_rows', [
            'indicadores' => $indicadores,
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
     * Función para obtener los campos de una indicador
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function get_indicador_fields(Request $request)
    {
        $id = $request->id;
        $set_formula = $request->set_formula;
        $dimensionId = $request->dimensionId;
        if ($id) {
            $indicador = Indicador::find($id);
            if ($set_formula) {
                return view('indicadores.set_formula', ['indicador' => $indicador, 'dimensionId' => $dimensionId]);
            }
            return view('indicadores.fields', ['indicador' => $indicador, 'dimensionId' => $dimensionId]);
        }
        return view('indicadores.fields', ['indicador' => null, 'dimensionId' => $dimensionId]);
    }

    # Helpers

    /**
     * Función para validar el información de el indicador
     * @param Request $request
     * @return array [array|null, Indicador|null, string|null]
     */
    protected function get_indicador_from_req(Request $request)
    {
        // Agregamos validación al request para mantener integridad en el información

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|min:4',
            'descripcion' => 'required|min:4',
            'status' => 'required',
            'unidad_medida' => 'required',
            'metodo_calculo' => 'nullable',
            'evaluable_formula' => 'nullable',
            'non_evaluable_formula' => 'nullable',
            'indicador_confirmado' => 'nullable|boolean',
            'sentido' => 'required',
            'dimensionId' => 'required',
            'medio_verificacion' => 'required',
            'requiere_anexo' => 'required'
        ]);
        $input_id = $request->id ?? null;
        if ($validator->fails()) {
            return [null, null, $validator->errors()];
        }
        if ($input_id) {
            try {
                $indicador = Indicador::find(intval($input_id));
                if (!$indicador) {
                    return [null, null, 'No se encontró el indicador'];
                }
                return [$validator->validated(), $indicador, null];
            } catch (\Throwable $_) {
                return [null, null, $_];
            }
        }
        return [$validator->validated(), null, null];
    }
}
