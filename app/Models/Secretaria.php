<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secretaria extends Model
{
    use HasFactory;
    public $nombre;
    public $siglas;
    public $type;
    protected $table = 'secretaria';
    protected $fillable = ['nombre', 'siglas', 'type'];
}
