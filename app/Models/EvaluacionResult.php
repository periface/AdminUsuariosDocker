<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionResult extends Model
{
    use HasFactory, HasTimestamps;
    public $resultNumber;
    public $evaluacionId;
    public $resultado;
    public $fecha;
    public $status;
    public $aprobadoPorId;
    public $used_formula;
    public $motivo;
    protected $table = 'evaluacion_result';
    protected $fillable = [
        'resultNumber',
        'evaluacionId',
        'resultado',
        'fecha',
        'status',
        'motivo'
    ];
    public function anexos()
    {
        return $this->hasMany(Anexo::class, 'evaluacionResultId', 'id');
    }
    public function variable_values()
    {
        return $this->hasMany(VariableValue::class, 'evaluacionResultId', 'id');
    }
}
