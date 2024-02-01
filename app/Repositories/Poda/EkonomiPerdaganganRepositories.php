<?php

namespace App\Repositories\Poda;

use Exception;
use Illuminate\Support\Facades\DB;

class EkonomiPerdaganganRepositories {

    // Start PAD
    public function getAreaPad($idUsecase){
        $db = DB::table('mart_poda_eko_pad')
            ->select('category', 'total as data')
            ->where('id_usecase', $idUsecase)
            ->get();

        return $db;
    }

    public function getDetailPad($idUsecase){
        $db = DB::table('mart_poda_eko_pad_detail')
            ->select('city as category', 'tahun as column', 'pad as data')
            ->where('id_usecase', $idUsecase)
            ->get();

        return $db;
    }
    // End PAD

    // Start Trend-Perdagangan
    public function getPeriodeTrendPerdagangan($idUsecase){
        $db = DB::table('mart_poda_eko_tren_perdagangan_filter_year')
            ->select('minYear', 'maxYear')
            ->where('id_usecase', $idUsecase)
            ->first();

        return $db;
    }

    public function getAreaTrendPerdagangan($idUsecase, $periode){
        $tahun = explode('-', $periode['periode']);

        $startYear = $tahun[0];
        $endYear = $tahun[1];

        $db = DB::table('mart_poda_eko_tren_perdagangan_line_chart')
            ->select('category', 'total as data')
            ->where('id_usecase', $idUsecase)
            ->whereBetween('category', [$startYear, $endYear])
            ->get();

        return $db;
    }

    public function getDetailTrendPerdagangan($idUsecase, $periode){
        $tahun = explode('-', $periode['periode']);

        $startYear = $tahun[0];
        $endYear = $tahun[1];

        $db = DB::table('mart_poda_eko_tren_perdagangan_detail')
            ->select('city as category', 'tahun as column', 'total as data')
            ->where('id_usecase', $idUsecase)
            ->whereBetween('tahun', [$startYear, $endYear])
            ->get();

        return $db;
    }
    // End Trend-Perdagangan

    // Start Top Komoditas
    public function getTahunKomoditas($idUsecase){
        $db = DB::table('mart_poda_eko_komoditas_perdagangan')->distinct()
            ->where('id_usecase', $idUsecase)
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return $db;
    }

    public function getBarKomoditas($idUsecase, $tahun){
        $db = DB::table('mart_poda_eko_komoditas_perdagangan')
            ->select('chart_categories', 'nilai as data')
            ->where('id_usecase', $idUsecase)
            ->where('tahun', $tahun)
            ->orderBy('data', 'desc')
            ->limit(10)
            ->get();

        return $db;
    }

    public function getDetailKomoditas($idUsecase, $tahun){
        $db = DB::table('mart_poda_eko_komoditas_perdagangan')
            ->selectRaw("chart_categories as category, 'Nominal' as `column`, nilai as data")
            ->where('id_usecase', $idUsecase)
            ->where('tahun', $tahun)
            ->orderBy('data', 'desc')
            ->limit(10)
            ->get();

        return $db;
    }
    // End Top Komoditas

    // Start PAD KabKot
    public function getTahunPadKabKota($idUsecase){
        $db = DB::table('mart_poda_eko_pad')->distinct()
            ->where('id_usecase', $idUsecase)
            ->orderBy('category', 'desc')
            ->pluck('category');

        return $db;
    }

    public function getBarPadKabKota($idUsecase, $tahun){
        $db = DB::table('mart_poda_eko_pad_detail')
            ->select('city as chart_categories', 'pad as data')
            ->where('id_usecase', $idUsecase)
            ->where('tahun', $tahun)
            ->orderBy('data', 'desc')
            ->limit(10)
            ->get();

        return $db;
    }

    public function getDetailPadKabKota($idUsecase, $tahun){
        $startYear = $tahun['tahun'] - 2;

        $db = DB::table('mart_poda_eko_pad_detail')
            ->selectRaw("city as category, tahun as `column`, pad as data")
            ->where('id_usecase', $idUsecase)
            ->whereBetween('tahun', [$startYear, $tahun['tahun']])
            ->orderBy('data', 'desc')
            ->get();

        return $db;
    }
    // End PAD KabKot

    // Start Inflasi dan IHK
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

    public function getDetailInflasi($idUsecase, $tahun){
        $startYear = $tahun['tahun'] - 2;

        $db = DB::table('mart_poda_eko_pad_detail')
            ->selectRaw("city as category, tahun as `column`, pad as data")
            ->where('id_usecase', $idUsecase)
            ->whereBetween('tahun', [$startYear, $tahun['tahun']])
            ->orderBy('data', 'desc')
            ->get();

        return $db;
    }
    // End Inflasi dan IHK

    // Start PDRB
    public function getTahunPDRB($idUsecase){
        $db = DB::table('mart_poda_eko_pdrb_filter_year')->distinct()
            ->where('id_usecase', $idUsecase)
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return $db;
    }

    public function getKategoriPDRB($idUsecase){
        $db = DB::table('mart_poda_eko_pdrb_filter_kategori')->distinct()
            ->where('id_usecase', $idUsecase)
            ->pluck('name');

        return $db;
    }

    public function getSektorPDRB($idUsecase){
        $db = DB::table('mart_poda_eko_pdrb_filter_sektor')->distinct()
            ->where('id_usecase', $idUsecase)
            ->pluck('name');

        return $db;
    }

    public function getCardPDRB($idUsecase, $params){
        $db = DB::table('mart_poda_eko_pdrb_card')
            ->where('id_usecase', $idUsecase)
            ->where('tahun', $params['tahun'])
            ->where('filter', $params['filter'])
            ->where('jenis', $params['jenis'])
            ->where('satuan', '=', 'Tahunan')
            ->sum('datacontent');

        return $db;
    }

    public function getBarPDRB($idUsecase, $params){
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
    }

    public function checkAreaPDRB($idUsecase){
        $db = DB::table('mart_poda_eko_pdrb_area_chart')->distinct()
            ->select('chart_categories')
            ->where('id_usecase', $idUsecase)
            ->first();

        return $db;
    }

    public function getAreaPDRB($idUsecase, $params){
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
    }
    // End PDRB

    // Start Pariwisata
    public function getIndikatorPariwisata($idUsecase){
        $db = DB::table('mart_poda_eko_pariwisata_filter_indikator')
            ->where('id_usecase', $idUsecase)
            ->orderBy('id_jenis')
            ->pluck('name');

        return $db;
    }

    public function getNamaDaerahPariwisataDTW($idUsecase){
        $db = DB::table('mart_poda_eko_pariwisata_dayatarik_filter_kabkot')->distinct()
            ->where('id_usecase', $idUsecase)
            ->pluck('city');

        return $db;
    }

    public function getPeriodePariwisataDTW($idUsecase){
        $db = DB::table('mart_poda_eko_pariwisata_dayatarik_filter_tahun')
            ->selectRaw('min(tahun) as minYear, max(tahun) as maxYear')
            ->where('id_usecase', $idUsecase)
            ->first();

        return $db;
    }

    public function getTahunPariwisataDTW($idUsecase){
        $db = DB::table('mart_poda_eko_pariwisata_dayatarik_filter_map')->distinct()
            ->where('id_usecase', $idUsecase)
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return $db;
    }

    public function getMapPariwisataDTW($idUsecase, $tahun){
        $db = DB::table('mart_poda_eko_pariwisata_dayatarik_map')
            ->select('city', 'lat', 'lon', 'daya_tarik_wisata as data')
            ->where('id_usecase', $idUsecase)
            ->where('tahun', $tahun)
            ->get();

        return $db;
    }

    public function getLinePariwisataDTW($idUsecase, $params){
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
    }

    public function getPeriodePariwisataHotel($idUsecase){
        $db = DB::table('mart_poda_eko_pariwisata_hotel_filter_tahun')
            ->selectRaw('min(tahun) as minYear, max(tahun) as maxYear')
            ->where('id_usecase', $idUsecase)
            ->first();

        return $db;
    }

    public function getTahunPariwisataHotel($idUsecase){
        $db = DB::table('mart_poda_eko_pariwisata_hotel_filter_tahun')
            ->where('id_usecase', $idUsecase)
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return $db;
    }

    public function getMapPariwisataHotel($idUsecase, $tahun){
        $db = DB::table('mart_poda_eko_pariwisata_hotel_map')
            ->select('vervar as city', 'turvar as jenis', 'lat', 'lon', 'value as data')
            ->where('id_usecase', $idUsecase)
            ->where('tahun', $tahun)
            ->orderBy('tahun', 'desc')
            ->get();

        return $db;
    }

    public function getBarPariwisataHotel($idUsecase, $tahun){
        $db = DB::table('mart_poda_eko_pariwisata_hotel_column_chart')
            ->selectRaw('jenis_hotel as chart_categories, sum(value) as data')
            ->where('id_usecase', $idUsecase)
            ->where('tahun', $tahun)
            ->groupBy('jenis_hotel')
            ->orderBy('jenis_hotel')
            ->get();

        return $db;
    }

    public function getLinePariwisataHotel($idUsecase, $periode){
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
    }

    public function getPeriodePariwisataWisatawan($idUsecase){
        $db = DB::table('mart_poda_eko_pariwisata_wisatawan_card')
            ->selectRaw('min(tahun) as minYear, max(tahun) as maxYear')
            ->where('id_usecase', $idUsecase)
            ->first();

        return $db;
    }

    public function getCardPariwisataWisatawan($idUsecase, $periode){
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
    }

    public function getLinePariwisataWisatawan($idUsecase, $periode){
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
    }

    public function getTahunPariwisataTPK($idUsecase){
        $db = DB::table('mart_poda_eko_pariwisata_tpk_filter_tahun')
            ->where('id_usecase', $idUsecase)
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return $db;
    }

    public function getBulanPariwisataTPK($idUsecase, $tahun){
        $db = DB::table('mart_poda_eko_pariwisata_tpk_detail')
            ->select('bulan','id_bulan')->distinct()
            ->where('id_usecase', $idUsecase)
            ->where('tahun', $tahun)
            ->orderBy('id_bulan', 'asc')
            ->pluck('bulan');

        return $db;
    }

    public function getCardPariwisataTPK($idUsecase, $params){
        $db = DB::table('mart_poda_eko_pariwisata_tpk_detail')
            ->selectRaw('AVG(value) as data')
            ->where('id_usecase', $idUsecase)
            ->where('tahun', $params['tahun'])
            ->where('bulan', $params['bulan'])
            ->whereNot('hotel_bintang', 'Total')
            ->orderBy('id_bulan', 'asc')
            ->first();

        return $db;
    }

    public function getLinePariwisataTPK($idUsecase, $tahun){
        $db = DB::table('mart_poda_eko_pariwisata_tpk_detail')
            ->selectRaw('id_bulan, bulan as category, hotel_bintang as jenis, sum(value) as data')
            ->where('id_usecase', $idUsecase)
            ->where('tahun', $tahun)
            ->whereNot('hotel_bintang', 'Total')
            ->groupBy('id_bulan', 'bulan', 'hotel_bintang')
            ->orderBy('id_bulan', 'asc', 'hotel_bintang', 'asc')
            ->get();

        return $db;
    }

    public function getPeriodePariwisataResto($idUsecase){
        $db = DB::table('mart_poda_eko_pariwisata_restoran_filter_tahun')
            ->selectRaw('min(tahun) as minYear, max(tahun) as maxYear')
            ->where('id_usecase', $idUsecase)
            ->first();

        return $db;
    }

    public function getTahunPariwisataResto($idUsecase){
        $db = DB::table('mart_poda_eko_pariwisata_restoran_filter_tahun')
            ->where('id_usecase', $idUsecase)
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return $db;
    }

    public function getNamaDaerahPariwisataResto($idUsecase){
        $db = DB::table('mart_poda_eko_pariwisata_restoran_filter_kabkot')->distinct()
            ->where('id_usecase', $idUsecase)
            ->pluck('city');

        return $db;
    }

    public function getMapPariwisataResto($idUsecase, $tahun){
        $db = DB::table('mart_poda_eko_pariwisata_restoran_map')
            ->select('city', 'lat', 'lon', 'value as data')
            ->where('id_usecase', $idUsecase)
            ->where('tahun', $tahun)
            ->get();

        return $db;
    }

    public function getLinePariwisataResto($idUsecase, $params){
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
    }
}
