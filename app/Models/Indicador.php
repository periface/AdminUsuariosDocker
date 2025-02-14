<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Indicador extends Model
{
    use HasFactory;
    protected $table = 'indicador';
    public $nombre;
    public $descripcion;
    public $status;
    public $sentido; // ascendente, descendente, constante
    public $unidad_medida; // $, %, kg, etc
    public $metodo_calculo; // formula x * y / z = resultado
    public $dimensionId;
    public $evaluable_formula;
    public $non_evaluable_formula;
    public $indicador_confirmado;
    public $secretariaId;
    public $medio_verificacion;
    public $requiere_anexo;
    public $secretaria;
    public $categoria; //capital humano, capital estructural, capital relacional, capital tecnologico
    protected $fillable = [
        'nombre',
        'descripcion',
        'unidad_medida',
        'metodo_calculo',
        'status',
        'dimensionId',
        'sentido',
        'evaluable_formula',
        'non_evaluable_formula',
        'indicador_confirmado',
        'secretariaId',
        'secretaria',
        'medio_verificacion',
        'requiere_anexo',
        'categoria'
    ];
    public function dimension()
    {
        return $this->belongsTo(Dimension::class, 'dimensionId', 'id');
    }
    public function variables()
    {
        return $this->hasMany(Variable::class, 'indicadorId');
    }
    public function variableValues()
    {
        return $this->hasMany(VariableValue::class, 'indicadorId');
    }

    public static function get_value(int $value, string $unidad_medida)
    {
        $prefix = self::get_prefix($unidad_medida);
        $suffix = self::get_suffix($unidad_medida);
        return $prefix . $value . $suffix;
    }
    public static function get_prefix($unidad_medida)
    {
        switch ($unidad_medida) {
            case '%':
                return '';
            case 'porcentaje':
                return '';
            case 'moneda':
                return '$';
            case 'numero':
                return '';
            case 'días':
                return '';
            default:
                return $unidad_medida;
        }
    }
    public static function get_suffix($unidad_medida)
    {
        switch ($unidad_medida) {
            case '%':
                return '%';
            case 'porcentaje':
                return '%';
            case 'moneda':
                return ' pesos';
            case 'numero':
                return '';
            case 'días':
                return ' días';
            default:
                return $unidad_medida;
        }
    }
}
