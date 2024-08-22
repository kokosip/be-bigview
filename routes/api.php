<?php

use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UsecaseController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MasterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Content\LogoController;
use App\Http\Controllers\Pariwisata\TelkomselController;
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
Route::post('/adminlogin', [AuthController::class, 'loginSuper']);


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('admin')->group(function () {
    Route::middleware(['auth'])->group(function () {
        // Master
        Route::middleware(['superadmin'])->group(function () {
            Route::get('/prov', [MasterController::class, 'listProvinsi']);
            Route::get('/kabkota', [MasterController::class, 'listKabkota']);
        });

        // Menu
        Route::prefix('menus')->group(function () {
            Route::prefix('exec')->group(function () {
                Route::middleware(['superadmin'])->group(function () {
                    Route::post('/', [MenuController::class, 'addMenu']);
                    Route::get('/', [MenuController::class, 'listMenu']);
                    Route::get('/parent', [MenuController::class, 'menuUtama']);
                    Route::put('/{id}', [MenuController::class, 'updateMenu']);
                    Route::delete('/{id}', [MenuController::class, 'deleteMenu']);
                    Route::get('/{id}', [MenuController::class, 'getMenuById']);
                });
            });
            Route::prefix('assigned')->group(function () {
                Route::middleware(['admin'])->group(function () {
                    Route::put('/', [MenuController::class, 'sortMenu']);
                });
                Route::get('/', [MenuController::class, 'getAssignedMenu']);
                Route::put('/{id}', [MenuController::class, 'updateAssignedMenu'])->middleware('menuaccess');;
            });
        });

        // Role
        Route::prefix('roles')->group(function () {
            Route::middleware(['superadmin'])->group(function () {
                Route::post('/', [RoleController::class, 'addRole']); // ??
                Route::get('/', [RoleController::class, 'listRole']);
                Route::get('/names', [RoleController::class, 'listNamesRole']);
                Route::put('/{id}', [RoleController::class, 'updateRole']);
                Route::delete('/{id}', [RoleController::class, 'deleteRole']); // ??
                Route::get('/{id}', [RoleController::class, 'getRoleById']);
            });
        });

        // Role - Menu
        Route::prefix('role-menu')->group(function() {
            Route::middleware(['superadmin'])->group(function () {
                Route::get('/', [RoleController::class, 'listRoleMenu']);
                Route::post('/', [RoleController::class, 'addRoleMenu']); // ??
                Route::delete('/', [RoleController::class, 'deleteRoleMenu']); // ??
                Route::post('/sub', [MenuController::class, 'editSubadminMenu']); // ??
            });
        });

        // Usecase
        Route::prefix('usecase')->group(function() {
            Route::middleware(['superadmin'])->group(function () {
                Route::get('', [UsecaseController::class, 'listUsecase']);
                Route::get('names', [UsecaseController::class, 'listNameUsecase']);
                Route::get('{id}', [UsecaseController::class, 'getUsecaseById']);
                Route::post('', [UsecaseController::class, 'addUsecase']);
                Route::post('profile/{id}', [UsecaseController::class, 'addUsecaseProfile']);
                Route::put('{id}', [UsecaseController::class, 'updateUsecase']);
                Route::put('profile/{id}', [UsecaseController::class, 'updateUsecaseProfile']);
                Route::delete('{id}', [UsecaseController::class, 'deleteUsecase']);
                Route::delete('profile/{id}', [UsecaseController::class, 'deleteUsecaseProfile']);
                Route::put('polygon/{id}', [UsecaseController::class, 'updateUsecasePolygon']);
            });
        });

        Route::prefix('usecase-polygon')->group(function() {
            Route::get('', [UsecaseController::class, 'getUserPolygon']);
            
            Route::middleware(['superadmin'])->group(function () {
                Route::get('all', [UsecaseController::class, 'getAllPolygon']);
                Route::post('', [UsecaseController::class, 'uploadPolygon']);
                Route::put('', [UsecaseController::class, 'updateUsecasePolygon']);
                Route::get('{id}', [UsecaseController::class, 'getUsecasePolygon']);
            });
        });

        Route::prefix('pariwisata')->group(function() {
            Route::prefix('telkomsel')->group(function() {
                Route::middleware(['subadmin'])->group(function () {
                    Route::get('tripmap', [TelkomselController::class, 'tripMap']);
                    Route::get('toporigin', [TelkomselController::class, 'topOrigin']);
                    Route::get('topdestination', [TelkomselController::class, 'topDestination']);
                    Route::get('numberoftrips', [TelkomselController::class, 'numberOfTrips']);
                    Route::get('numberoftripsorigin', [TelkomselController::class, 'numberOfTripsOrigin']);
                    Route::get('numberoftripsdestination', [TelkomselController::class, 'numberOfTripsDestination']);
                    Route::get('movementoftrips', [TelkomselController::class, 'movementOfTrips']);
                    Route::get('lengthofstay', [TelkomselController::class, 'lengthOfStay']);
                    Route::get('movementofgender', [TelkomselController::class, 'movementOfGender']);
                    Route::get('movementofage', [TelkomselController::class, 'movementOfAge']);
                    Route::get('statusses', [TelkomselController::class, 'statusSES']);
                    Route::get('matrixorigin', [TelkomselController::class, 'matrixOrigin']);
                    Route::get('jeniswisatawan', [TelkomselController::class, 'jenisWisatawan']);
                    Route::get('filterprovinsi', [TelkomselController::class, 'filterProvinsi']);
                    Route::get('filterkabkota', [TelkomselController::class, 'filterKabKota']);
                    Route::get('filtertahun', [TelkomselController::class, 'filterTahun']);
                    Route::get('filterperiode', [TelkomselController::class, 'filterPeriode']);
                    Route::get('movementtripmap', [TelkomselController::class, 'movementTripMap']);
                    Route::get('movementheatmap', [TelkomselController::class, 'movementHeatMap']);
                    Route::get('filtersingleyear', [TelkomselController::class, 'filterSingleYear']);
                    Route::get('trendjumlahperjalanan', [TelkomselController::class, 'trendJumlahPerjalanan']);
                    Route::get('filtermonth', [TelkomselController::class, 'filterMonth']);
                    Route::get('tempatwisata', [TelkomselController::class, 'tempatWisata']);
                    Route::get('filterdestination', [TelkomselController::class, 'filterDestination']);
                    Route::get('trendwisatawanbylamatinggal', [TelkomselController::class, 'trendWisatawanByLamaTinggal']);
                    Route::get('jumlahwisatawan', [TelkomselController::class, 'jumlahWisatawan']);
                    Route::get('kelompokusiawisatawan', [TelkomselController::class, 'kelompokUsiaWisatawan']);
                });
            });
        });
        Route::prefix('cms')->group(function() {
            Route::middleware(['subadmin'])->group(function () {
                Route::get('sektor/{id}', [UsecaseController::class, 'listSektor']);
                Route::get('data/{id}', [UsecaseController::class, 'listDataSektor']);
                Route::get('indikator/{id}', [UsecaseController::class, 'listIndikator']);
                Route::get('satuan', [UsecaseController::class, 'listSatuan']);
                Route::get('opd/{id}', [UsecaseController::class, 'listOpd']);
                Route::post('sektor/{id}', [UsecaseController::class, 'addSektorIku']);
                Route::put('sektor/{id}', [UsecaseController::class, 'updateSektorIku']);
                Route::delete('sektor/{id}', [UsecaseController::class, 'deleteSektorIku']);
                Route::post('indikator/{id}', [UsecaseController::class, 'addIndikator']);
                Route::post('sektor/import/{id}', [UsecaseController::class, 'importSektorIKU']);
            });
        });

        // User
        Route::middleware(['admin'])->group(function () {
            Route::middleware(['superadmin'])->group(function () {
                Route::get('/users', [UserController::class, 'listUser']);
                Route::post('/users', [UserController::class, 'addUser']);
                Route::put('/users/active/{id}', [UserController::class, 'updateIsActived']);
                Route::get('/users/{id}', [UserController::class, 'getUserById']);
                Route::delete('/users/{id}', [UserController::class, 'deleteUser']);
                Route::put('/users/{id}', [UserController::class, 'updateUser']);
            });
            Route::get('/userdetail', [UserController::class, 'userDetail']);
            Route::post('/users/subadmin', [UserController::class,'addSubAdmin']);
        });
    });

    

    // Content
    Route::prefix('content')->group(function() {
        Route::prefix('logo')->group(function() {
            Route::middleware(['superadmin'])->group(function () {
                Route::post('/{id}', [UsecaseController::class,'uploadLogo']);
                Route::get('/{id}', [UsecaseController::class,'getLogo']);
                Route::delete('/{id}', [UsecaseController::class,'deleteLogo']);
            });
        });
        Route::prefix('beranda')->group(function() {
            Route::middleware(['superadmin'])->group(function () {
                Route::prefix('pimpinan-daerah')->group(function() {
                    Route::post('upload/{id}', [UsecaseController::class, 'uploadProfilePimpinan']);
                });
                Route::prefix('kontak')->group(function() {
                    Route::post('update/{id}', [UsecaseController::class, 'updateContact']);
                });
            });
        });
        Route::prefix('visi-misi')->group(function() {
            Route::middleware(['superadmin'])->group(function () {
                Route::prefix('visi')->group(function() {
                    Route::post('{id}', [UsecaseController::class,'addVisi']);
                    Route::put('{id}', [UsecaseController::class,'updateVisi']);
                    Route::delete('{id}', [UsecaseController::class, 'deleteVisi']);
                    Route::get('{id}', [UsecaseController::class,'listVisi']);
                });
                Route::prefix('misi')->group(function() {
                    Route::post('{id}', [UsecaseController::class,'addMisi']);
                    Route::put('{id}', [UsecaseController::class,'updateMisi']);
                    Route::delete('{id}', [UsecaseController::class,'deleteMisi']);
                    Route::get('{id}', [UsecaseController::class,'listMisi']);
                });
            });
        });
        Route::prefix('sektor')->group(function() {
            Route::prefix('exec')->group(function() {
                Route::middleware(['superadmin'])->group(function () {
                    Route::post('{id}', [UsecaseController::class,'addSektor']);
                    Route::delete('{id}', [UsecaseController::class,'deleteSektor']);
                    Route::put('{id}', [UsecaseController::class,'updateSektor']);
                    Route::get('{id}', [UsecaseController::class,'getSektorUsecase']);
                });
            });
            Route::middleware(['admin'])->group(function () {
                Route::post('sub/{id}', [UsecaseController::class,'editSubadminSektor']);
                Route::post('sort/{id}', [UsecaseController::class,'sortSektor']);
            });
            Route::middleware(['subadmin'])->group(function () {
                Route::get('assigned', [UsecaseController::class,'getAssignedSektor']);
                Route::put('assigned/{id}', [UsecaseController::class,'updateAssignedSektor']);
            });
        });
    });
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