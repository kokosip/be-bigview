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
    public function getMapJumlahPenduduk($idUsecase, $tahun){
        $db = DB::table('mart_poda_social_kependudukan_map_leaflet')
            ->select('city', 'Lakilaki', 'Perempuan', 'lat', 'lon')
            ->where('tahun', $tahun['tahun'])
            ->where('id_usecase', $idUsecase)
            ->get();

        return $db;
    }

    public function getPieJumlahPenduduk($idUsecase, $tahun){
        $db = DB::table('mart_poda_social_kependudukan_pie_chart')
            ->select('sumber_data', 'tahun', 'lakilaki', 'Perempuan', 'jumlah')
            ->where('tahun', $tahun['tahun'])
            ->where('id_usecase', $idUsecase)
            ->first();

        return $db;
    }

    public function getBarJumlahPenduduk($idUsecase, $tahun){
        $db = DB::table('mart_poda_social_kependudukan_bar_chart')
            ->select('city', 'datacontent as data')
            ->where('tahun', $tahun['tahun'])
            ->where('id_usecase', $idUsecase)
            ->get();

        return $db;
    }

    public function getDetailJumlahPenduduk($idUsecase, $tahun){
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

    public function getStackedBarRentangUsia($idUsecase, $tahun){
        $db = DB::table('mart_poda_social_rentang_usia_bar_chart')
            ->select('name', 'chart_categories', 'data')
            ->where('id_usecase', $idUsecase)
            ->where('tahun', $tahun['tahun'])
            ->get();

        return $db;
    }
    // End Rentang Usia

    // Start Rasio Jenis Kelamin
    public function getTahunRasio($idUsecase){
        $db = DB::table('mart_poda_social_rasio_jk_filter_tahun')
            ->distinct()->where('id_usecase', $idUsecase)
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return $db;
    }

    public function getMapRasio($idUsecase, $tahun){
        $db = DB::table('mart_poda_social_rasio_jk_map_leaflet')
            ->select('city', 'lat', 'lon', 'rasio as jumlah')
            ->where('tahun', $tahun['tahun'])
            ->where('id_usecase', $idUsecase)
            ->get();

        return $db;
    }

    public function getBarRasio($idUsecase, $tahun){
        $db = DB::table('mart_poda_social_rasio_jk_bar_chart')
            ->select('chart_categories as city', 'data')
            ->where('tahun', $tahun['tahun'])
            ->where('id_usecase', $idUsecase)
            ->get();

        return $db;
    }
    // End Rasio Jenis Kelamin

    // Start Kepadatan Penduduk
    public function getTahunKepadatan($idUsecase){
        $db = DB::table('mart_poda_social_kepadatan_penduduk_filter_tahun')
            ->distinct()->where('id_usecase', $idUsecase)
            ->orderBy('years', 'desc')
            ->pluck('years');

        return $db;
    }

    public function getMapKepadatan($idUsecase, $tahun){
        $db = DB::table('mart_poda_social_rasio_jk_map_leaflet')
            ->select('city', 'lat', 'lon', 'rasio as jumlah')
            ->where('tahun', $tahun['tahun'])
            ->where('id_usecase', $idUsecase)
            ->get();

        return $db;
    }

    public function getBarKepadatan($idUsecase, $tahun){
        $db = DB::table('mart_poda_social_rasio_jk_bar_chart')
            ->select('chart_categories as city', 'data')
            ->where('tahun', $tahun['tahun'])
            ->where('id_usecase', $idUsecase)
            ->get();

        return $db;
    }
    // End Kepadatan Penduduk

    // Start IPM
    public function getPeriodeIPM($idUsecase, $filter){
        $db = DB::table('mart_poda_social_ipm_year_periode')
            ->select('startYear', 'endYear', 'minYear', 'maxYear')
            ->where('id_usecase', $idUsecase)
            ->where('filter', $filter['filter'])
            ->first();

        return $db;
    }

    public function getNamaDaerahIPM($idUsecase, $filter){
        $db = DB::table('mart_poda_social_ipm_area_chart')->distinct()
            ->where('id_usecase', $idUsecase)
            ->where('filter', $filter['filter'])
            ->orderBy('city', 'asc')
            ->pluck('city');

        return $db;
    }

    public function getIndikatorIPM($idUsecase){
        $db = DB::table('mart_poda_social_ipm_filter_indikator')->distinct()
            ->where('id_usecase', $idUsecase)
            ->pluck('nama');

        return $db;
    }

    public function getMapIPM($idUsecase, $params){
        $db = DB::table('mart_poda_social_ipm_map_leaflet')
            ->select('city','lat','lon','ipm as data')
            ->where('id_usecase', $idUsecase)
            ->where('filter', $params['filter'])
            ->where('tahun', $params['tahun'])
            ->get();

        return $db;
    }
    // End IPM

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
