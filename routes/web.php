<?php

use App\Http\Controllers\AnexosController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\DimensionController;
use App\Http\Controllers\IndicadorController;
use App\Http\Controllers\PrincipalController;
use App\Http\Controllers\RegistrosController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SecretariaController;

Route::get('/', [AuthController::class, 'login']);
// Route::get('/', function(){
//     return view('login');
// });

// Esta ruta no está protegida, dado que es el punto de entrada a la aplicación
Route::post('/', [AuthController::class, 'store'])->name('login');

//Ruta para activar la cuenta
Route::get('/activate/{token}', [UserController::class, 'activate']);

//Ruta demo admin
// Route::get('/welcome', function(){
//     return view('layout.welcome');
// });

Route::middleware('auth:sanctum')->group(function () {

    // Dashboard
    Route::get('/principal',                                         [PrincipalController::class, 'index'])->name('principal');

    // Usuarios
    Route::group(['middleware' => ['role:ADM']], function () {
        Route::get('/users',                                         [UserController::class, 'index']);
        Route::get('/users/create',                                  [UserController::class, 'add']);
        Route::get('/users/{user}/edit',                             [UserController::class, 'edit']);
        Route::get('/users/{user}/roles-permissions',                [UserController::class, 'userRolesAndPermissions']);
        Route::get('/users/fetchUsers',                              [UserController::class, 'fetchUsers']);

        // Roles
        Route::get('/roles',                                         [RoleController::class, 'index'])->name('roles');
        Route::get('/roles/create',                                  [RoleController::class, 'add']);
        Route::get('/roles/{role}/edit',                             [RoleController::class, 'edit']);
        Route::get('/roles/{role}/permissions',                      [RoleController::class, 'rolePermissions']);

        // Permissions
        Route::get('/permissions',                                   [PermissionController::class, 'index'])->name('permisos');
        Route::get('/permissions/{user}/available-permissions',      [PermissionController::class, 'getAvailablePermissions']);
        Route::get('/permissions/{role}/available-permissions-role', [PermissionController::class, 'getAvailablePermissionsRole']);
        Route::get('/permissions/add',                               [PermissionController::class, 'addPermissionForm']);
    });

    // Accesos para responsables de área
    // Route::group(['middleware' => ['role:ADM|SPA']], function () {
    Route::get('/users',                                         [UserController::class, 'index'])->middleware('role:SPA|ADM')->name('usuarios');
    Route::get('/areas',                                         [AreaController::class, 'index'])->middleware('role:SPA|ADM')->name('areas');
    // });

    // Areas
    // Route::group(['middleware' => ['role:ADM']], function () {
    Route::get('/areas',                                          [AreaController::class, 'index'])->name('areas');
    Route::get('/areas/{area}/edit',                              [AreaController::class, 'createOrEdit']);
    Route::get('/areas/create',                                   [AreaController::class, 'create']);
    Route::get('/areas/fetchAreas',                               [AreaController::class, 'fetchAreas']);

    // });

    // Auth
    Route::post('/logout',                                            [AuthController::class, 'logout'])->name('logout');


    Route::group(['middleware' => ['role:ADM']], function () {
        Route::get('/monitor',                                        [MonitorController::class, 'index']);
    });


    // PERI WEB ROUTES

    Route::prefix('dimension')->name("dimension.")->middleware(['role:ADM'])->group(function () {
        Route::get('/',  [DimensionController::class, 'index'])->name('index');
        Route::get('/{dimensionId}/indicadores',  [IndicadorController::class, 'dimension_indicadores'])->name('indicadores');
        Route::post('/get_table_rows',  [DimensionController::class, 'get_rows'])->name('get_table_rows');
        Route::get('/get_dimension_fields',  [DimensionController::class, 'get_dimension_fields'])->name('get_dimension_fields');
    });

    Route::prefix('categoria')->name("categoria.")->middleware(['role:ADM'])->group(function () {
        Route::get('/',  [CategoriasController::class, 'index'])->name('index');
        Route::get('/{categoriaId}/indicadores',  [CategoriasController::class, 'categoria_indicadores'])->name('indicadores');
        Route::post('/get_table_rows',  [CategoriasController::class, 'get_rows'])->name('get_table_rows');
        Route::get('/get_categoria_fields',  [CategoriasController::class, 'get_categoria_fields'])->name('get_categoria_fields');
    });
    Route::prefix('indicador')->name("indicador.")->middleware(['role:ADM'])->group(function () {
        Route::get('/',  [IndicadorController::class, 'index'])->name('index');
        Route::get('/details/{id}',  [IndicadorController::class, 'details'])->name('details');
        Route::post('/get_table_rows/{dimensionId}',  [IndicadorController::class, 'get_rows'])->name('get_table_rows');
        Route::get('/get_indicador_fields',  [IndicadorController::class, 'get_indicador_fields'])->name('get_indicador_fields');
    });

    Route::prefix('evaluacion')->name("evaluacion.")->middleware(['role:ADM|REV'])->group(function () {
        Route::get('/',  [EvaluacionController::class, 'index'])->name('index');
        Route::post('/get_table_rows',  [EvaluacionController::class, 'get_rows'])->name('get_table_rows');
        Route::get('/get_evaluacion_fields',  [EvaluacionController::class, 'get_evaluacion_fields'])->name('get_evaluacion_fields');
        Route::get('/get_evaluacion_details',  [EvaluacionController::class, 'get_evaluacion_details'])->name('get_evaluacion_details');
        Route::get('/{id}/registros',  [RegistrosController::class, 'registros'])->name('registros');
        Route::get('/{id}/ficha',  [EvaluacionController::class, 'ficha'])->name('ficha');
    });

    Route::prefix('registro')->name("registro.")->middleware(['role:ADM|REV'])->group(function () {
        Route::get('/get_registros_form/{id_evaluacion}/{fecha}',  [RegistrosController::class, 'get_registros_form'])->name('get_registros_form');

        Route::post('/get_table_rows/{id_evaluacion}',  [RegistrosController::class, 'get_rows'])->name('get_rows');
    });

    Route::prefix('secretaria')->name("secretaria.")->middleware(['role:ADM'])->group(function () {
        Route::get('/',  [SecretariaController::class, 'index'])->middleware('role:ADM')->name('index');
        Route::post('/get_table_rows',  [SecretariaController::class, 'get_rows'])->name('get_table_rows');
        Route::get('/get_secretaria_fields',  [SecretariaController::class, 'get_secretaria_fields'])->middleware('role:ADM')->name('get_secretaria_fields');
    });

    Route::prefix('anexos')->name("anexos.")->middleware(['role:ADM|REV'])->group(function () {
        Route::get('/{id}',  [AnexosController::class, 'index'])->name('index');
        Route::get('/get_rows/{id}',  [AnexosController::class, 'get_rows'])->name('get_rows');
        Route::post('/upload/{id}', [AnexosController::class, 'upload'])->name('upload');
        Route::delete('/{id}', [AnexosController::class, 'delete'])->name('delete');
    });
});
