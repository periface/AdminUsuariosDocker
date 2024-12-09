<?php

namespace App\DTO\Role;

class RoleDTO{
    public $id;
    public $name;
    public $description;
    public $alias;


    /**
     * @var PermissionDTO;
     */
    public array $permissions;

    public function __construct(int $id, string $name, string $description, string $alias, array $permissions = []){
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->alias = $alias;
        $this->permissions = $permissions;
    }
}