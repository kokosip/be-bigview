<?php

use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UsecaseController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MasterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Poda\EkonomiPerdaganganController;
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
    // Sosial & Kependudukan
    Route::prefix('sosial')->group(function() {
        //Kependudukan
        Route::prefix('penduduk')->group(function() {
            Route::get('/maps', [SosialKependudukanController::class, 'mapJumlahPenduduk']);
            Route::get('/pies', [SosialKependudukanController::class, 'pieJumlahPenduduk']);
            Route::get('/bars', [SosialKependudukanController::class, 'barJumlahPenduduk']);
            Route::get('/tahun', [SosialKependudukanController::class, 'tahunJumlahPenduduk']);
            Route::get('/details', [SosialKependudukanController::class, 'detailJumlahPenduduk']);
        });

        // Rentang Usia
        Route::prefix('rentang')->group(function() {
            Route::get('/stacked-bars', [SosialKependudukanController::class, 'stackedBarRentangUsia']);
            Route::get('/tahun', [SosialKependudukanController::class, 'tahunRentangUsia']);
            Route::get('/details', [SosialKependudukanController::class, 'detailRentangUsia']);
        });

        // Laju Pertumbuhan Penduduk
        Route::prefix('laju')->group(function() {
            Route::get('/dual-axes', [SosialKependudukanController::class, 'dualAxesLaju']);
            Route::get('/periode', [SosialKependudukanController::class, 'periodeLaju']);
            Route::get('/daerah', [SosialKependudukanController::class, 'namaDaerahLaju']);
            Route::get('/details', [SosialKependudukanController::class, 'detailLaju']);
        });

        // Rasio Jenis Kelamin
        Route::prefix('rasio')->group(function() {
            Route::get('/bars', [SosialKependudukanController::class, 'barRasio']);
            Route::get('/maps', [SosialKependudukanController::class, 'mapRasio']);
            Route::get('/tahun', [SosialKependudukanController::class, 'tahunRasio']);
            Route::get('/details', [SosialKependudukanController::class, 'detailRasio']);
        });

        // Kepadatan Penduduk
        Route::prefix('kepadatan')->group(function() {
            Route::get('/bars', [SosialKependudukanController::class, 'barKepadatan']);
            Route::get('/maps', [SosialKependudukanController::class, 'mapKepadatan']);
            Route::get('/tahun', [SosialKependudukanController::class, 'tahunKepadatan']);
            Route::get('/details', [SosialKependudukanController::class, 'detailKepadatan']);
        });

        // IPM
        Route::prefix('ipm')->group(function() {
            Route::get('/areas', [SosialKependudukanController::class, 'areaIPM']);
            Route::get('/maps', [SosialKependudukanController::class, 'mapIPM']);
            Route::get('/periode', [SosialKependudukanController::class, 'periodeIPM']);
            Route::get('/daerah', [SosialKependudukanController::class, 'namaDaerahIPM']);
            Route::get('/indikator', [SosialKependudukanController::class, 'indikatorIPM']);
            Route::get('/details', [SosialKependudukanController::class, 'detailIPM']);
        });

        // Kemiskinan
        Route::prefix('kemiskinan')->group(function() {
            Route::get('/indikator', [SosialKependudukanController::class, 'indikatorKemiskinan']);
            Route::get('/tahun', [SosialKependudukanController::class, 'tahunKemiskinan']);
            Route::get('/periode', [SosialKependudukanController::class, 'periodeKemiskinan']);
            Route::get('/maps', [SosialKependudukanController::class, 'mapKemiskinan']);
            Route::get('/areas', [SosialKependudukanController::class, 'areaKemiskinan']);
            Route::get('/daerah', [SosialKependudukanController::class, 'daerahKemiskinan']);
            Route::get('/details', [SosialKependudukanController::class, 'detailKemiskinan']);
        });

        // Pekerjaan dan Angkatan Kerja
        Route::prefix('pekerjaan')->group(function() {
            Route::get('/indikator', [SosialKependudukanController::class, 'indikatorPekerjaan']);
            Route::get('/tahun', [SosialKependudukanController::class, 'tahunPekerjaan']);
            Route::get('/tahun/jenis', [SosialKependudukanController::class, 'tahunJenisPekerjaan']);
            Route::get('/periode', [SosialKependudukanController::class, 'periodePekerjaan']);
            Route::get('/bars', [SosialKependudukanController::class, 'barJenisPekerjaan']);
            Route::get('/maps', [SosialKependudukanController::class, 'mapPekerjaan']);
            Route::get('/lines', [SosialKependudukanController::class, 'linePekerjaan']);
        });

        // Pendidikan
        Route::prefix('pendidikan')->group(function() {
            Route::get('/tahun/ajaran', [SosialKependudukanController::class, 'tahunAjaranPendidikan']);
            Route::get('/tahun', [SosialKependudukanController::class, 'tahunPendidikan']);
            Route::get('/jenjang', [SosialKependudukanController::class, 'jenjangPendidikan']);
            Route::get('/indikator', [SosialKependudukanController::class, 'indikatorPendidikan']);
            Route::get('/maps', [SosialKependudukanController::class, 'mapPendidikan']);
            Route::get('/bars', [SosialKependudukanController::class, 'barPendidikan']);
            Route::get('/bars/jenjang', [SosialKependudukanController::class, 'barJenjangPendidikan']);
        });

        // Kesehatan
        Route::prefix('kesehatan')->group(function() {
            Route::get('/tahun', [SosialKependudukanController::class, 'tahunKesehatan']);
            Route::get('/periode', [SosialKependudukanController::class, 'periodeKesehatan']);
            Route::get('/indikator', [SosialKependudukanController::class, 'indikatorKesehatan']);
            Route::get('/maps', [SosialKependudukanController::class, 'mapKesehatan']);
            Route::get('/bars', [SosialKependudukanController::class, 'barKesehatan']);
            Route::get('/bars-column', [SosialKependudukanController::class, 'barColumnKesehatan']);
        });
    });

    Route::prefix('ekonomi')->group(function() {
        Route::prefix('inflasi')->group(function() {
            Route::get('/periode', [EkonomiPerdaganganController::class, 'monthPeriodeInflasi']);
            Route::get('/daerah', [EkonomiPerdaganganController::class, 'namaDaerahInflasi']);
            Route::get('/tahun', [EkonomiPerdaganganController::class, 'tahunInflasi']);
            Route::get('/bulan', [EkonomiPerdaganganController::class, 'bulanInflasi']);
            Route::get('/maps', [EkonomiPerdaganganController::class, 'mapInflasi']);
            Route::get('/dual-chart', [EkonomiPerdaganganController::class, 'dualChartInflasi']);
        });

        Route::prefix('pdrb')->group(function() {
            Route::get('/tahun', [EkonomiPerdaganganController::class, 'tahunPDRB']);
            Route::get('/kategori', [EkonomiPerdaganganController::class, 'kategoriPDRB']);
            Route::get('/sektor', [EkonomiPerdaganganController::class, 'sektorPDRB']);
            Route::get('/card', [EkonomiPerdaganganController::class, 'cardPDRB']);
            Route::get('/bars', [EkonomiPerdaganganController::class, 'barPDRB']);
        });
    });

    Route::prefix('sda')->group(function() {

    });
});
