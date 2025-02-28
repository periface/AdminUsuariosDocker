<?php
namespace App\DTO\Secretaria;

class SecretariaDTO {
    public $id;
    public $nombre;
    public $siglas;
    public $tipo;
    public $status;

    public $areas;

    public function __construct(int $id, string $nombre, string $siglas, string $tipo, int $status , ?array $areas = []){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->siglas = $siglas;
        $this->tipo = $tipo;
        $this->status = $status;
        $this->areas = $areas;
    }
}