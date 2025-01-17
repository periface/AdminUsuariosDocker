<?php

namespace App\DTO\Users;

class UserDTO
{
    public $id;
    public $nombre;
    public $apPaterno;
    public $apMaterno;
    public $email;
    public $fechaCreacion;
    public $fechaModificacion;
    public $areaId;
    public $areaName;
    public $rol;
    public $secretariaId;

    public function __construct(
        int $id,
        string $nombre,
        string $apPaterno,
        ?string $apMaterno,
        string $email,
        string $fechaCreacion,
        string $fechaModificacion,
        ?int $areaId,
        int $secretariaId,
        ?string $areaName = null,
        ?string $rol = null
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apPaterno = $apPaterno;
        $this->apMaterno = $apMaterno;
        $this->email = $email;
        $this->fechaCreacion = $fechaCreacion;
        $this->fechaModificacion = $fechaModificacion;
        $this->areaId = $areaId;
        $this->areaName = $areaName;
        $this->rol = $rol;
        $this->secretariaId = $secretariaId;
    }
}
