<?php
namespace App\DTO\Users;

class UserDTO {
    public $id;
    public $nombre;
    public $apPaterno;
    public $apMaterno;
    public $email;
    public $fechaCreacion;
    public $fechaModificacion;
    public $direccionId;
    public $direccion;
    public $rol;

    public function __construct(int $id, string $nombre, string $apPaterno, ?string $apMaterno,
                                string $email, string $fechaCreacion, string $fechaModificacion, 
                                int $direccionId, ?string $direccion = null, ?string $rol = null){
            $this->id = $id;
            $this->nombre = $nombre;
            $this->apPaterno = $apPaterno;
            $this->apMaterno = $apMaterno;
            $this->email = $email;
            $this->fechaCreacion = $fechaCreacion;
            $this->fechaModificacion = $fechaModificacion;
            $this->direccionId = $direccionId;
            $this->direccion = $direccion;
            $this->rol = $rol;
    }
}