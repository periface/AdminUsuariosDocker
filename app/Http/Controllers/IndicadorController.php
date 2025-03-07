<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Dimension;
use App\Models\Evaluacion;
use App\Models\Indicador;
use App\Models\IndicadorCategoria;
use App\Models\Secretaria;
use App\Models\Variable;
use App\Services\EvaluacionService;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class IndicadorController extends BaseController
{
    public $evaluacionService;
    // SI NO SE MIDE, CREAN ESQUEMAS
    public function __construct(EvaluacionService $evaluacionService)
    {
        $this->evaluacionService = $evaluacionService;
    }
    public function index()
    {
        $dimensiones = Dimension::all();
        return view('indicadores.index', compact('dimensiones'));
    }
    public function details($id)
    {
        $indicador = Indicador::find($id);
        if (!$indicador) {
            return redirect()->route('indicador.index');
        }
        $evaluaciones = Evaluacion::where('indicadorId', $id)->get();
        foreach ($evaluaciones as $evaluacion) {
            $evaluacion = $this->evaluacionService->get_evaluacion_stats($evaluacion);
        }
        $dimension = Dimension::find($indicador["dimensionId"]);
        return view('indicadores.details', compact('indicador', 'evaluaciones', 'dimension'));
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

    private function resolve_secretaria_by_areaId($areaId)
    {
        $area = Area::find($areaId);
        $secretaria = Secretaria::find($area["secretariaId"]);
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
    public function search_by_clave($clave)
    {
        $indicador = Indicador::where('clave', $clave)->first();
        return $indicador;
    }
    public function post(Request $request)
    {
        try {
            // Agregamos validación al request para mantener integridad en el información
            [$data, $indicador_found, $error] = $this->get_indicador_from_req($request);
            $categoria = IndicadorCategoria::find($data["categoriaId"]);
            if (!$categoria) {
                return response()->json([
                    'status' => 'error',
                    'data' => null,
                    'error' => 'No se encontró la categoría',
                    'statusCode' => 404
                ], 404);
            }

            $dimension = Dimension::find($data["dimensionId"]);

            if (!$dimension) {
                return response()->json([
                    'status' => 'error',
                    'data' => null,
                    'error' => 'No se encontró la dimensión',
                    'statusCode' => 404
                ], 404);
            }
            $data["categoria"] =  $categoria["nombre"];;
            $data["dimension"] =  $dimension["nombre"];;
            $variables = $request->variables;

            if (!$variables) {
                $variables = "[]";
            }
            $indicador_by_clave = null;
            // Verificamos si ya existe un indicador con la clave
            // Si la clave es __indicador__ no se valida porque es una clave reservada
            // para los indicadores que se crean de forma manual
            if ($data["clave"] != "__indicador__" && $indicador_found == null) {
                $indicador_by_clave = $this->search_by_clave($data["clave"]);
            }
            if ($indicador_by_clave) {
                return response()->json([
                    'status' => 'error',
                    'data' => null,
                    'error' => 'Ya existe un indicador con la clave ' . $data["clave"],
                    'statusCode' => 422
                ], 422);
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
            $secretaria = $this->resolve_secretaria_by_areaId($user['areaId']);
            if ($indicador_found) {
                $indicador_found['secretariaId'] = $secretaria["id"]; //Agregamos el id de la secretaria al request
                $indicador_found['secretaria'] = $secretaria["nombre"];
                $indicador_found->update($data);
                if ($indicador_found["indicador_confirmado"] == 1) {
                    $this->create_variables($variables, $indicador_found["id"]);
                }
                return response()->json([
                    'status' => 'success',
                    'data' => $indicador_found["id"],
                    'statusCode' => 200
                ], 200);
            }
            $data['secretariaId'] = $secretaria["id"]; //Agregamos el id de la secretaria al request
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
            $evaluaciones = Evaluacion::where('indicadorId', $request->id)->get();

            if (count($evaluaciones) > 0) {
                return response()->json([
                    'status' => 'error',
                    'error' => 'No se puede eliminar el indicador porque tiene evaluaciones asociadas',
                    'data' => null,
                    'statusCode' => 422
                ], 422);
            }
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
    public function get_rows(Request $request, $dimensionId)
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
                if ($dimensionId == 0) {
                    $indicadores = Indicador::where('nombre', 'like', "%$search%")
                        ->where('descripcion', 'like', "%$search%")
                        ->orWhere('status', 'like', "%$search%")

                        ->orWhere('dimension', 'like', "%$search%")

                        ->orWhere('categoria', 'like', "%$search%")
                        ->orderBy($sort, $order)
                        ->limit($limit)
                        ->offset($offset)
                        ->get();
                    $totalRows = $indicadores::count();
                    $grandTotalRows = $totalRows;
                    $totalPages = ceil($totalRows / $limit);
                } else {
                    $indicadores = Indicador::where('nombre', 'like', "%$search%")
                        ->where('dimensionId', $dimensionId)
                        ->orWhere('descripcion', 'like', "%$search%")
                        ->orWhere('status', 'like', "%$search%")
                        ->orderBy($sort, $order)
                        ->limit($limit)
                        ->offset($offset)
                        ->get();
                    $totalRows = $indicadores::where('dimensionId', $dimensionId)->count();
                    $grandTotalRows = $totalRows;
                    $totalPages = ceil($totalRows / $limit);
                }
            } else {
                if ($dimensionId == 0) {
                    $indicadores = Indicador::orderBy($sort, $order)
                        ->limit($limit)
                        ->offset($offset)
                        ->get();
                    $grandTotalRows = Indicador::count();
                    $totalRows = $grandTotalRows;
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
        $dimensiones = Dimension::all();
        $categorias = IndicadorCategoria::all();
        $id = $request->id;
        if (!$id) {
            return view(
                'indicadores.fields',
                [
                    'indicador' => null,
                    'dimensionId' => $request->dimensionId,
                    'dimensiones' => $dimensiones,
                    'categorias' => $categorias
                ]
            );
        }
        $set_formula = $request->set_formula;
        $dimensionId = $request->dimensionId;
        $indicador = Indicador::find($id);

        $categoriaId = $indicador["categoriaId"];
        if ($set_formula) {
            return view(
                'indicadores.set_formula',
                [
                    'indicador' => $indicador,
                    'dimensionId' => $dimensionId,
                    'dimensiones' => $dimensiones,
                    'categorias' => $categorias,
                    'categoriaId' => $categoriaId
                ]
            );
        }
        return view(
            'indicadores.fields',
            [
                'indicador' => $indicador,
                'dimensionId' => $dimensionId,
                'categoriaId' => $categoriaId,
                'dimensiones' => $dimensiones,
                'categorias' => $categorias
            ]
        );
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
            'clave' => 'nullable',
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
            'requiere_anexo' => 'required',
            'medio_verificacion' => 'required',
            'categoria' => 'nullable',
            'categoriaId' => 'required',
        ]);
        $input_id = $request->id ?? null;

        if ($validator->fails()) {
            dd($validator->errors());
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
