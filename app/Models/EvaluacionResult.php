<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionResult extends Model
{
    use HasFactory;
    public $evaluacionId;
    public $resultado;
    public $fecha;
    public $status;
    public $aprobado_por;
    public $used_formula;
    protected $table = 'evaluacion_result';
    protected $fillable = [
        'evaluacionId',
        'resultado',
        'fecha',
        'status',
    ];
    public function anexos()
    {
        return $this->hasMany(Anexo::class, 'evaluacionResultId', 'id');
    }
}
