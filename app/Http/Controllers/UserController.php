<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\RoleService;
use App\Services\UserService;
use App\Services\PermissionService;

class UserController extends Controller
{
    //

    protected $userService;
    protected $roleService;
    protected $permissionService;

    public function __construct(UserService $userService, RoleService $roleService, PermissionService $permissionService){
        $this->userService = $userService;
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
    }

    public function index(){
        $users = $this->userService->getAllUsers();

        return view('users.index', compact('users'));
        // return view('users.table', compact('users'));
    }

    public function showForm(){
        return view('users.add');
    }

    public function userRolesAndPermissions(User $user){

        $userDto = $this->userService->getUserById($user);

        $roles = $this->roleService->getUserRoles($user);

        $permissions = $this->permissionService->getUserPermissions($user);

        return view('users.config', compact('userDto', 'roles', 'permissions'));
    }
}
