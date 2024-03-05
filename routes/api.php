<?php

use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UsecaseController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MasterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Content\LogoController;
use App\Http\Controllers\Poda\EkonomiPerdaganganController;
use App\Http\Controllers\Poda\SosialKependudukanController;
use App\Http\Controllers\Poda\SumberDayaAlamController;
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

Route::prefix('content')->group(function () {
    Route::post('/upload/gov', [LogoController::class, 'uploadLogoGovern']);
    Route::get('/logo', [LogoController::class, 'getLogo']);
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
            Route::get('/details', [SosialKependudukanController::class, 'detailPekerjaan']);
            Route::get('/details/jenis', [SosialKependudukanController::class, 'detailJenisPekerjaan']);
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
            Route::get('/details', [SosialKependudukanController::class, 'detailPendidikan']);
        });

        // Kesehatan
        Route::prefix('kesehatan')->group(function() {
            Route::get('/tahun', [SosialKependudukanController::class, 'tahunKesehatan']);
            Route::get('/periode', [SosialKependudukanController::class, 'periodeKesehatan']);
            Route::get('/indikator', [SosialKependudukanController::class, 'indikatorKesehatan']);
            Route::get('/maps', [SosialKependudukanController::class, 'mapKesehatan']);
            Route::get('/bars', [SosialKependudukanController::class, 'barKesehatan']);
            Route::get('/bars-column', [SosialKependudukanController::class, 'barColumnKesehatan']);
            Route::get('/details', [SosialKependudukanController::class, 'detailKesehatan']);
        });
    });

    Route::prefix('ekonomi')->group(function() {
        Route::prefix('pad')->group(function() {
            Route::get('/areas', [EkonomiPerdaganganController::class, 'areaPad']);
            Route::get('/details', [EkonomiPerdaganganController::class, 'detailPad']);
        });

        Route::prefix('trend')->group(function() {
            Route::get('/periode', [EkonomiPerdaganganController::class, 'periodeTrendPerdagangan']);
            Route::get('/areas', [EkonomiPerdaganganController::class, 'areaTrendPerdagangan']);
            Route::get('/details', [EkonomiPerdaganganController::class, 'detailTrendPerdagangan']);
        });

        Route::prefix('komoditas')->group(function() {
            Route::get('/tahun', [EkonomiPerdaganganController::class, 'tahunKomoditas']);
            Route::get('/bars', [EkonomiPerdaganganController::class, 'barKomoditas']);
            Route::get('/details', [EkonomiPerdaganganController::class, 'detailKomoditas']);
        });

        Route::prefix('pad-kabkot')->group(function() {
            Route::get('/tahun', [EkonomiPerdaganganController::class, 'tahunPadKabKota']);
            Route::get('/bars', [EkonomiPerdaganganController::class, 'barPadKabKota']);
            Route::get('/details', [EkonomiPerdaganganController::class, 'detailPadKabKota']);
        });

        Route::prefix('inflasi')->group(function() {
            Route::get('/periode', [EkonomiPerdaganganController::class, 'monthPeriodeInflasi']);
            Route::get('/daerah', [EkonomiPerdaganganController::class, 'namaDaerahInflasi']);
            Route::get('/tahun', [EkonomiPerdaganganController::class, 'tahunInflasi']);
            Route::get('/bulan', [EkonomiPerdaganganController::class, 'bulanInflasi']);
            Route::get('/maps', [EkonomiPerdaganganController::class, 'mapInflasi']);
            Route::get('/dual-chart', [EkonomiPerdaganganController::class, 'dualChartInflasi']);
            Route::get('/details', [EkonomiPerdaganganController::class, 'detailInflasi']);
        });

        Route::prefix('pdrb')->group(function() {
            Route::get('/tahun', [EkonomiPerdaganganController::class, 'tahunPDRB']);
            Route::get('/kategori', [EkonomiPerdaganganController::class, 'kategoriPDRB']);
            Route::get('/sektor', [EkonomiPerdaganganController::class, 'sektorPDRB']);
            Route::get('/card', [EkonomiPerdaganganController::class, 'cardPDRB']);
            Route::get('/bars', [EkonomiPerdaganganController::class, 'barPDRB']);
            Route::get('/areas', [EkonomiPerdaganganController::class, 'areaPDRB']);
            Route::get('/details', [EkonomiPerdaganganController::class, 'detailPDRB']);
        });

        Route::prefix('pariwisata')->group(function() {
            Route::get('/indikator', [EkonomiPerdaganganController::class, 'indikatorPariwisata']);
            // Daya Tarik Wisata
            Route::prefix('dtw')->group(function() {
                Route::get('/daerah', [EkonomiPerdaganganController::class, 'namaDaerahPariwisataDTW']);
                Route::get('/periode', [EkonomiPerdaganganController::class, 'periodePariwisataDTW']);
                Route::get('/tahun', [EkonomiPerdaganganController::class, 'tahunPariwisataDTW']);
                Route::get('/maps', [EkonomiPerdaganganController::class, 'mapPariwisataDTW']);
                Route::get('/lines', [EkonomiPerdaganganController::class, 'linePariwisataDTW']);
                Route::get('/details', [EkonomiPerdaganganController::class, 'detailPariwisataDTW']);
            });

            // Daya Tarik Wisata
            Route::prefix('hotel')->group(function() {
                Route::get('/periode', [EkonomiPerdaganganController::class, 'periodePariwisataHotel']);
                Route::get('/tahun', [EkonomiPerdaganganController::class, 'tahunPariwisataHotel']);
                Route::get('/maps', [EkonomiPerdaganganController::class, 'mapPariwisataHotel']);
                Route::get('/bars', [EkonomiPerdaganganController::class, 'barPariwisataHotel']);
                Route::get('/lines', [EkonomiPerdaganganController::class, 'linePariwisataHotel']);
                Route::get('/details', [EkonomiPerdaganganController::class, 'detailPariwisataHotel']);
            });

            // Jumlah Wisatawan
            Route::prefix('wisatawan')->group(function() {
                Route::get('/periode', [EkonomiPerdaganganController::class, 'periodePariwisataWisatawan']);
                Route::get('/card', [EkonomiPerdaganganController::class, 'cardPariwisataWisatawan']);
                Route::get('/lines', [EkonomiPerdaganganController::class, 'linePariwisataWisatawan']);
                Route::get('/details', [EkonomiPerdaganganController::class, 'detailPariwisataWisatawan']);
            });

            // TPK
            Route::prefix('tpk')->group(function() {
                Route::get('/tahun', [EkonomiPerdaganganController::class, 'tahunPariwisataTPK']);
                Route::get('/bulan', [EkonomiPerdaganganController::class, 'bulanPariwisataTPK']);
                Route::get('/card', [EkonomiPerdaganganController::class, 'cardPariwisataTPK']);
                Route::get('/lines', [EkonomiPerdaganganController::class, 'linePariwisataTPK']);
                Route::get('/details', [EkonomiPerdaganganController::class, 'detailPariwisataTPK']);
            });

            // Jumlah Restoran dan Rumah Makan
            Route::prefix('resto')->group(function() {
                Route::get('/periode', [EkonomiPerdaganganController::class, 'periodePariwisataResto']);
                Route::get('/daerah', [EkonomiPerdaganganController::class, 'namaDaerahPariwisataResto']);
                Route::get('/tahun', [EkonomiPerdaganganController::class, 'tahunPariwisataResto']);
                Route::get('/maps', [EkonomiPerdaganganController::class, 'mapPariwisataResto']);
                Route::get('/lines', [EkonomiPerdaganganController::class, 'linePariwisataResto']);
                Route::get('/details', [EkonomiPerdaganganController::class, 'detailPariwisataResto']);
            });
        });
    });

    Route::prefix('sda')->group(function() {
        Route::get('/{subject}/indikator', [SumberDayaAlamController::class, 'listIndikator']);
        Route::get('/{subject}/jenis', [SumberDayaAlamController::class, 'listJenis']);
        Route::get('/{subject}/tahun', [SumberDayaAlamController::class, 'listTahun']);
        Route::get('/{subject}/periode', [SumberDayaAlamController::class, 'periodeSda']);
        Route::get('/{subject}/maps', [SumberDayaAlamController::class, 'mapSda']);
        Route::get('/{subject}/bars', [SumberDayaAlamController::class, 'barSda']);
        Route::get('/{subject}/areas', [SumberDayaAlamController::class, 'areaSda']);
        Route::get('/{subject}/details', [SumberDayaAlamController::class, 'detailSda']);
    });
});
