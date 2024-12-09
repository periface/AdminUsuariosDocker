<?php
namespace App\DTO\Permission;

class PermissionDto{
    public $id;
    public $name;
    public $fechaCreacion;

    public function __construct(int $id, string $name, string $fechaCreacion){
        $this->id = $id;
        $this->name = $name;
        $this->fechaCreacion = $fechaCreacion;
    }
}