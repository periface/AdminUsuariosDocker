<?php

namespace App\DTO\Area;

class AreaDTO
{

    public $id;
    public $nombre;
    public $siglas;
    public $responsable;
    public $fecha_creacion;
    public $secretariaId;
    public function __construct(int $id, string $nombre, string $siglas, ?string $responsable, string $fecha_creacion, int $secretariaId)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->siglas = $siglas;
        $this->responsable = $responsable;
        $this->fecha_creacion = $fecha_creacion;
        $this->secretariaId = $secretariaId;
    }
}
