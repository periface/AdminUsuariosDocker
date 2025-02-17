<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IndicadoresSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        self::generar_indicadores();
    }
    public static function generar_indicadores()
    {
        $dimensionCalidad = \App\Models\Dimension::where('nombre', 'Calidad')->first();
        $dimensionEficiencia = \App\Models\Dimension::where('nombre', 'Eficiencia')->first();
        $secretaria = \App\Models\Secretaria::where('siglas', 'SA')->first();
        $indicador = \App\Models\Indicador::factory()->create([
            'clave' => 'CO1',
            'nombre' => 'Expedientes de Compras con Observaciones',
            'descripcion' => 'Mide el porcentaje de expedientes de compras devueltos por la DGCyOP a la Dirección Administrativa por motivo de observaciones',
            'status' => true,
            'sentido' => 'descendente',
            'unidad_medida' => 'porcentaje',
            'metodo_calculo' => '(Expedientes de compra devueltos con observaciones
por la DGCyOP/ Expedientes de compra entregados a la DGCyOP) *100',
            'dimensionId' => $dimensionCalidad->id,
            'evaluable_formula' => '({ECADO}/{EPGCA})*100',
            'non_evaluable_formula' => '(ECADO/EPGCA)*100',
            'indicador_confirmado' => true,
            'secretariaId' => $secretaria->id,
            'secretaria' => $secretaria['nombre'],
            'medio_verificacion' => 'No definido',
            'requiere_anexo' => false,
            'categoria' => 'Capital Organizacional'
        ]);

        self::create_variable($indicador["id"], 'Expedientes de compra devueltos con observaciones por la DGCyOP', 'ECADO');
        self::create_variable($indicador["id"], 'Expedientes de compra entregados a la DGCyOP', 'EPGCA');


        $indicador2 = \App\Models\Indicador::factory()->create([
            'clave' => 'CO2',
            'nombre' => 'Tiempo promedio de atención a observaciones
de Expedientes de Compras ante la DGCyOP',
            'descripcion' => 'Mide el tiempo promedio que le toma a la dirección administrativa atender observaciones hechas por la DGCyOP a sus expedientes de compras.',
            'status' => true,
            'sentido' => 'descendente',
            'unidad_medida' => 'días',
            'metodo_calculo' => 'Suma del tiempo total de atención a observaciones por tipo de procedimiento de compra/
Total de expedientes devueltos con observaciones por procedimiento de compra',
            'dimensionId' => $dimensionEficiencia->id,
            'evaluable_formula' => '{STTAOTPC}/{TEDOPC}',
            'non_evaluable_formula' => 'STTAOTPC/TEDOPC',
            'indicador_confirmado' => true,
            'secretariaId' => $secretaria->id,
            'secretaria' => $secretaria['nombre'],
            'medio_verificacion' => 'No definido',
            'requiere_anexo' => false,
            'categoria' => 'Capital Organizacional'
        ]);

        self::create_variable($indicador2["id"], 'Suma del tiempo total de atención a observaciones por tipo de procedimiento de compra', 'STTAOTPC');
        self::create_variable($indicador2["id"], 'Total de expedientes devueltos con observaciones por procedimiento de compra', 'TEDOPC');
    }
    public static function create_variable($indicadorId, $variableName, $variableCode = null)
    {

        \App\Models\Variable::factory()->create([
            'code' => $variableCode,
            'nombre' => $variableName,
            'indicadorId' => $indicadorId,
        ]);
    }
}
