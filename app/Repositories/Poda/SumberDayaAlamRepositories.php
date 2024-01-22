<?php

namespace App\Repositories\Poda;

use Exception;
use Illuminate\Support\Facades\DB;

class SumberDayaAlamRepositories {

    public function getListIndikator($idUsecase, $subject){
        $db = DB::table('mart_poda_sda_list_indikator')
            ->where('id_usecase', $idUsecase)
            ->where('subject', $subject)
            ->pluck('indikator');

        return $db;
    }

    public function getListJenis($idUsecase, $subject, $indikator){
        $db = DB::table('mart_poda_sda_list_jenis')
            ->where('id_usecase', $idUsecase)
            ->where('subject', $subject)
            ->where('indikator', $indikator)
            ->pluck('jenis');

        return $db;
    }

    public function getListTahun($idUsecase, $subject, $indikator){
        $db = DB::table('mart_poda_sda_filer_tahun')
            ->where('id_usecase', $idUsecase)
            ->where('subject', $subject)
            ->where('indikator', $indikator)
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return $db;
    }

    public function getPeriodeSda($idUsecase, $subject, $indikator){
        $db = DB::table('mart_poda_sda_filer_periode')
            ->select('minYear', 'maxYear')
            ->where('id_usecase', $idUsecase)
            ->where('subject', $subject)
            ->where('indikator', $indikator)
            ->first();

        return $db;
    }

    public function getMapSda($idUsecase, $subject, $params){
        $db = DB::table('mart_poda_sda_leaflet')
            ->selectRaw("city, CAST(data as SIGNED) as data")
            ->where('id_usecase', $idUsecase)
            ->where('subject', $subject)
            ->where('indikator', $params['indikator'])
            ->where('jenis', $params['jenis'])
            ->where('tahun', $params['tahun'])
            ->get();

        return $db;
    }

    public function getBarSda($idUsecase, $subject, $params){
        $db = DB::table('mart_poda_sda_bar_chart')
            ->select('chart_categories', 'data')
            ->where('id_usecase', $idUsecase)
            ->where('subject', $subject)
            ->where('indikator', $params['indikator'])
            ->where('jenis', $params['jenis'])
            ->where('tahun', $params['tahun'])
            ->get();

        return $db;
    }

    public function getAreaSda($idUsecase, $subject, $params){
        $periode = explode('-', $params['periode']);

        $startYear = $periode[0];
        $endYear = $periode[1];

        $db = DB::table('mart_poda_sda_area_chart')
            ->select('category', 'total as data')
            ->where('id_usecase', $idUsecase)
            ->where('subject', $subject)
            ->where('indikator', $params['indikator'])
            ->where('jenis', $params['jenis'])
            ->whereBetween('category', [$startYear, $endYear])
            ->orderBy('category', 'desc')
            ->get();

        return $db;
    }
}
