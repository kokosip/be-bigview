<?php

namespace App\Repositories\Poda;

use App\Exceptions\ErrorResponse;
use Exception;
use Illuminate\Support\Facades\DB;

class EkonomiPerdaganganRepositories {

    // Start PAD
    public function getAreaPad($idUsecase){
        try {
            $db = DB::table('mart_poda_eko_pad')
                ->select('category', 'total as data')
                ->where('id_usecase', $idUsecase)
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan area pad.');
        }
    }

    public function getDetailPad($idUsecase){
        try {
            $db = DB::table('mart_poda_eko_pad_detail')
                ->select('city as category', 'tahun as column', 'pad as data')
                ->where('id_usecase', $idUsecase)
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan detail pad.');
        }
    }
    // End PAD

    // Start Trend-Perdagangan
    public function getPeriodeTrendPerdagangan($idUsecase){
        try {
            $db = DB::table('mart_poda_eko_tren_perdagangan_filter_year')
                ->select('minYear', 'maxYear')
                ->where('id_usecase', $idUsecase)
                ->first();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan periode trend perdagangan.');
        }
    }

    public function getAreaTrendPerdagangan($idUsecase, $periode){
        try {
            $tahun = explode('-', $periode['periode']);

            $startYear = $tahun[0];
            $endYear = $tahun[1];

            $db = DB::table('mart_poda_eko_tren_perdagangan_line_chart')
                ->select('category', 'total as data')
                ->where('id_usecase', $idUsecase)
                ->whereBetween('category', [$startYear, $endYear])
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan area trend perdagangan.');
        }
    }

    public function getDetailTrendPerdagangan($idUsecase, $periode){
        try {
            $tahun = explode('-', $periode['periode']);

            $startYear = $tahun[0];
            $endYear = $tahun[1];

            $db = DB::table('mart_poda_eko_tren_perdagangan_detail')
                ->select('city as category', 'tahun as column', 'total as data')
                ->where('id_usecase', $idUsecase)
                ->whereBetween('tahun', [$startYear, $endYear])
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan detail trend perdagangan.');
        }
    }
    // End Trend-Perdagangan

    // Start Top Komoditas
    public function getTahunKomoditas($idUsecase){
        try {
            $db = DB::table('mart_poda_eko_komoditas_perdagangan')->distinct()
                ->where('id_usecase', $idUsecase)
                ->orderBy('tahun', 'desc')
                ->pluck('tahun');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan tahun komoditas.');
        }
    }

    public function getBarKomoditas($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_eko_komoditas_perdagangan')
                ->select('chart_categories', 'nilai as data')
                ->where('id_usecase', $idUsecase)
                ->where('tahun', $tahun)
                ->orderBy('data', 'desc')
                ->limit(10)
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan bar komoditas.');
        }
    }

    public function getDetailKomoditas($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_eko_komoditas_perdagangan')
                ->selectRaw("chart_categories as category, 'Nominal' as `column`, nilai as data")
                ->where('id_usecase', $idUsecase)
                ->where('tahun', $tahun)
                ->orderBy('data', 'desc')
                ->limit(10)
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan detail komoditas.');
        }
    }
    // End Top Komoditas

    // Start PAD KabKot
    public function getTahunPadKabKota($idUsecase){
        try {
            $db = DB::table('mart_poda_eko_pad')->distinct()
                ->where('id_usecase', $idUsecase)
                ->orderBy('category', 'desc')
                ->pluck('category');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan tahun PAD Kabupaten/Kota.');
        }
    }

    public function getBarPadKabKota($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_eko_pad_detail')
                ->select('city as chart_categories', 'pad as data')
                ->where('id_usecase', $idUsecase)
                ->where('tahun', $tahun)
                ->orderBy('data', 'desc')
                ->limit(10)
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan bar PAD Kabupaten/Kota.');
        }
    }

    public function getDetailPadKabKota($idUsecase, $tahun){
        try {
            $startYear = $tahun['tahun'] - 2;

            $db = DB::table('mart_poda_eko_pad_detail')
                ->selectRaw("city as category, tahun as `column`, pad as data")
                ->where('id_usecase', $idUsecase)
                ->whereBetween('tahun', [$startYear, $tahun['tahun']])
                ->orderBy('data', 'desc')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan detail PAD Kabupaten/Kota.');
        }
    }
    // End PAD KabKot

    // Start Inflasi dan IHK
    public function getMonthPeriodeInflasi($idUsecase){
        try {
            $db = DB::table('mart_poda_eko_inflasi_ihk_filter_year')
                ->select('bulan', 'tahun', 'id_bulan')
                ->where('id_usecase', $idUsecase)
                ->orderBy('id_bulan', 'desc')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan bulan periode inflasi.');
        }
    }

    public function getNamaDaerahInflasi($idUsecase){
        try {
            $db = DB::table('mart_poda_eko_inflasi_ihk_filter_kabkot')->distinct()
                ->where('id_usecase', $idUsecase)
                ->orderBy('name', 'asc')
                ->pluck('name');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan nama periode inflasi.');
        }
    }

    public function getTahunInflasi($idUsecase){
        try {
            $db = DB::table('mart_poda_eko_inflasi_ihk_filter_year')->distinct()
                ->where('id_usecase', $idUsecase)
                ->orderBy('tahun', 'desc')
                ->pluck('tahun');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan tahun periode inflasi.');
        }
    }

    public function getBulanInflasi($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_eko_inflasi_ihk_filter_year')
                ->where('id_usecase', $idUsecase)
                ->where('tahun', $tahun)
                ->orderBy('id_bulan', 'asc')
                ->pluck('bulan');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan bulan periode inflasi.');
        }
    }

    public function getMapInflasi($idUsecase, $params){
        try {
            $db = DB::table('mart_poda_eko_inflasi_ihk_map')
                ->select('jenis', 'city', 'lat', 'lon', 'merged_data as data')
                ->where('id_usecase', $idUsecase)
                ->where('tahun', $params['tahun'])
                ->where('bulan', $params['bulan'])
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan map inflasi.');
        }
    }

    public function getDualChartInflasi($idUsecase, $params){
        try {
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
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan dual-chart inflasi.');
        }
    }

    public function getDetailInflasi($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_eko_inflasi_ihk_detail')
                ->selectRaw("kab_kota as category, CONCAT(bulan, ' ', tahun) as category2, jenis as `column`, merge_data as data")
                ->where('id_usecase', $idUsecase)
                ->where('tahun', $tahun)
                ->orderBy('id_bulan', 'desc', 'jenis', 'asc')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan detail inflasi.');
        }
    }
    // End Inflasi dan IHK

    // Start PDRB
    public function getTahunPDRB($idUsecase){
        try {
            $db = DB::table('mart_poda_eko_pdrb_filter_year')->distinct()
                ->where('id_usecase', $idUsecase)
                ->orderBy('tahun', 'desc')
                ->pluck('tahun');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan tahun PDRB.');
        }
    }

    public function getKategoriPDRB($idUsecase){
        try {
            $db = DB::table('mart_poda_eko_pdrb_filter_kategori')->distinct()
                ->where('id_usecase', $idUsecase)
                ->pluck('name');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan kategori PDRB.');
        }
    }

    public function getSektorPDRB($idUsecase){
        try {
            $db = DB::table('mart_poda_eko_pdrb_filter_sektor')->distinct()
                ->where('id_usecase', $idUsecase)
                ->pluck('name');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan sektor PDRB.');
        }
    }

    public function getCardPDRB($idUsecase, $params){
        try {
            $db = DB::table('mart_poda_eko_pdrb_card')
                ->where('id_usecase', $idUsecase)
                ->where('tahun', $params['tahun'])
                ->where('filter', $params['filter'])
                ->where('jenis', $params['jenis'])
                ->where('satuan', '=', 'Tahunan')
                ->sum('datacontent');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan card PDRB.');
        }
    }

    public function getBarPDRB($idUsecase, $params){
        try {
            $db = DB::table('mart_poda_eko_pdrb_bar_chart')
                ->select('chart_categories', 'chart_series as data')
                ->where('id_usecase', $idUsecase)
                ->where('tahun', $params['tahun'])
                ->where('filter', $params['filter'])
                ->where('jenis', $params['jenis'])
                ->whereIn('satuan', ['Tahunan', 'Tahun'])
                ->orderBy('data', 'desc')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan bar PDRB.');
        }
    }

    public function checkAreaPDRB($idUsecase){
        try {
            $db = DB::table('mart_poda_eko_pdrb_area_chart')->distinct()
                ->select('chart_categories')
                ->where('id_usecase', $idUsecase)
                ->first();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal check area PDRB.');
        }
    }

    public function getAreaPDRB($idUsecase, $params){
        try {
            $category = $this->checkAreaPDRB($idUsecase);

            $db = DB::table('mart_poda_eko_pdrb_area_chart');

            if($category->chart_categories == 'Tahun'){
                $db = $db->select('tahun as category', 'chart_series as data')
                    ->whereBetween('tahun', [$params['tahun'] - 4, $params['tahun']]);
            } else {
                $db = $db->select('chart_categories as category', 'chart_series as data')
                    ->where('tahun', $params['tahun']);
            }

            $db = $db->where('id_usecase', $idUsecase)
                ->where('filter', $params['filter'])
                ->where('jenis', $params['jenis'])
                ->where('name', 'like', '%' . $params['sektor'] . '%')
                ->get();

            return [$db, $category->chart_categories];
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan area PDRB.');
        }
    }

    public function getDetailPDRB($idUsecase, $params){
        try {
            $db = DB::table('mart_poda_eko_pdrb_detail')
                ->select('sektor as category', 'satuan as column', 'value as data')
                ->where('id_usecase', $idUsecase)
                ->where('filter', $params['filter'])
                ->where('jenis', $params['jenis'])
                ->where('tahun', $params['tahun'])
                ->orderBy('value', 'desc')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan detail PDRB.');
        }
    }
    // End PDRB

    // Start Pariwisata
    public function getIndikatorPariwisata($idUsecase){
        try {
            $db = DB::table('mart_poda_eko_pariwisata_filter_indikator')
                ->where('id_usecase', $idUsecase)
                ->orderBy('id_jenis')
                ->pluck('name');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan indikator pariwisata.');
        }
    }

    public function getNamaDaerahPariwisataDTW($idUsecase){
        try {
            $db = DB::table('mart_poda_eko_pariwisata_dayatarik_filter_kabkot')->distinct()
                ->where('id_usecase', $idUsecase)
                ->pluck('city');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan nama pariwisata DTW.');
        }
    }

    public function getPeriodePariwisataDTW($idUsecase){
        try {
            $db = DB::table('mart_poda_eko_pariwisata_dayatarik_filter_tahun')
                ->selectRaw('min(tahun) as minYear, max(tahun) as maxYear')
                ->where('id_usecase', $idUsecase)
                ->first();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan periode pariwisata DTW.');
        }
    }

    public function getTahunPariwisataDTW($idUsecase){
        try {
            $db = DB::table('mart_poda_eko_pariwisata_dayatarik_filter_map')->distinct()
                ->where('id_usecase', $idUsecase)
                ->orderBy('tahun', 'desc')
                ->pluck('tahun');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan tahun pariwisata DTW.');
        }
    }

    public function getMapPariwisataDTW($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_eko_pariwisata_dayatarik_map')
                ->select('city', 'lat', 'lon', 'daya_tarik_wisata as data')
                ->where('id_usecase', $idUsecase)
                ->where('tahun', $tahun)
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan map pariwisata DTW.');
        }
    }

    public function getLinePariwisataDTW($idUsecase, $params){
        try {
            $periode = explode('-', $params['periode']);
            $startYear = $periode[0];
            $endYear = $periode[1];

            $db = DB::table('mart_poda_eko_pariwisata_dayatarik_line_chart')
                ->select('tahun as category', 'value as data')
                ->where('id_usecase', $idUsecase)
                ->where('city', $params['daerah'])
                ->whereBetween('tahun', [$startYear, $endYear])
                ->orderBy('tahun', 'desc')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan line pariwisata DTW.');
        }
    }

    public function getDetailPariwisataDTW($idUsecase, $periode){
        try {
            $tahun = explode('-', $periode['periode']);

            $startYear = $tahun[0];
            $endYear = $tahun[1];

            $db = DB::table('mart_poda_eko_pariwisata_dayatarik_detail')
                ->select('kab_kota as category', 'tahun as column', 'value as data')
                ->where('id_usecase', $idUsecase)
                ->whereBetween('tahun', [$startYear, $endYear])
                ->orderBy('tahun', 'asc')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan detail pariwisata DTW.');
        }
    }

    public function getPeriodePariwisataHotel($idUsecase){
        try {
            $db = DB::table('mart_poda_eko_pariwisata_hotel_filter_tahun')
                ->selectRaw('min(tahun) as minYear, max(tahun) as maxYear')
                ->where('id_usecase', $idUsecase)
                ->first();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan periode pariwisata hotel.');
        }
    }

    public function getTahunPariwisataHotel($idUsecase){
        try {
            $db = DB::table('mart_poda_eko_pariwisata_hotel_filter_tahun')
                ->where('id_usecase', $idUsecase)
                ->orderBy('tahun', 'desc')
                ->pluck('tahun');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan tahun pariwisata hotel.');
        }
    }

    public function getMapPariwisataHotel($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_eko_pariwisata_hotel_map')
                ->select('vervar as city', 'turvar as jenis', 'lat', 'lon', 'value as data')
                ->where('id_usecase', $idUsecase)
                ->where('tahun', $tahun)
                ->orderBy('tahun', 'desc')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan map pariwisata hotel.');
        }
    }

    public function getBarPariwisataHotel($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_eko_pariwisata_hotel_column_chart')
                ->selectRaw('jenis_hotel as chart_categories, sum(value) as data')
                ->where('id_usecase', $idUsecase)
                ->where('tahun', $tahun)
                ->groupBy('jenis_hotel')
                ->orderBy('jenis_hotel')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan bar pariwisata hotel.');
        }
    }

    public function getLinePariwisataHotel($idUsecase, $periode){
        try {
            $tahun = explode('-', $periode['periode']);

            $startYear = $tahun[0];
            $endYear = $tahun[1];

            $db = DB::table('mart_poda_eko_pariwisata_hotel_line_chart')
                ->selectRaw('tahun as category, turvar as jenis, sum(value) as data')
                ->where('id_usecase', $idUsecase)
                ->whereBetween('tahun', [$startYear, $endYear])
                ->groupBy('tahun', 'turvar')
                ->orderBy('tahun', 'asc', 'turvar', 'asc')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan line pariwisata hotel.');
        }
    }

    public function getDetailPariwisataHotel($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_eko_pariwisata_hotel_detail_table')
                ->select('kab_kota as category', 'turvar as column', 'value as data')
                ->where('id_usecase', $idUsecase)
                ->where('tahun', $tahun)
                ->orderBy('tahun', 'asc')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan detail pariwisata hotel.');
        }
    }

    public function getPeriodePariwisataWisatawan($idUsecase){
        try {
            $db = DB::table('mart_poda_eko_pariwisata_wisatawan_card')
                ->selectRaw('min(tahun) as minYear, max(tahun) as maxYear')
                ->where('id_usecase', $idUsecase)
                ->first();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan periode pariwisata wisatawan.');
        }
    }

    public function getCardPariwisataWisatawan($idUsecase, $periode){
        try {
            $tahun = explode('-', $periode['periode']);

            $startYear = $tahun[0];
            $endYear = $tahun[1];

            $db = DB::table('mart_poda_eko_pariwisata_wisatawan_card')
                ->selectRaw('name, sum(value) as data')
                ->where('id_usecase', $idUsecase)
                ->whereBetween('tahun', [$startYear, $endYear])
                ->groupBy('name')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan card pariwisata wisatawan.');
        }
    }

    public function getLinePariwisataWisatawan($idUsecase, $periode){
        try {
            $tahun = explode('-', $periode['periode']);

            $startYear = $tahun[0];
            $endYear = $tahun[1];

            $db = DB::table('mart_poda_eko_pariwisata_wisatawan_line_series')
                ->selectRaw('tahun as category, turvar as jenis, sum(value) as data')
                ->where('id_usecase', $idUsecase)
                ->whereBetween('tahun', [$startYear, $endYear])
                ->groupBy('tahun', 'turvar')
                ->orderBy('tahun', 'asc', 'turvar', 'asc')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan line pariwisata wisatawan.');
        }
    }

    public function getDetailPariwisataWisatawan($idUsecase, $periode){
        try {
            $tahun = explode('-', $periode['periode']);

            $startYear = $tahun[0];
            $endYear = $tahun[1];

            $db = DB::table('mart_poda_eko_pariwisata_wisatawan_detail')
                ->selectRaw('tahun as category, turvar as `column`, value as data')
                ->where('id_usecase', $idUsecase)
                ->whereBetween('tahun', [$startYear, $endYear])
                ->orderBy('tahun', 'asc', 'turvar', 'asc');

            $db_total = DB::table('mart_poda_eko_pariwisata_wisatawan_detail')
                ->selectRaw("tahun as category, 'Total' as `column`, sum(value) as data")
                ->where('id_usecase', $idUsecase)
                ->whereBetween('tahun', [$startYear, $endYear])
                ->groupBy('tahun', 'column');

            $union = $db->union($db_total)->get();

            return $union;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan detail pariwisata wisatawan.');
        }
    }

    public function getTahunPariwisataTPK($idUsecase){
        try {
            $db = DB::table('mart_poda_eko_pariwisata_tpk_filter_tahun')
                ->where('id_usecase', $idUsecase)
                ->orderBy('tahun', 'desc')
                ->pluck('tahun');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan tahun pariwisata TPK.');
        }
    }

    public function getBulanPariwisataTPK($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_eko_pariwisata_tpk_detail')
                ->select('bulan','id_bulan')->distinct()
                ->where('id_usecase', $idUsecase)
                ->where('tahun', $tahun)
                ->orderBy('id_bulan', 'asc')
                ->pluck('bulan');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan bulan pariwisata TPK.');
        }
    }

    public function getCardPariwisataTPK($idUsecase, $params){
        try {
            $db = DB::table('mart_poda_eko_pariwisata_tpk_detail')
                ->selectRaw('AVG(value) as data')
                ->where('id_usecase', $idUsecase)
                ->where('tahun', $params['tahun'])
                ->where('bulan', $params['bulan'])
                ->whereNot('hotel_bintang', 'Total')
                ->orderBy('id_bulan', 'asc')
                ->first();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan card pariwisata TPK.');
        }
    }

    public function getLinePariwisataTPK($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_eko_pariwisata_tpk_detail')
                ->selectRaw('id_bulan, bulan as category, hotel_bintang as jenis, sum(value) as data')
                ->where('id_usecase', $idUsecase)
                ->where('tahun', $tahun)
                ->whereNot('hotel_bintang', 'Total')
                ->groupBy('id_bulan', 'bulan', 'hotel_bintang')
                ->orderBy('id_bulan', 'asc', 'hotel_bintang', 'asc')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan line pariwisata TPK.');
        }
    }

    public function getDetailPariwisataTPK($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_eko_pariwisata_tpk_detail')
                ->select('hotel_bintang as category', 'bulan as column', 'value as data')
                ->where('id_usecase', $idUsecase)
                ->where('tahun', $tahun)
                ->orderBy('id_bulan', 'asc', 'hotel_bintang', 'asc')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan detail pariwisata TPK.');
        }
    }

    public function getPeriodePariwisataResto($idUsecase){
        try {
            $db = DB::table('mart_poda_eko_pariwisata_restoran_filter_tahun')
                ->selectRaw('min(tahun) as minYear, max(tahun) as maxYear')
                ->where('id_usecase', $idUsecase)
                ->first();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan detail pariwisata restoran.');
        }
    }

    public function getTahunPariwisataResto($idUsecase){
        try {
            $db = DB::table('mart_poda_eko_pariwisata_restoran_filter_tahun')
                ->where('id_usecase', $idUsecase)
                ->orderBy('tahun', 'desc')
                ->pluck('tahun');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan tahun pariwisata restoran.');
        }
    }

    public function getNamaDaerahPariwisataResto($idUsecase){
        try {
            $db = DB::table('mart_poda_eko_pariwisata_restoran_filter_kabkot')->distinct()
                ->where('id_usecase', $idUsecase)
                ->pluck('city');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan nama pariwisata restoran.');
        }
    }

    public function getMapPariwisataResto($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_eko_pariwisata_restoran_map')
                ->select('city', 'lat', 'lon', 'value as data')
                ->where('id_usecase', $idUsecase)
                ->where('tahun', $tahun)
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan map pariwisata restoran.');
        }
    }

    public function getLinePariwisataResto($idUsecase, $params){
        try {
            $periode = explode('-', $params['periode']);
            $startYear = $periode[0];
            $endYear = $periode[1];

            $db = DB::table('mart_poda_eko_pariwisata_restoran_line_chart')
                ->select('tahun as category', 'value as data')
                ->where('id_usecase', $idUsecase)
                ->where('vervar', $params['daerah'])
                ->whereBetween('tahun', [$startYear, $endYear])
                ->orderBy('tahun', 'desc')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan line pariwisata restoran.');
        }
    }

    public function getDetailPariwisataResto($idUsecase, $periode){
        try {
            $tahun = explode('-', $periode['periode']);

            $startYear = $tahun[0];
            $endYear = $tahun[1];

            $db = DB::table('mart_poda_eko_pariwisata_restoran_detail')
                ->select('kab_kota as category', 'tahun as column', 'value as data')
                ->where('id_usecase', $idUsecase)
                ->whereBetween('tahun', [$startYear, $endYear])
                ->orderBy('tahun', 'asc')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan detail pariwisata restoran.');
        }
    }
}
