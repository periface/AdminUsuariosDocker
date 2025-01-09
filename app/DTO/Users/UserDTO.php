<?php
namespace App\DTO\Users;

class UserDTO {
    public int $id;
    public string $nombre;
    public string $apPaterno;
    public string $apMaterno;
    public string $email;
    public string $fechaCreacion;
    public string $fechaModificacion;
    public int $direccion;
}