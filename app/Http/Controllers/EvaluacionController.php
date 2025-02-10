<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Dimension;
use App\Models\Evaluacion;
use App\Models\EvaluacionResult;
use App\Models\Indicador;
use App\Models\Secretaria;
use App\Models\User;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use function Illuminate\Log\log;

class EvaluacionController extends BaseController
{
    public function index()
    {
        return view('evaluacion.index');
    }
    public function post(Request $request)
    {
        try {
            // Agregamos validación al request para mantener integridad en el información
            [$data, $evaluacion_found, $error] = $this->get_evaluacion_from_req($request);
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
            $secretaria = $this->resolve_secretaria_by_areaId($user['areaId']);
            if ($evaluacion_found) {
                $evaluacion_found->update($data);
                return response()->json([
                    'status' => 'success',
                    'data' => $evaluacion_found->id,
                    'statusCode' => 200
                ], 200);
            }
            $data['secretariaId'] = $secretaria["id"]; //Agregamos el id de la secretaria al request
            $data['secretaria'] = $secretaria["nombre"];
            $data['usuarioId'] = $user->id; //Agregamos el id del usuario al request
            $id = Evaluacion::create($data)->id;
            [$variablesId, $evaluacionesId, $error] = $this->create_variables($data, $fechas_captura, $id, $user);
            if ($error) {
                Evaluacion::find($id)->delete();
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
    private function create_variables($data, $fechas_captura, $id, $user)
    {
        try {
            [$variables_valor, $evaluation_results] = $this->create_capture_dates(
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

        [$data, $evaluacion, $error] = $this->get_evaluacion_from_req($request);
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
            if ($search !== '') {
                $evaluaciones = Evaluacion::where('descripcion', 'like', "%$search%")
                    ->orderBy($sort, $order)
                    ->limit($limit)
                    ->offset($offset)
                    ->get();
                $totalRows = $evaluaciones::where('descripcion', 'like', "%$search%")
                    ->count();
                $grandTotalRows = $totalRows;
                $totalPages = ceil($totalRows / $limit);
            } else {
                $evaluaciones = Evaluacion::orderBy($sort, $order)
                    ->limit($limit)
                    ->offset($offset)
                    ->get();
                $grandTotalRows = Evaluacion::count();
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

    function get_evaluacion_stats_req($id)
    {
        $evaluacion = Evaluacion::find($id);
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
            log::info($aprobado["resultado"]);
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
        Log::info($evaluacion);
        return $evaluacion;
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
    protected function get_evaluacion_from_req(Request $request)
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

    private function resolve_secretaria_by_areaId($areaId)
    {
        $area = Area::find($areaId);
        $secretaria = Secretaria::find($area["secretariaId"]);
        if (!$secretaria) {
            throw new \Exception('No se encontró la secretaria');
        }
        return $secretaria;
    }

    public static function create_capture_dates(
        $fechas_captura,
        $evaluacion_id,
        $indicador_id,
        $user_id,
    ) {
        $variables_valor = [];
        $evaluation_results = [];
        $variables = Indicador::find($indicador_id)->variables;

        foreach ($fechas_captura as $fecha) {
            $evaluacion_result = [
                'id' => null,
                'evaluacionId' => $evaluacion_id,
                'resultado' => 0,
                'status' => 'pendiente',
                'fecha' => $fecha->fecha_captura,
                'aprobadoPorId' => null
            ];

            \App\Models\EvaluacionResult::insert($evaluacion_result);
            $lastInsertedId = \App\Models\EvaluacionResult::latest()->first();
            $evaluation_results[] = $evaluacion_result;
            foreach ($variables as $variable) {
                $variable_valor = [
                    'evaluacionResultId' => $lastInsertedId->id,
                    'fecha' => $fecha->fecha_captura,
                    'valor' => 0,
                    'meta_esperada' => floatval($fecha->meta),
                    'evaluacionId' => $evaluacion_id,
                    'variableId' => $variable->id,
                    'usuarioId' => $user_id,
                    'status' => 'pendiente',
                ];
                $db_variable = \App\Models\VariableValue::insert($variable_valor);
                $variables_valor[] = $db_variable;
            }
        }
        return [$variables_valor, $evaluation_results];
    }
}
