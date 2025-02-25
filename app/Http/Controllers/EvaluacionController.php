<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\User;
use App\Models\Dimension;
use App\Models\Indicador;
use App\Models\Evaluacion;
use App\Models\Secretaria;
use Illuminate\Http\Request;
use App\Services\RoleService;
use App\Models\EvaluacionResult;
use App\Models\VariableValue;
use App\Services\EvaluacionService;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller as BaseController;

class EvaluacionController extends BaseController
{

    protected $roleService;

    protected $evaluacionService;

    public function __construct(RoleService $roleService, EvaluacionService $evaluacionService)
    {
        $this->evaluacionService = $evaluacionService;
        $this->roleService = $roleService;
    }
    public function index()
    {
        return view('evaluacion.index');
    }

    public function get_evaluacion_stats_req($id)
    {
        return $this->evaluacionService->get_evaluacion_stats_req($id);
    }
    public function get_evaluacion_stats($evaluacion)
    {
        return $this->evaluacionService->get_evaluacion_stats($evaluacion);
    }
    public function post(Request $request)
    {
        try {
            // Agregamos validación al request para mantener integridad en el información
            [$data, $_, $error] = $this->get_form_body($request);
            if ($error) {
                return response()->json([
                    'status' => 'error',
                    'data' => null,
                    'statusCode' => 422,
                    'error' => $error
                ], 422);
            }
            $fechas_captura = json_decode($data['fechas_captura']);
            $user = (Auth::user()); //Obtenemos el usuario autenticado
            $secretaria = $this->evaluacionService->get_by_area($user['areaId']);
            $data['secretariaId'] = $secretaria["id"]; //Agregamos el id de la secretaria al request
            $data['secretaria'] = $secretaria["nombre"];
            $data['usuarioId'] = $user->id; //Agregamos el id del usuario al request
            $id = Evaluacion::create($data)->id; //Guardamos el evaluacion en la base de datos
            [$variablesId, $evaluacionesId, $error] = $this->make_eval_results($data, $fechas_captura, $id, $user);
            if ($error) {
                return response()->json([
                    'status' => 'error',
                    'data' => null,
                    'error' => $error,
                    'statusCode' => 500
                ], 500);
            }
            // Una vez guardado en el base de datos enviamos respuesta exitosa a el vista
            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $id,
                    'variablesId' => $variablesId,
                    'evaluacionesId' => $evaluacionesId
                ],
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
    private function make_eval_results($data, $fechas_captura, $id, $user)
    {
        try {
            [$variables_valor, $evaluation_results] = $this->evaluacionService->create_capture_dates(
                $fechas_captura,
                $id,
                $data["indicadorId"],
                $user->id,
            );
            return [$variables_valor, $evaluation_results, null];
        } catch (\Throwable $e) {
            log::error($e);
            return [null, null, $e->getMessage()];
        }
    }
    public function cerrar_evaluacion($id)
    {
        try {
            $evaluacion = Evaluacion::find($id);

            $indicador = Indicador::find($evaluacion["indicadorId"]);
            if (!$evaluacion) {
                return response()->json([
                    'status' => 'error',
                    'data' => null,
                    'error' => 'No se encontró el evaluacion',
                    'statusCode' => 404
                ], 404);
            }
            $toggle = !$evaluacion['finalizado'];
            if ($toggle == true) {
                $meta_alcanzada = $this->evaluacionService->get_meta_alcanzada($indicador, $evaluacion);
                $performance = $this->evaluacionService->getIndicadorPerformanceValue($indicador, $evaluacion);
                $evaluacion->update([
                    'finalizado' => $toggle,
                    'finalizado_por' => auth()->user()->id,
                    'finalizado_en' => now(),
                    'rendimiento' => $performance,
                    'meta_alcanzada' => $meta_alcanzada
                ]);
                return response()->json([
                    'status' => 'success',
                    'data' => $evaluacion,
                    'statusCode' => 200
                ], 200);
            }

            $evaluacion->update([
                'finalizado' => $toggle,
                'finalizado_por' => null,
                'finalizado_en' => null,
                'rendimiento' => null,
                'meta_alcanzada' => null
            ]);
            return response()->json([
                'status' => 'success',
                'data' => $evaluacion,
                'statusCode' => 200
            ], 200);
        } catch (\Throwable $e) {
            log::error($e);
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
                $evaluaciones = Evaluacion::all();
                return response()->json([
                    'status' => 'success',
                    'data' => $evaluaciones,
                    'statusCode' => 200
                ], 200);
            }

            $evaluacion = Evaluacion::find($request->id);
            if (!$evaluacion) {
                return response()->json([
                    'status' => 'error',
                    'data' => null,
                    'error' => 'No se encontró el evaluacion',
                    'statusCode' => 404
                ], 404);
            }
            return response()->json([
                'status' => 'success',
                'data' => $evaluacion,
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

        [$data, $evaluacion, $error] = $this->get_form_body($request);
        if ($error) {
            return response()->json([
                'status' => 'error',
                'error' => $error,
                'data' => null,
                'statusCode' => 422
            ], 422);
        }
        try {
            $evaluacion->update($data);
            return response()->json([
                'status' => 'success',
                'data' => $evaluacion,
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
            $evaluacion = Evaluacion::find($request->id);
            if (!$evaluacion) {
                return response()->json([
                    'status' => 'error',
                    'error' => 'No se encontró el evaluacion',
                    'data' => null,
                    'statusCode' => 404
                ], 404);
            }
            $evaluacion->delete();
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
    public static function isAdmin($user)
    {

        $roles = $user->getRoleNames();
        return $roles[0] === "ADM";
    }
    #Vistas parciales

    /**
     * Función para obtener las filas de el tabla de evaluaciones
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
        $evaluaciones = [];
        $grandTotalRows = 0;
        $totalRows = 0;
        $totalPages = 0;
        try {
            /**
             * Inicia valida ROL
             * En este bloque se valida el rol que tenga el usuario logueado
             * para limitar la información que va a consultar dependiendo el ROL
             * con el que cuente.
             */

            $user = auth()->user();
            $responsableArea = Area::where('responsableId', $user->id)->first();
            if (self::isAdmin($user)) {
                $evaluaciones = Evaluacion::all();
            } else {
                // $evaluaciones = Evaluacion::where(function ($query) use ($user, $responsableArea) {
                //     $query->where('areaId', '=', $user->areaId)
                //         ->orWhere('areaId', '=', $responsableArea->id);
                // })->get();
                $evaluaciones = Evaluacion::whereIn('areaId', [$user->areaId])->get();
            }
            /**
             * Fin valida ROL
             */

            if ($search !== '') {
                $evaluaciones = $evaluaciones::where('descripcion', 'like', "%$search%") //Evaluacion::where('descripcion', 'like', "%$search%")
                    ->orderBy($sort, $order)
                    ->limit($limit)
                    ->offset($offset)
                    ->get();
                $totalRows = $evaluaciones::where('descripcion', 'like', "%$search%")
                    ->count();
                $grandTotalRows = $totalRows;
                $totalPages = ceil($totalRows / $limit);
            } else {
                $evaluaciones = $evaluaciones::orderBy($sort, $order) //Evaluacion::orderBy($sort, $order)
                    ->limit($limit)
                    ->offset($offset)
                    ->get();
                $grandTotalRows = $evaluaciones::count(); //Evaluacion::count();
                $totalRows = $grandTotalRows;
                $totalPages = ceil($totalRows / $limit);
            }

            // return using compact
        } catch (\Throwable $_) {
            Log::error($_);
        }

        foreach ($evaluaciones as $evaluacion) {
            $evaluacion = $this->get_evaluacion_stats($evaluacion);
        }
        return view('evaluacion.table_rows', [
            'evaluaciones' => $evaluaciones,
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
     * Función para obtener los campos de una evaluacion
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function get_evaluacion_fields(Request $request)
    {
        $id = $request->id;
        $areas = Area::all();
        foreach ($areas as $area) {
            $_responsable = User::find($area["reponsableId"]);

            log::info($_responsable);
            $area["responsableId"] = $_responsable;
        }
        $dindicadores = Dimension::all();
        if ($id) {
            $evaluacion = Evaluacion::find($id);
            return view('evaluacion.fields', ['evaluacion' => $evaluacion, 'areas' => $areas, 'dimensiones' => $dindicadores]);
        }
        return view('evaluacion.fields', ['evaluacion' => null, 'areas' => $areas, 'dimensiones' => $dindicadores]);
    }
    # Helpers

    /**
     * Función para validar el información de el evaluacion
     * @param Request $request
     * @return array [array|null, Evaluacion|null, string|null]
     */
    protected function get_form_body(Request $request)
    {
        // Agregamos validación al request para mantener integridad en el información

        $validator = Validator::make($request->all(), [
            'areaId' => 'required|integer',
            'indicadorId' => 'required|integer',
            'meta' => 'required|string',
            'frecuencia_medicion' => 'required|string',
            'fecha_fin' => 'required|date',
            'fecha_inicio' => 'required|date',
            'fechas_captura' => 'required|string',
            'evaluable_formula' => 'required|string',
            'non_evaluable_formula' => 'required|string',
            'formula_literal' => 'required|string',
            'descripcion' => 'required|string',
        ]);
        $input_id = $request->id ?? null;
        if ($validator->fails()) {
            return [null, null, $validator->errors()];
        }
        if ($input_id) {
            try {
                $evaluacion = Evaluacion::find(intval($input_id));
                if (!$evaluacion) {
                    return [null, null, 'No se encontró el evaluacion'];
                }
                return [$validator->validated(), $evaluacion, null];
            } catch (\Throwable $_) {
                return [null, null, $_];
            }
        }
        return [$validator->validated(), null, null];
    }
    public function get_evaluacion_details(Request $request)
    {
        try {
            $areaId = $request->areaId;
            $indicadorId = $request->indicadorId;
            if (!$areaId || !$indicadorId) {
                return response()->json([
                    'status' => 'error',
                    'data' => null,
                    'error' => 'No se encontró el área o el indicador',
                    'statusCode' => 404
                ], 404);
            }
            $area = Area::find($areaId);
            // get variables by indicadorId
            $indicador = Indicador::all()->find($indicadorId);
            $variables = $indicador->variables;
            $responsable = User::find($area["responsableId"]);
            $evaluacion = Evaluacion::where('areaId', $areaId)
                ->where('indicadorId', $indicadorId)
                ->orderBy('created_at', 'desc')
                ->first();
            return view('evaluacion.details', [
                'area' => $area,
                'indicador' => $indicador,
                'evaluacion' => $evaluacion,
                'variables' => $variables,
                'responsable' => $responsable
            ]);
        } catch (\Throwable $_) {
            log::error($_);
            return response()->json([
                'status' => 'error',
                'data' => null,
                'error' => $_,
                'statusCode' => 500
            ], 500);
        }
    }


    public function insert_variable_valor($variables, $evaluacion_id, $user_id)
    {
        $variables_valor = [];
        foreach ($variables as $variable) {
            $variable_valor = [
                'valor' => $variable["valor"],
                'meta_esperada' => $variable["meta_esperada"],
                'fecha' => $variable["fecha"],
                'status' => 'pendiente',
                'evaluacionId' => $evaluacion_id,
                'variableId' => $variable["variableId"],
                'usuarioId' => $user_id,
            ];
            $db_variable = VariableValue::create($variable_valor);
            $variables_valor[] = $db_variable;
        }
        return $variables_valor;
    }
    public function ficha($id)
    {
        $evaluacion = Evaluacion::find($id);
        $indicador = Indicador::find($evaluacion["indicadorId"]);
        $area = Area::find($evaluacion["areaId"]);
        return view('evaluacion.ficha', [
            'evaluacion' => $evaluacion,
            'indicador' => $indicador,
            'area' => $area
        ]);
    }
    public function get_chart_data(Request $request)
    {
        $id = $request->id;
        $evaluacion_results = EvaluacionResult::where('evaluacionId', $id)->get();
        response()->json([
            'status' => 'success',
            'data' => $evaluacion_results,
            'statusCode' => 200
        ], 200);
    }
}
