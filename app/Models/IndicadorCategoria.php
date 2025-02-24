<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicadorCategoria extends Model
{
    use HasFactory;
    public $nombre;
    public $descripcion;
    public $status;
    public $secretariaId;
    public $secretaria;
    protected $table = 'indicador_categoria';
    protected $fillable = ['nombre', 'descripcion', 'sentido', 'status', 'secretariaId', 'secretaria'];

    public function indicadores()
    {
        return $this->hasMany(Indicador::class, 'categoriaId', 'id');
    }
}
