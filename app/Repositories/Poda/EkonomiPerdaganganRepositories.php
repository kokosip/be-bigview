<?php

namespace App\Repositories\Poda;

use Exception;
use Illuminate\Support\Facades\DB;

class EkonomiPerdaganganRepositories {

    public function getMonthPeriodeInflasi($idUsecase){
        $db = DB::table('mart_poda_eko_inflasi_ihk_filter_year')
            ->select('bulan', 'tahun', 'id_bulan')
            ->where('id_usecase', $idUsecase)
            ->orderBy('id_bulan', 'desc')
            ->get();

        return $db;
    }

    public function getNamaDaerahInflasi($idUsecase){
        $db = DB::table('mart_poda_eko_inflasi_ihk_filter_kabkot')->distinct()
            ->where('id_usecase', $idUsecase)
            ->orderBy('name', 'asc')
            ->pluck('name');

        return $db;
    }

    public function getTahunInflasi($idUsecase){
        $db = DB::table('mart_poda_eko_inflasi_ihk_filter_year')->distinct()
            ->where('id_usecase', $idUsecase)
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return $db;
    }

    public function getBulanInflasi($idUsecase, $tahun){
        $db = DB::table('mart_poda_eko_inflasi_ihk_filter_year')
            ->where('id_usecase', $idUsecase)
            ->where('tahun', $tahun)
            ->orderBy('id_bulan', 'asc')
            ->pluck('bulan');

        return $db;
    }

    public function getMapInflasi($idUsecase, $params){
        $db = DB::table('mart_poda_eko_inflasi_ihk_map')
            ->select('jenis', 'city', 'lat', 'lon', 'merged_data as data')
            ->where('id_usecase', $idUsecase)
            ->where('tahun', $params['tahun'])
            ->where('bulan', $params['bulan'])
            ->get();

        return $db;
    }

    public function getDualChartInflasi($idUsecase, $params){
        $datemonth = explode('-', $params['periode']);

        $startDateMonth = substr($datemonth[0], 0, 4)."-".substr($datemonth[0], 4)."-01";
        $endDateMonth = substr($datemonth[1], 0, 4)."-".substr($datemonth[1], 4)."-02";

        $db = DB::table('mart_poda_eko_inflasi_ihk_chart')
            ->select('name as jenis', 'bulan', 'tahun', 'value as data')
            ->where('id_usecase', $idUsecase)
            ->where('city', $params['nama_daerah'])
            ->whereBetween('id_bulan', [$startDateMonth, $endDateMonth])
            ->get();

        return $db;
    }
}
