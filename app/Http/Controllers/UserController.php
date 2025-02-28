<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AreaService;
use App\Services\RoleService;
use App\Services\UserService;
use App\Services\PermissionService;
use App\Services\SecretariaService;

class UserController extends Controller
{
    //

    protected $secretariaService;
    protected $areaService;
    protected $userService;
    protected $roleService;
    protected $permissionService;

    public function __construct(SecretariaService $secretariaService, AreaService $areaService, UserService $userService, RoleService $roleService, PermissionService $permissionService)
    {
        $this->secretariaService = $secretariaService;
        $this->userService       = $userService;
        $this->roleService       = $roleService;
        $this->permissionService = $permissionService;
        $this->areaService       = $areaService;
    }

    public function index()
    {
        return view('users.index');
    }

    public function add(Request $request)
    {
        $areas = $this->areaService->getAllAreas();
        $roles = $this->roleService->getAllRoles();
        $secretarias = $this->secretariaService->getAllSecretariasWithAreas();
        
        return view('users.add', compact('areas', 'roles', 'secretarias'));
    }

    public function edit(User $user)
    {
        $userRole = $this->roleService->getUserRoles($user);
        $user = $this->userService->getUserById($user);
        if(count($userRole) > 0){
            foreach ($userRole as $rol) {
                $user->roleId = $rol->id;
                $user->role = $rol->alias;
            }
        }
        $areas = Area::all();
        $roles = $this->roleService->getAllRoles();
        
        return view('users.edit', compact('user', 'areas', 'roles'));
    }

    public function userRolesAndPermissions(User $user)
    {

        $userDto = $this->userService->getUserById($user);

        $roles = $this->roleService->getUserRoles($user);

        $permissions = $this->permissionService->getUserPermissions($user);

        return view('users.config', compact('userDto', 'roles', 'permissions'));
    }

    public function activate($token)
    {
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            $message = 'Este enlace de activación no es válido o ya ha sido utilizado';
            $ruta = null;
            return view('users.activate', compact('message', 'ruta'));
        }

        $user->update([
            'is_active' => 1,
            'activation_token' => null,
            'email_verified_at' =>  now()
        ]);


        $message = 'Su cuenta ha sido activada con éxito, por favor inicie sesión';
        $ruta = 'login';

        return view('users.activate', compact('message', 'ruta'));
    }

    public function fetchUsers(){

        $user = auth()->user();
        $role = $user->getRoleNames();

        switch ($role[0]) {
            case 'ADM':
                $users = $this->userService->getAllUsers();
                break;
            case 'RESP':
                $users = $this->userService->getUsersByArea($user);
                break;
                
            default:
                return response()->json([
                    'status' => 403,
                    'redirect' => route('principal')
                ]);
                break;
        }

        foreach ($users as $user) {
            // $user->areaName = Area::where('id', $user->areaId)->first()->nombre;
            $role = $this->roleService->getUserRoles($user);
            if (!empty($role)) {
                $user->rolId = $role[0]->id;
                $user->rol = $role[0]->alias;
            }
        }
        return view('users.table', compact('users'));

    }
}
