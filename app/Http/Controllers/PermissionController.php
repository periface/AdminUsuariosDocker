<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\PermissionService;

class PermissionController extends Controller
{
    //
    protected $permissionService;

    public function __construct(PermissionService $permissionService){
        $this->permissionService = $permissionService;
    }

    public function index(){
        $permissions = $this->permissionService->getAllPermissions();

        return view('permissions.index', compact('permissions'));
    }

    public function getAvailablePermissions(User $user){
        
        $availablePermissions = $this->permissionService->getAvailablePermissions($user);

        return view('permissions.availablePermissions', compact('availablePermissions'));
    }
}
