<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{
    use HasFactory;

    public $nombre;
    public $code;
    public $indicadorId;

    protected $fillable = ['code', 'nombre', 'indicadorId'];
    protected $table = 'variable';
    public function indicador()
    {
        return $this->belongsTo(Indicador::class, 'indicadorId');
    }
    public function variableValues()
    {
        return $this->hasMany(VariableValue::class, 'variableId');
    }
}
