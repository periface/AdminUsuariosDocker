<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\AreaController;
use App\Http\Controllers\DimensionController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\IndicadorController;
use App\Http\Controllers\RegistrosController;
use App\Http\Controllers\SecretariaController;

Route::post('/login', [AuthController::class, 'store']);

// Route::post('/logout', [AuthController::class, 'logout']);

// Creamos un grupo de rutas protegidas
Route::middleware('auth:sanctum')->group(function () {

    // Users
    Route::get('users',                                     [UserController::class, 'index']);
    Route::post('users/register',                           [UserController::class, 'store']);
    Route::get('users/{user}',                              [UserController::class, 'show']);
    Route::put('users/{user}',                              [UserController::class, 'update']);
    Route::delete('users/{user}',                           [UserController::class, 'destroy']);
    Route::get('users/{user}/roles-permissions',            [UserController::class, 'userRolesAndPermissions']);

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

    Route::group(['middleware'], function () {
        // Roles
        Route::get('roles',                                     [RoleController::class, 'index']);
        Route::post('roles',                                    [RoleController::class, 'store']);
        Route::get('roles/{role}',                              [RoleController::class, 'show']);
        Route::put('roles/{role}',                              [RoleController::class, 'update']);
        Route::delete('roles/{role}',                           [RoleController::class, 'destroy']);
    });


    // Accesos para responsables de área
    Route::group(['middleware' => ['role:ADM|SPA']], function () {
        Route::get('users',                                     [UserController::class, 'index']);
        Route::get('areas',                                     [AreaController::class, 'index']);
    });

    // Áreas
    Route::group(['middleware' => ['role:ADM']], function () {
        Route::get('areas',                                     [AreaController::class, 'index']);
        Route::post('areas',                                    [AreaController::class, 'store']);
        Route::get('areas/{area}',                              [AreaController::class, 'show'])->name('mostrar-area');
        Route::put('areas/{area}',                              [AreaController::class, 'update']);
        Route::delete('areas/{area}',                           [AreaController::class, 'destroy']);
    });

    // RUTAS DE ÁREAS PERI
    Route::prefix('v1')->group(function () {
        Route::get('/secretaria', [SecretariaController::class, 'get']); //Obtiene todas las áreas

        Route::post('/secretaria', [SecretariaController::class, 'post']); //Crea una nueva área
        Route::put('/secretaria', [SecretariaController::class, 'put']); //Crea una nueva área
        Route::delete('/secretaria/{id}', [SecretariaController::class, 'delete']); //Crea una nueva área
        Route::get('/dimension', [DimensionController::class, 'get']);
        Route::post('/dimension', [DimensionController::class, 'post']);
        Route::get('/dimension/{id}', [DimensionController::class, 'get']);
        Route::put('/dimension/{id}', [DimensionController::class, 'put']);
        Route::delete('/dimension/{id}', [DimensionController::class, 'delete']);
        Route::get('/dimension/{id}/area', [DimensionController::class, 'getAreas']);
        Route::get('/dimension/get_by_name/{name}',  [DimensionController::class, 'get_by_name'])->name('get_by_name');

        Route::get('/indicador', [IndicadorController::class, 'get']);
        Route::post('/indicador', [IndicadorController::class, 'post']);
        Route::get('/indicador/{id}', [IndicadorController::class, 'get']);
        Route::put('/indicador/{id}', [IndicadorController::class, 'put']);
        Route::delete('/indicador/{id}', [IndicadorController::class, 'delete']);


        Route::post('/evaluacion', [EvaluacionController::class, 'post']);
        Route::delete('/evaluacion/{id}', [EvaluacionController::class, 'delete']);
        Route::get('/evaluacion/{id}/stats', [EvaluacionController::class, 'get_evaluacion_stats_req']);
        Route::get('/evaluacion/cerrar/{id}', [EvaluacionController::class, 'cerrar_evaluacion'])->name('cerrar_evaluacion');
        // Registros
        Route::post('/registro', [RegistrosController::class, 'post']);
        Route::get('/registro/{id}/{status}', [RegistrosController::class, 'set_status']);

        Route::get('/registro/{id}/{status}', [RegistrosController::class, 'set_status']);
    });
});
