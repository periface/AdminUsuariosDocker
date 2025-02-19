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
    public $rolId;
    public $rol;
    public $secretariaId;
    public $status;

    public function __construct(
        int $id,
        string $nombre,
        string $apPaterno,
        ?string $apMaterno,
        string $email,
        string $fechaCreacion,
        string $fechaModificacion,
        ?int $areaId,
        ?string $areaName = null,
        ?int $rolId = null,
        ?string $rol = null,
        int $secretariaId,
        string $status
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
        $this->rolId = $rolId;
        $this->rol = $rol;
        $this->secretariaId = $secretariaId;
        $this->status = $status;
    }
}
