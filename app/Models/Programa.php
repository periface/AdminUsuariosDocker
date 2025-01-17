<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    use HasFactory;

    public $nombre;
    public $secretariaId;
    public $clave;
}

class ProgramaEjercicio extends Model
{
    use HasFactory;

    public $programaId;
    public $ejercicio; // año
    public $fecha_inicio;
    public $fecha_fin;
    public $status;
}
