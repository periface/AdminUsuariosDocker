<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariableValue extends Model
{
    use HasFactory;
    protected $table = 'variable_valor';
    public $valor;
    public $meta_esperada;
    public $fecha;
    public $evaluacionId;
    public $variableId;
    public $secretariaId;
    public $usuarioId;
    public $status;
    public $evaluacionResultId;
    protected $fillable = [
        'valor',
        'meta_esperada',
        'fecha',
        'evaluacionId',
        'variableId',
        'usuarioId',
        'secretariaId',
        'status', // pendiente, aprobado
        'evaluacionResultId',
    ];

    public function evaluacion()
    {
        return $this->belongsTo(Evaluacion::class, 'evaluacionId', 'id');
    }
    public function variable()
    {
        return $this->belongsTo(Variable::class, 'variableId', 'id');
    }
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuarioId', 'id');
    }
    public function secretaria()
    {
        return $this->belongsTo(Secretaria::class, 'secretariaId', 'id');
    }
}
