<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\PrincipalController;
use App\Http\Controllers\PermissionController;

Route::get('/', function(){
    return view('login');
});

// Esta ruta no está protegida, dado que es el punto de entrada a la aplicación
Route::post('/', [AuthController::class, 'store'])->name('login');

Route::middleware('auth:sanctum')->group(function(){
    
    // Usuarios
    Route::group(['middleware' => ['role:ADM']], function(){
        Route::get('/users',                                         [UserController::class, 'index']);
        Route::get('/users/create',                                  [UserController::class, 'add']);
        Route::get('/users/{user}/edit',                             [UserController::class, 'edit']);
        Route::get('/users/{user}/roles-permissions',                [UserController::class, 'userRolesAndPermissions']);

    // Roles
        Route::get('/roles',                                         [RoleController::class, 'index']);
        Route::get('/roles/create',                                  [RoleController::class, 'add']);
        Route::get('/roles/{role}/edit',                             [RoleController::class, 'edit']);
        Route::get('/roles/{role}/permissions',                      [RoleController::class, 'rolePermissions']);
    
    // Roles disponibles
        Route::get('/roles/{user}/',                                  [RoleController::class, 'availableRoles']);
    
    // Permissions
        Route::get('/permissions',                                   [PermissionController::class, 'index']);
        Route::get('/permissions/{user}/available-permissions',      [PermissionController::class, 'getAvailablePermissions']);
        Route::get('/permissions/{role}/available-permissions-role', [PermissionController::class, 'getAvailablePermissionsRole']);
        Route::get('/permissions/add',                               [PermissionController::class, 'addPermissionForm']);
        
        // Ruta de prueba
        Route::get('/permissions/assign-permission',                 [PermissionController::class, 'assignPermission']);
    });

    // Dashboard
    Route::get('/principal',                                         [PrincipalController::class, 'index'])->name('principal');

    // Auth
    Route::post('/logout',                                           [AuthController::class, 'logout'])->name('logout');

});