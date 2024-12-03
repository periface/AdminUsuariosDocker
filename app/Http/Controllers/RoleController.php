<?php

namespace App\Http\Controllers;

use auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\RoleService;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    //
    protected $roleService;

    public function __construct(RoleService $roleService){
        $this->roleService = $roleService;
    }

    public function allRoles(){
        $roles = $this->roleService->getAllRoles();

        return view('roles.table', compact('roles'));
    }

    // Esta función debe cambiar de nombre al mismo del método utilizado
    public function index(User $user){
        $roles = $this->roleService->getAvailableRoles($user);

        return view('roles.roles', compact('roles'));
    }

    public function editar(Role $role){

        $role = $this->roleService->getRole($role);
        return view('roles.edit', compact('role'));

    }

    public function formRole(){
        return view('roles.add');
    }
}
