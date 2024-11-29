<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AreaController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\LoginController;

use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PermissionController;

Route::post('/login', [AuthController::class, 'store']);

// Route::post('/logout', [AuthController::class, 'logout']);

// Creamos un grupo de rutas protegidas
Route::middleware('auth:sanctum')->group(function(){

    // Users
    Route::middleware('role:admin')->group(function(){

        Route::get('users',                                     [UserController::class, 'index']);
        Route::post('users/register',                           [UserController::class, 'store']);
        Route::get('users/{user}',                              [UserController::class, 'show']);
        Route::put('users/{user}',                              [UserController::class, 'update']);
        Route::delete('users/{user}',                           [UserController::class, 'destroy']);
        Route::get('users/{user}/roles-permissions',            [UserController::class, 'userRolesAndPermissions']);
        
        // Roles
        Route::get('roles',                                     [RoleController::class, 'index']);
        Route::post('roles',                                    [RoleController::class, 'store']);
        Route::get('roles/{role}',                              [RoleController::class, 'show']);
        Route::put('roles/{role}',                              [RoleController::class, 'update']);
        Route::delete('roles/{role}',                           [RoleController::class, 'destroy']);

        // UserRoles
        Route::post('users/{user}/roles/{role}',                [RoleController::class, 'attachRole']);
        Route::delete('users/{user}/roles/{role}',              [RoleController::class, 'detachRole']);

        // Permisos
        Route::get('permissions',                               [PermissionController::class, 'index']);
        Route::post('permissions',                              [PermissionController::class, 'store']);
        Route::get('permissions/{permission}',                  [PermissionController::class, 'show']);
        Route::put('permissions/{permission}',                  [PermissionController::class, 'update']);
        Route::delete('permissions/{permission}',               [PermissionController::class, 'destroy']);

        // RolePermissions
        Route::post('roles/{role}/permissions/{permission}',    [PermissionController::class, 'atachPermissionRole']);
        Route::delete('roles/{role}/permissions/{permission}',  [PermissionController::class, 'detachPermissionRole']);

        // UserPermissions
        Route::post('users/{user}/permissions/{permission}',    [PermissionController::class, 'atachPermissionUser']);
        Route::delete('users/{user}/permissions/{permission}',  [PermissionController::class, 'detachPermissionUser']);

    });

    // Áreas
    Route::get('areas',                                     [AreaController::class, 'index'])->name('lista-areas');
    Route::post('areas/{area}',                             [AreaController::class, 'store'])->name('crear-area');
    Route::get('areas/{area}',                              [AreaController::class, 'show'])->name('mostrar-area');
    Route::put('areas/{area}',                              [AreaController::class, 'update'])->name('editar-area');
    Route::delete('areas/{area}',                           [AreaController::class, 'delete'])->name('eliminar-area');

});