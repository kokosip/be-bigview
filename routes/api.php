<?php

use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\AuthController;
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

    Route::put('/roles/{id}', [RoleController::class, 'updateRole']);
    Route::delete('/roles/{id}', [RoleController::class, 'deleteRole']);
    Route::get('/roles/{id}', [RoleController::class, 'getRoleById']);

    // Role - Menu
    Route::prefix('role-menu')->group(function() {
        Route::get('/', [RoleController::class, 'listRoleMenu']);
        Route::post('/', [RoleController::class, 'addRoleMenu']);
        Route::delete('/', [RoleController::class, 'deleteRoleMenu']);
    });

});
