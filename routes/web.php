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

    // Auth
    Route::post('/logout',                                  [AuthController::class, 'logout'])->name('logout');
    
    // Roles
    Route::get('/roles/{user}',                             [RoleController::class, 'index']);

    // Permissions
    Route::get('/permissions',                              [PermissionController::class, 'index']);
    Route::get('/permissions/{user}',                       [PermissionController::class, 'getPermissions']);
    Route::get('/permissions/{user}/available-permissions', [PermissionController::class, 'getAvailablePermissions']);
    
    // Usuarios
    Route::middleware('role:admin')->group(function(){
        Route::get('/users',                                [UserController::class, 'index']);
        // Route::get('/users/{user}',                      [UserController::class, 'config']);
        Route::get('/users/{user}/roles-permissions',       [UserController::class, 'userRolesAndPermissions']);
        Route::get('/users/add',                            [UserController::class, 'showForm']);
    });

    // Dashboard
    Route::get('/principal',                                [PrincipalController::class, 'index'])->name('principal');
});
