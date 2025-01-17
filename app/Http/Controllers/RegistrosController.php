<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Indicador;
use App\Models\Variable;
use App\Models\VariableValue;
use App\Models\EvaluacionResult;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;


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
        $espacios_captura = EvaluacionResult::all()->where('evaluacionId', $id);
        $indicador = Indicador::all()->find($evaluacion["indicadorId"]);
        foreach ($espacios_captura as $espacio) {
            $espacio["days_left"] = $this->get_days_left($espacio["fecha"]);
        }
        return view('registros.index', [
            'evaluacion' => $evaluacion,
            'espacios' => $espacios_captura,
            'indicador' => $indicador
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
        try {
            if ($search !== '') {
                $evaluacion_results = EvaluacionResult::where('descripcion', 'like', "%$search%")
                    ->where('evaluacionId', $evaluacionId)
                    ->orderBy($sort, $order)
                    ->limit($limit)
                    ->offset($offset)
                    ->get();
                $totalRows = $evaluacion_results::where('descripcion', 'like', "%$search%")
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

            return view('evaluacion_results.table_rows', [
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
            return view('evaluacion_results.table_rows', [
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
        log::info($indicador);
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
        $registros = json_decode($request_data["registros"]);
        log::info($registros);
        $user = auth()->user();
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
        $result = $request_data["result"];
        $evaluacion_result = EvaluacionResult::all()->where('evaluacionId', $evaluacionId)->where('fecha', $fecha)->first();
        $evaluacion_result["evaluacionId"] = $evaluacionId;
        $evaluacion_result["used_formula"] = $used_formula;
        $evaluacion_result["resultado"] = $result;
        $evaluacion_result["fecha"] = $fecha;
        $evaluacion_result["status"] = "capturado";
        $evaluacion_result->save();
        return json_encode($created);
    }
    public function set_status($id, $status)
    {
        $evaluacion_result = EvaluacionResult::all()->find($id);
        $evaluacion_result["status"] = $status;
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
