<?php

namespace Database\Seeders;

use App\Models\Indicador;
use Illuminate\Database\Seeder;

class FechaCaptura
{
    public $fecha_captura;
    public $meta;
    public function __construct($fecha_captura, $meta)
    {
        $this->fecha_captura = $fecha_captura;
        $this->meta = $meta;
    }
}
class EvaluacionesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        self::generar_evaluaciones();
    }
    public static function generar_evaluaciones()
    {
        $indicador = \App\Models\Indicador::where('nombre', 'Expedientes de Compras con Observaciones')->first();
        $area = \App\Models\Area::where('siglas', 'DGCYOP')->first();
        $usuario = \App\Models\User::where('email', 'test@example.com')->first();
        $evaluacion = \App\Models\Evaluacion::factory()->create([
            'areaId' => $area->id,
            'indicadorId' => $indicador->id,
            'frecuencia_medicion' => 'mensual',
            'meta' => 100,
            'fecha_fin' => '2025-12-1',
            'fecha_inicio' => '2025-01-01',
            'usuarioId' => $usuario->id,
            'evaluable_formula' => '({ECADO}/{EPGCA})*100',
            'non_evaluable_formula' => '(ECADO/EPGCA)*100',
            'formula_literal' => '({ECADO}/{EPGCA})*100',
            'descripcion' => 'Mide el porcentaje de expedientes de compras devueltos por la DGCyOP a la Dirección Administrativa por motivo de observaciones',
        ]);


        $fechas_captura = [
            new FechaCaptura('2025-01-01', 100),
            new FechaCaptura('2025-02-01', 100),
            new FechaCaptura('2025-03-01', 100),
            new FechaCaptura('2025-04-01', 100),
            new FechaCaptura('2025-05-01', 100),
            new FechaCaptura('2025-06-01', 100),
            new FechaCaptura('2025-07-01', 100),
            new FechaCaptura('2025-08-01', 100),
            new FechaCaptura('2025-09-01', 100),
            new FechaCaptura('2025-10-01', 100),
            new FechaCaptura('2025-11-01', 100),
            new FechaCaptura('2025-12-01', 100),
        ];


        $evaluacion = \App\Models\Evaluacion::where('indicadorId', $indicador->id)->first();
        self::create_capture_dates(
            $fechas_captura,
            $evaluacion->id,
            $indicador->id,
            $usuario->id,
        );
    }
    public static function get_random_status()
    {
        $status = ["capturado", "pendiente", "aprobado", "rechazado"];
        $random = rand(0, 3);
        return $status[$random];
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
            $random1 = rand(1, 100);
            $random2 = rand(1, 100);
            if ($random2 == 0) {
                $random2 = 1;
            }
            if ($random1 == 0) {
                $random1 = 1;
            }

            if ($random2 < $random1) {
                $temp = $random1;
                $random1 = $random2;
                $random2 = $temp;
            }

            $result = $random1 / $random2 * 100;
            $evaluacion_result = [
                'id' => null,
                'evaluacionId' => $evaluacion_id,
                'resultado' => $result,
                'status' => self::get_random_status(),
                'fecha' => $fecha->fecha_captura,
                'aprobadoPorId' => null
            ];

            \App\Models\EvaluacionResult::insert($evaluacion_result);
            $lastInsertedId = \App\Models\EvaluacionResult::latest()->first();
            $evaluation_results[] = $evaluacion_result;
            $counter = 0;
            foreach ($variables as $variable) {
                $varValue = 0;
                if ($counter == 0) {
                    $varValue = $random1;
                } else {
                    $varValue = $random2;
                    $counter = 0;
                }
                $variable_valor = [
                    'evaluacionResultId' => $lastInsertedId->id,
                    'fecha' => $fecha->fecha_captura,
                    'valor' => $varValue,
                    'meta_esperada' => floatval($fecha->meta),
                    'evaluacionId' => $evaluacion_id,
                    'variableId' => $variable->id,
                    'usuarioId' => $user_id,
                    'status' => 'pendiente',
                ];
                $db_variable = \App\Models\VariableValue::insert($variable_valor);
                $variables_valor[] = $db_variable;
                $counter++;
            }
        }
        return [$variables_valor, $evaluation_results];
    }
}
