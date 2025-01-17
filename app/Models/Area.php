<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    public $nombre;
    public $responsableId;
    public $siglas;
    public $status;
    public $fecha_creacion;
    public $fecha_modificacion;
    public $secretariaId;
    protected $primaryKey = 'id';

    protected $table = 'area';

    protected $fillable = ['nombre', 'responsableId', 'siglas', 'secretariaId'];

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsableId');
    }
    public function secretaria()
    {
        return $this->belongsTo(Secretaria::class, 'secretariaId');
    }
}
