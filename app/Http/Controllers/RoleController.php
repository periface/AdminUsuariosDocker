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

    public function index(){
        $roles = $this->roleService->getAllRoles();
        return view('roles.index', compact('roles'));
    }

    public function availableRoles(User $user){
        $roles = $this->roleService->getAvailableRoles($user);

        return view('roles.availableRoles', compact('roles'));
    }

    public function edit(Role $role){
        $role = $this->roleService->getRole($role);
        return view('roles.edit', compact('role'));
    }

    public function add(){
        return view('roles.add');
    }

    public function rolePermissions(Role $role){

        $rolePermissions = $this->roleService->getRolePermissions($role);
        return view('roles.rolePermissions', compact('rolePermissions'));

    }
}
