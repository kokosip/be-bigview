<?php

namespace App\Repositories\Poda;

use App\Exceptions\ErrorResponse;
use Exception;
use Illuminate\Support\Facades\DB;

class SumberDayaAlamRepositories {

    public function getListIndikator($idUsecase, $subject){
        try {
            $db = DB::table('mart_poda_sda_list_indikator')
                ->where('id_usecase', $idUsecase)
                ->where('subject', $subject)
                ->pluck('indikator');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil list indikator.');
        }
    }

    public function getListJenis($idUsecase, $subject, $indikator){
        try {
            $db = DB::table('mart_poda_sda_list_jenis')
                ->where('id_usecase', $idUsecase)
                ->where('subject', $subject)
                ->where('indikator', $indikator)
                ->pluck('jenis');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message:'Gagal mengambil list jenis.');
        }
    }

    public function getListTahun($idUsecase, $subject, $indikator){
        try {
            $db = DB::table('mart_poda_sda_filer_tahun')
                ->where('id_usecase', $idUsecase)
                ->where('subject', $subject)
                ->where('indikator', $indikator)
                ->orderBy('tahun', 'desc')
                ->pluck('tahun');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message:'Gagal mengambil list tahun.');
        }
    }

    public function getPeriodeSda($idUsecase, $subject, $indikator){
        try {
            $db = DB::table('mart_poda_sda_filer_periode')
                ->select('minYear', 'maxYear')
                ->where('id_usecase', $idUsecase)
                ->where('subject', $subject)
                ->where('indikator', $indikator)
                ->first();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message:'Gagal mengambil periode SDA');
        }
    }

    public function getMapSda($idUsecase, $subject, $params){
        try {
            $db = DB::table('mart_poda_sda_leaflet')
                ->selectRaw("city, CAST(data as SIGNED) as data")
                ->where('id_usecase', $idUsecase)
                ->where('subject', $subject)
                ->where('indikator', $params['indikator'])
                ->where('jenis', $params['jenis'])
                ->where('tahun', $params['tahun'])
                ->get();

            return $db;
        } catch (Exception $e){
            throw new ErrorResponse(type:'Internal Server Error', message:'Gagal mengambil map SDA.');
        }
    }

    public function getBarSda($idUsecase, $subject, $params){
        try {
            $db = DB::table('mart_poda_sda_bar_chart')
                ->select('chart_categories', 'data')
                ->where('id_usecase', $idUsecase)
                ->where('subject', $subject)
                ->where('indikator', $params['indikator'])
                ->where('jenis', $params['jenis'])
                ->where('tahun', $params['tahun'])
                ->get();

            return $db;
        } catch (Exception $e){
            throw new ErrorResponse(type:'Internal Server Error', message:'Gagal mengambil bar SDA.');
        }
    }

    public function getAreaSda($idUsecase, $subject, $params){
        try {
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
        } catch (Exception $e){
            throw new ErrorResponse(type:'Internal Server Error', message:'Gagal mengambil area SDA.');
        }
    }

    public function getDetailSda($idUsecase, $subject, $params){
        try {
            $periode = explode('-', $params['periode']);

            $startYear = $periode[0];
            $endYear = $periode[1];

            $db = DB::table('mart_poda_sda_detail_data')
                ->select('kabupaten_kota as category', 'tahun as column', 'data')
                ->where('id_usecase', $idUsecase)
                ->where('subject', $subject)
                ->where('indikator', $params['indikator'])
                ->where('jenis', $params['jenis'])
                ->whereBetween('tahun', [$startYear, $endYear])
                ->get();

            return $db;
        } catch (Exception $e){
            throw new ErrorResponse(type:'Internal Server Error', message:'Gagal mengambil detail SDA.');
        }
    }
}
