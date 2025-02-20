<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    use HasFactory;
    public $areaId;
    public $indicadorId;
    public $frecuencia_medicion;
    public $meta;

    public $fecha_fin;
    public $fecha_inicio;

    public $usuarioId;
    public $evaluable_formula;
    public $non_evaluable_formula;
    public $formula_literal;
    public $descripcion;
    public $finalizado;
    public $finalizado_por;
    public $finalizado_en;
    public $rendimiento;
    public $meta_alcanzada;
    protected $table = 'evaluacion';
    protected $fillable = [
        'formula_literal',
        'areaId',
        'indicadorId',
        'frecuencia_medicion',
        'meta',
        'fecha_fin',
        'fecha_inicio',
        'usuarioId',
        'evaluable_formula',
        'non_evaluable_formula',
        'descripcion',
        'finalizado',
        'finalizado_por',
        'finalizado_en',
        'rendimiento',
        'meta_alcanzada'
    ];
    public function indicador()
    {
        return $this->belongsTo(Indicador::class, 'indicadorId', 'id');
    }
    public function area()
    {
        return $this->belongsTo(Area::class, 'areaId', 'id');
    }
    public function variableValues()
    {
        return $this->hasMany(VariableValue::class, 'evaluacionId');
    }
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuarioId', 'id');
    }
    public function aprobadoPor()
    {
        return $this->belongsTo(User::class, 'aprobadoPorId', 'id');
    }
}
