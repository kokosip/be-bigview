<?php

use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UsecaseController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MasterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Poda\SosialKependudukanController;
use App\Services\Admin\SosialKependudukanServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/login', [AuthController::class, 'login']);


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('admin')->group(function () {
    // Master
    Route::get('/prov', [MasterController::class, 'listProvinsi']);
    Route::get('/kabkota', [MasterController::class, 'listKabkota']);

    // Menu Management
    Route::post('/menus', [MenuController::class, 'addMenu']);
    Route::get('/menus', [MenuController::class, 'listMenu']);
    Route::get('/menus/parent', [MenuController::class, 'menuUtama']);
    Route::put('/menus/{id}', [MenuController::class, 'updateMenu']);
    Route::delete('/menus/{id}', [MenuController::class, 'deleteMenu']);
    Route::get('/menus/{id}', [MenuController::class, 'getMenuById']);

    // Role
    Route::post('/roles', [RoleController::class, 'addRole']);
    Route::get('/roles', [RoleController::class, 'listRole']);
    Route::get('/roles/names', [RoleController::class, 'listNamesRole']);

    Route::put('/roles/{id}', [RoleController::class, 'updateRole']);
    Route::delete('/roles/{id}', [RoleController::class, 'deleteRole']);
    Route::get('/roles/{id}', [RoleController::class, 'getRoleById']);

    // Role - Menu
    Route::prefix('role-menu')->group(function() {
        Route::get('/', [RoleController::class, 'listRoleMenu']);
        Route::post('/', [RoleController::class, 'addRoleMenu']);
        Route::delete('/', [RoleController::class, 'deleteRoleMenu']);
    });

    // Usecase
    Route::get('/usecase', [UsecaseController::class, 'listUsecase']);
    Route::get('/usecase/names', [UsecaseController::class, 'listNameUsecase']);
    Route::post('/usecase/gov', [UsecaseController::class, 'addUsecaseGovernment']);
    Route::post('/usecase/custom', [UsecaseController::class, 'addUsecaseCustom']);
    Route::get('/usecase/{id}', [UsecaseController::class, 'getUsecaseById']);
    Route::put('/usecase/gov/{id}', [UsecaseController::class, 'updateUsecaseGovern']);
    Route::put('/usecase/custom/{id}', [UsecaseController::class, 'updateUsecaseCustom']);
    Route::delete('/usecase/gov/{id}', [UsecaseController::class, 'deleteUsecaseGovernment']);
    Route::delete('/usecase/custom/{id}', [UsecaseController::class, 'deleteUsecaseCustom']);

    // User
    Route::get('/users', [UserController::class, 'listUser']);
    Route::post('/users', [UserController::class, 'addUser']);
    Route::put('/users/active/{id}', [UserController::class, 'updateIsActived']);
    Route::get('/users/{id}', [UserController::class, 'getUserById']);
    Route::delete('/users/{id}', [UserController::class, 'deleteUser']);
    Route::put('/users/{id}', [UserController::class, 'updateUser']);

});

// Poda
Route::prefix('poda')->group(function() {
    // Sosial Kependudukan
    Route::prefix('penduduk')->group(function() {
        Route::get('/maps', [SosialKependudukanController::class, 'mapJumlahPenduduk']);
        Route::get('/pies', [SosialKependudukanController::class, 'pieJumlahPenduduk']);
    });
});
