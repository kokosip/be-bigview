<?php

namespace App\Repositories\Poda;

use Exception;
use Illuminate\Support\Facades\DB;

class SosialKependudukanRepositories {

    public function getTahunJumlahPenduduk($idUsecase){
        $db = DB::table('mart_poda_social_kependudukan_filter_tahun')
            ->where('id_usecase', $idUsecase)
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return $db;
    }

    // Start Kependudukan
    public function getMapJumlahPenduduk($tahun, $idUsecase){
        $db = DB::table('mart_poda_social_kependudukan_map_leaflet')
            ->select('city', 'Lakilaki', 'Perempuan', 'lat', 'lon')
            ->where('tahun', $tahun)
            ->where('id_usecase', $idUsecase)
            ->get();

        return $db;
    }

    public function getPieJumlahPenduduk($tahun, $idUsecase){
        $db = DB::table('mart_poda_social_kependudukan_pie_chart')
            ->select('sumber_data', 'tahun', 'lakilaki', 'Perempuan', 'jumlah')
            ->where('tahun', $tahun)
            ->where('id_usecase', $idUsecase)
            ->first();

        return $db;
    }

    public function getBarJumlahPenduduk($tahun, $idUsecase){
        $db = DB::table('mart_poda_social_kependudukan_bar_chart')
            ->select('city', 'datacontent as data')
            ->where('tahun', $tahun)
            ->where('id_usecase', $idUsecase)
            ->get();

        return $db;
    }

    public function getDetailJumlahPenduduk($tahun, $idUsecase){
        $db = DB::table('mart_poda_social_kependudukan_detail')
            ->select('city', 'sumber', 'tahun', 'Lakilaki', 'Perempuan', 'jumlah')
            ->where('tahun', $tahun)
            ->where('id_usecase', $idUsecase)
            ->orderBy('jumlah', 'desc')
            ->get();

        return $db;
    }
    // End Kependudukan

    // Start Rentang Usia
    public function getTahunRentangUsia($idUsecase){
        $db = DB::table('mart_poda_social_rentang_usia_filter_tahun')
            ->where('id_usecase', $idUsecase)
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return $db;
    }

    public function getStackedBarRentangUsia($tahun, $idUsecase){
        $db = DB::table('mart_poda_social_rentang_usia_bar_chart')
            ->select('name', 'chart_categories', 'data')
            ->where('id_usecase', $idUsecase)
            ->where('tahun', $tahun)
            ->get();

        return $db;
    }
    // End Rentang Usia

    // Start Kemiskinan
    public function getTahunKemiskinan($idUsecase){
        $db = DB::table('mart_poda_social_kemiskinan_filter_tahun')
            ->distinct()->where('id_usecase', $idUsecase)
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return $db;
    }

    public function getMapKemiskinan($tahun, $idUsecase){
        $db = DB::table('mart_poda_social_kemiskinan_map_leaflet')
            ->select('city', 'filter', 'value', 'lat', 'lon')
            ->where('tahun', $tahun)
            ->where('id_usecase', $idUsecase)
            ->get();

        return $db;
    }
    // End Kemiskinan
}
