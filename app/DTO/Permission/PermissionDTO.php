<?php

namespace App\DTO\Permission;

class PermissionDto
{
    public $id;
    public $name;
    public $fechaCreacion;

    public function __construct(int $id, string $name, string $fechaCreacion)
    {
        $this->id = $id;
        $this->name = $name;
        $this->fechaCreacion = $fechaCreacion;
    }
}


class Test
{
    public function test()
    {
        $permissionDto = new PermissionDto(1, 'Mi permiso', '2021-10-10');
        echo $permissionDto->id;
    }
}
