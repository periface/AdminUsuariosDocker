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
        Route::get('/users',                                    [UserController::class, 'index']);
        Route::get('/users/{user}/roles-permissions',           [UserController::class, 'userRolesAndPermissions']);
        Route::get('/users/add',                                [UserController::class, 'showForm']);

    // Roles
        Route::get('/roles',                                    [RoleController::class, 'allRoles']);
        Route::get('/roles/add',                                [RoleController::class, 'formRole']);
        Route::get('/roles/{user}',                             [RoleController::class, 'index']);
        Route::get('/roles/edit-role/{role}',                   [RoleController::class, 'editar']);
        Route::get('/roles/rolePermissions/{role}',                    [RoleController::class, 'rolePermissions']);

    // Permissions
        Route::get('/permissions',                              [PermissionController::class, 'index']);
        Route::get('/permissions/{user}/available-permissions', [PermissionController::class, 'getAvailablePermissions']);
        Route::get('/permissions/add',                          [PermissionController::class, 'addPermissionForm']);
    });

    // Dashboard
    Route::get('/principal',                                    [PrincipalController::class, 'index'])->name('principal');

    // Auth
    Route::post('/logout',                                      [AuthController::class, 'logout'])->name('logout');

});