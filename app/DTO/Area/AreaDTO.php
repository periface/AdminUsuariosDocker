<?php

namespace App\DTO\Area;

class AreaDTO
{

    public $id;
    public $secretaria;
    public $sria_siglas;
    public $nombre;
    public $siglas;
    public $responsable;
    public $fecha_creacion;
    public $secretariaId;

    public function __construct(
        int $id, 
        string $secretaria,
        string $sria_siglas,
        string $nombre, 
        string $siglas, 
        ?string $responsable, 
        string $fecha_creacion, 
        int $secretariaId)
    {
        $this->id = $id; 
        $this->secretaria = $secretaria;
        $this->sria_siglas = $sria_siglas;
        $this->nombre = $nombre;
        $this->siglas = $siglas;
        $this->responsable = $responsable;
        $this->fecha_creacion = $fecha_creacion;
        $this->secretariaId = $secretariaId;
    }
}
