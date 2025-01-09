<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Enum\DireccionesEnum;
use App\Services\RoleService;
use App\Services\UserService;
use App\Services\PermissionService;
use Symfony\Component\HttpFoundation\Response;

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
        foreach ($users as $user) {
            $user->direccion = DireccionesEnum::from($user->direccionId)->direccion();
            $role = $this->roleService->getUserRoles($user);
            if (!empty($role)) {
                $user->rol = $role[0]->alias;
            }
        }
        return view('users.index', compact('users'));

    }

    public function add(){

        $direcciones = DireccionesEnum::cases();
        return view('users.add', compact('direcciones'));
    
    }

    public function edit(User $user){
        return view('user.edit', compact('user'));
    }

    public function userRolesAndPermissions(User $user){

        $userDto = $this->userService->getUserById($user);

        $roles = $this->roleService->getUserRoles($user);

        $permissions = $this->permissionService->getUserPermissions($user);

        return view('users.config', compact('userDto', 'roles', 'permissions'));
    }

    public function activate($token){
        $user = User::where('activation_token', $token)->first();

        if( !$user ){
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
    
}
