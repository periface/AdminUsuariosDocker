<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Evaluacion;
use App\Models\Indicador;
use App\Models\Variable;
use App\Models\VariableValue;
use App\Models\EvaluacionResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class VariableValueModel
{
    public $id;
    public $valor;
    public $meta_esperada;
    public $fecha;
    public $evaluacionId;
    public $variableId;
    public $secretariaId;
    public $usuarioId;
    public $status;
}

class RegistrosController extends BaseController
{
    //
    public function registros($id)
    {
        $evaluacion = Evaluacion::all()->find($id);
        $indicador = Indicador::all()->find($evaluacion["indicadorId"]);
        $area = Area::all()->find($evaluacion["areaId"]);
        return view('registros.index', [
            'evaluacion' => $evaluacion,
            'indicador' => $indicador,
            'evaluacionId' => $id,
            'area' => $area
        ]);
    }
    public function get_rows(request $request, $evaluacionId)
    {
        $page = $request->input("page") ?? 1;
        $limit = $request->input("limit") ?? 10;
        $offset = ($page - 1) * $limit;
        $sort = $request->input("sort") ?? 'id';
        $order = $request->input("order") ?? 'desc';
        $search = $request->input("search") ?? '';
        $evaluacion_results = [];
        $grandTotalRows = 0;
        $totalRows = 0;
        $totalPages = 0;
        $evaluacion = Evaluacion::all()->find($evaluacionId);
        $frecuencia_medicion = $evaluacion["frecuencia_medicion"];
        $indiadorId = $evaluacion["indicadorId"];
        $indicador = Indicador::all()->find($indiadorId);

        $unidad_medida = $indicador["unidad_medida"];
        try {
            if ($search !== '') {
                $evaluacion_results = EvaluacionResult::where('resultado', 'like', "%$search%")
                    ->where('evaluacionId', $evaluacionId)
                    ->orderBy($sort, $order)
                    ->limit($limit)
                    ->offset($offset)
                    ->get();
                $totalRows = $evaluacion_results::where('fecha', 'like', "%$search%")
                    ->where('evaluacionId', $evaluacionId)
                    ->count();
                $grandTotalRows = $totalRows;
                $totalPages = ceil($totalRows / $limit);
            } else {
                $evaluacion_results = EvaluacionResult::where("evaluacionId", $evaluacionId)->orderBy($sort, $order)
                    ->limit($limit)
                    ->offset($offset)
                    ->get();
                $grandTotalRows = EvaluacionResult::where("evaluacionId", $evaluacionId)->count();
                $totalRows = $grandTotalRows;
                $totalPages = ceil($totalRows / $limit);
            }

            foreach ($evaluacion_results as $espacio) {
                $espacio["days_left"] = $this->get_days_left($espacio["fecha"]);
                $espacio["requiere_anexo"] = $indicador["requiere_anexo"];
                $espacio["value"] = Indicador::get_value(floatval($espacio["resultado"]), $unidad_medida);
                $espacio["finalizado"] = $evaluacion["finalizado"];
                if ($espacio["aprobadoPorId"] !== null) {
                    $aprobadoPorUser = User::all()->find($espacio["aprobadoPorId"]);
                    $espacio["aprobadoPor"] = $aprobadoPorUser["name"];
                }
            }
            return view('registros.table_rows', [
                'frecuencia_medicion' => $frecuencia_medicion,
                'evaluacion_results' => $evaluacion_results,
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
            // return using compact
        } catch (\Throwable $_) {
            Log::error($_);
            return view('registros.table_rows', [
                'frecuencia_medicion' => $frecuencia_medicion,
                'evaluacion_results' => $evaluacion_results,
                'totalRows' => $totalRows,
                'grandTotalRows' => $grandTotalRows,
                'totalPages' => $totalPages,
                'page' => $page,
                'limit' => $limit,
                'sort' => $sort,
                'order' => $order,
                'search' => $search,
                'error' => $_,
            ]);
        }
    }
    public function get_registros_form($evaluacionId, $fecha)
    {
        $evaluacion = Evaluacion::all()->find($evaluacionId);
        $indicador = Indicador::all()->find($evaluacion["indicadorId"]);
        $registros = $this->get_registros_input_info($evaluacionId, $fecha);
        return view('registros.fields', [
            'indicador' => $indicador,
            'evaluacion' => $evaluacion,
            'registros' => $registros
        ]);
    }

    public function get_registros_input_info($evaluacionId, $fecha)
    {
        $registros = VariableValue::all()->where('evaluacionId', $evaluacionId)->where('fecha', $fecha);
        $registros_array = [];
        foreach ($registros as $evaluacion_result) {
            $variable_info = Variable::all()->find($evaluacion_result["variableId"]);
            $evaluacion_result["nombre_variable"] = $variable_info["nombre"];
            $evaluacion_result["code"] = $variable_info["code"];
            array_push($registros_array, $evaluacion_result);
        }
        return $registros_array;
    }

    public function post(Request $request)
    {
        return $this->store_registros($request);
    }
    public function store_evaluacion_result() {}
    public function store_registros(Request $request)
    {

        $request_data = $request->all();

        $evaluacionId = $request_data["evaluacionId"];
        $evaluacion = Evaluacion::all()->find($evaluacionId);
        if ($evaluacion["finalizado"]) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'statusCode' => 400,
                'error' => 'La evaluación ya ha sido finalizada'
            ], 400);
        }
        $registros = json_decode($request_data["registros"]);
        $user = Auth::user();
        $created = [];
        foreach ($registros as $evaluacion_result) {
            $variable = VariableValue::all()->find($evaluacion_result->id);
            $variable["valor"] = $evaluacion_result->value;
            $variable->evaluacionId = $request_data["evaluacionId"];
            $variable->variableId = $evaluacion_result->variableId;
            $variable->secretariaId = $user["secretariaId"];
            $variable->usuarioId = $user["id"];
            $variable->save();
            array_push($created, $variable);
        }
        $evaluacionId = $request_data["evaluacionId"];
        $fecha = $request_data["fecha"];
        $used_formula = $request_data["used_formula"];
        if (
            $request_data["result"] == "Infinity"
                || $request_data["result"] == "-Infinity"
                || $request_data["result"] == "NaN"
        ) {
            $request_data["result"] = 0;
        }
        $result = $request_data["result"];
        $evaluacion_result = EvaluacionResult::all()->where('evaluacionId', $evaluacionId)->where('fecha', $fecha)->first();
        $evaluacion_result["evaluacionId"] = $evaluacionId;
        $evaluacion_result["used_formula"] = $used_formula;
        $evaluacion_result["resultado"] = $result;
        $evaluacion_result["fecha"] = $fecha;
        $evaluacion_result["status"] = "capturado";
        $evaluacion_result->save();
        return response()->json([
            'status' => 'success',
            'data' => $created,
            'statusCode' => 200,
            'error' => null
        ], 200);
    }
    public function set_status(Request $request, $id, $status)
    {
        $form = $request->all();
        $evaluacion_result = EvaluacionResult::all()->find($id);
        $evaluacion = Evaluacion::all()->find($evaluacion_result["evaluacionId"]);
        $user = Auth::user();
        if ($evaluacion["finalizado"]) {
            return response()->json(["error" => "La evaluación ya ha sido finalizada"]);
        }
        $evaluacion_result["status"] = $status;
        $evaluacion_result["motivo"] = $form["motivo"];
        $evaluacion_result["aprobadoPorId"] = $user["id"];
        $evaluacion_result->save();
        return json_encode($evaluacion_result);
    }
    public function get_days_left($fecha)
    {
        $today = date("Y-m-d");
        $today = strtotime($today);
        $fecha = strtotime($fecha);
        $diff = $fecha - $today;
        $days = $diff / (60 * 60 * 24);
        return $this->get_days_string($days);
    }
    public function get_days_string($days)
    {
        if ($days == 0) {
            return "Hoy";
        } else if ($days == 1) {
            return "Mañana";
        } else if ($days == -1) {
            return "Ayer";
        } else if ($days > 1) {
            return $this->get_divider($days);
        } else {
            return "Activo desde hace " . abs($days) . " días";
        }
    }
    public function get_divider($days)
    {
        $anio = 365;
        $mes = 30;
        $semana = 7;
        if ($days >= $anio) {
            $total = round($days / $anio);
            $rest = $days % $anio;
            if ($rest == 0 && ($total == 1 && $total < 2)) {
                return "Disponible en " . $total . " año";
            }
            return "Disponible en " . $total . " años";
        } else if ($days > $mes) {
            $total = round($days / $mes);
            $rest = $days % $mes;
            if (($total >= 1 && $total < 2)) {
                return "Disponible en " . $total . " mes";
            }
            return "Disponible en " . $total . " meses";
        } else if ($days > $semana && $days < $mes) {
            $total = round($days / $semana);
            $rest = $days % $semana;
            if ($rest == 0 && $total == 1) {
                return "Disponible en " . $total . " semana";
            }
            return "Disponible en " . $total . " semanas";
        } else {
            return "Disponible en " . $days . " días";
        }
    }
}
