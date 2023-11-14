<?php

use App\Http\Controllers\Admin\MenuController;
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
    Route::post('/menus', [MenuController::class, 'addMenu']);
    Route::get('/menus', [MenuController::class, 'listMenu']);
    Route::get('/menus/parent', [MenuController::class, 'filterMenuUtama']);
});
