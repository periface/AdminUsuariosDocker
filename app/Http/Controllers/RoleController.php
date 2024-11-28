<?php

namespace App\Http\Controllers;

use auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\RoleService;

class RoleController extends Controller
{
    //
    protected $roleService;

    public function __construct(RoleService $roleService){
        $this->roleService = $roleService;
    }

    public function index(User $user){
        $roles = $this->roleService->getAllRoles($user);

        return view('roles.roles', compact('roles'));

    }
}
