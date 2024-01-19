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
            ->select('city as chart_categories', 'datacontent as data')
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

    // Start Laju Pertumbuhan
    public function getPeriodeLaju($idUsecase){
        $db = DB::table('mart_poda_social_lpp_filter_periode')
            ->select('startYear', 'endYear', 'minYear', 'maxYear')
            ->where('id_usecase', $idUsecase)
            ->first();

        return $db;
    }

    public function getNamaDaerahLaju($idUsecase){
        $db = DB::table('mart_poda_social_lpp_filter_kabkot')->distinct()
            ->where('id_usecase', $idUsecase)
            ->orderBy('city', 'asc')
            ->pluck('city');

        return $db;
    }

    public function getDualAxesLaju($idUsecase, $params){
        $year = explode('-', $params['periode']);

        $startYear = $year[0];
        $endYear = $year[1];

        $db = DB::table('mart_poda_social_lpp_chart_line_column')
            ->select('city', 'jenis', 'tahun', 'merged_data as data')
            ->where('id_usecase', $idUsecase)
            ->whereBetween('tahun', [$startYear, $endYear])
            ->where('city', $params['nama_daerah'])
            ->orderBy('city', 'asc', 'jenis', 'asc', 'tahun', 'desc')
            ->get();

        return $db;
    }
    // End Laju Pertumbuhan

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
            ->select('city', 'lat', 'lon', 'rasio')
            ->where('tahun', $tahun['tahun'])
            ->where('id_usecase', $idUsecase)
            ->get();

        return $db;
    }

    public function getBarRasio($idUsecase, $tahun){
        $db = DB::table('mart_poda_social_rasio_jk_bar_chart')
            ->select('chart_categories', 'data')
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
        $db = DB::table('mart_poda_social_kepadatan_penduduk_map_leaflet')
            ->select('city', 'lat', 'lon', 'nilai')
            ->where('tahun', $tahun['tahun'])
            ->where('id_usecase', $idUsecase)
            ->get();

        return $db;
    }

    public function getBarKepadatan($idUsecase, $tahun){
        $db = DB::table('mart_poda_social_kepadatan_penduduk_bar_chart')
            ->select('chart_categories', 'data')
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

    public function getAreaIPM($idUsecase, $params){
        $years = explode('-', $params['periode']);

        $startYear = $years[0];
        $endYear = $years[1];

        $db = DB::table('mart_poda_social_ipm_area_chart')
            ->select('city','tahun as category', 'data')
            ->where('id_usecase', $idUsecase)
            ->where('city', $params['nama_daerah'])
            ->where('filter', $params['filter'])
            ->whereBetween('tahun', [$startYear, $endYear])
            ->get();

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
    public function getIndikatorKemiskinan($idUsecase){
        $db = DB::table('mart_poda_social_kemiskinan_filter_indikator')
            ->distinct()->where('id_usecase', $idUsecase)
            ->pluck('nama');

        return $db;
    }

    public function getTahunKemiskinan($idUsecase){
        $db = DB::table('mart_poda_social_kemiskinan_filter_tahun')
            ->distinct()->where('id_usecase', $idUsecase)
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return $db;
    }

    public function getDaerahKemiskinan($idUsecase){
        $db = DB::table('mart_poda_social_kemiskinan_filterkab')
            ->distinct()->where('id_usecase', $idUsecase)
            ->pluck('city');

        return $db;
    }

    public function getPeriodeKemiskinan($idUsecase, $filter){
        $db = DB::table('mart_poda_social_kemiskinan_filter_periode')
            ->select('startYear', 'endYear', 'minYear', 'maxYear')
            ->where('id_usecase', $idUsecase)
            ->where('filter', $filter['filter'])
            ->first();

        return $db;
    }

    public function getMapKemiskinan($idUsecase, $params){
        $db = DB::table('mart_poda_social_kemiskinan_map_leaflet')
            ->select('city', 'value as data', 'lat', 'lon')
            ->where('tahun', $params['tahun'])
            ->where('filter', $params['filter'])
            ->where('id_usecase', $idUsecase)
            ->get();

        return $db;
    }

    public function getAreaKemiskinan($idUsecase, $params){
        $years = explode('-', $params['periode']);

        $startYear = $years[0];
        $endYear = $years[1];

        $db = DB::table('mart_poda_social_kemiskinan_area_chart')
            ->select('kabupaten_kota as city','category', 'data')
            ->where('id_usecase', $idUsecase)
            ->where('kabupaten_kota', $params['nama_daerah'])
            ->where('filter', $params['filter'])
            ->whereBetween('tahun', [$startYear, $endYear])
            ->get();

        return $db;
    }
    // End Kemiskinan

    // Start Pekerjaan dan Angkatan Kerja
    public function getIndikatorPekerjaan($idUsecase){
        $db = DB::table('mart_poda_social_pekerjaan_filter_indikator')
            ->distinct()->where('id_usecase', $idUsecase)
            ->pluck('indikator');

        return $db;
    }

    public function getTahunPekerjaan($idUsecase){
        $db = DB::table('mart_poda_social_pekerjaan_filter_year_leaflet')
            ->distinct()->where('id_usecase', $idUsecase)
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return $db;
    }

    public function getTahunJenisPekerjaan($idUsecase){
        $db = DB::table('master_poda_social_pekerjaan_type')
            ->distinct()->where('id_usecase', $idUsecase)
            ->orderBy('year', 'desc')
            ->pluck('year as tahun');

        return $db;
    }

    public function getPeriodePekerjaan($idUsecase, $filter){
        $db = DB::table('mart_poda_social_pekerjaan_periode')
            ->select('startYear', 'endYear', 'minYear', 'maxYear')
            ->where('id_usecase', $idUsecase)
            ->where('indikator', $filter['filter'])
            ->first();

        return $db;
    }

    public function getBarJenisPekerjaan($idUsecase, $tahun){
        $db = DB::table('master_poda_social_pekerjaan_type')
            ->selectRaw("jenis as chart_categories, sum(datacontent) as data")
            ->where('year', $tahun['tahun'])
            ->where('id_usecase', $idUsecase)
            ->groupBy('jenis')
            ->get();

        return $db;
    }

    public function getMapPekerjaan($idUsecase, $params){
        $db = DB::table('mart_poda_social_pekerjaan_map_leaflet')
            ->select('city', 'data', 'lat', 'lon')
            ->where('tahun', $params['tahun'])
            ->where('id_usecase', $idUsecase)
            ->where('indikator', $params['filter'])
            ->get();

        return $db;
    }

    public function getLinePekerjaan($idUsecase, $params){
        $years = explode('-', $params['periode']);

        $startYear = $years[0];
        $endYear = $years[1];

        $db = DB::table('mart_poda_social_pekerjaan_line_chart')
            ->select('tahun as category', 'data')
            ->where('id_usecase', $idUsecase)
            ->where('indikator', $params['filter'])
            ->whereBetween('tahun', [$startYear, $endYear])
            ->get();

        return $db;
    }
    // End Pekerjaan dan Angkatan Kerja

    // Start Pendidikan
    public function getTahunAjaranPendidikan($idUsecase){
        $db = DB::table('mart_poda_social_pendidikan_filter_tahun_ajaran')
            ->distinct()->where('id_usecase', $idUsecase)
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return $db;
    }

    public function getTahunPendidikan($idUsecase){
        $db = DB::table('mart_poda_social_pendidikan_filter_tahun')
            ->distinct()->where('id_usecase', $idUsecase)
            ->orderBy('years', 'desc')
            ->pluck('years');

        return $db;
    }

    public function getJenjangPendidikan($idUsecase){
        $db = DB::table('mart_poda_social_pendidikan_filter_jenjang')
            ->distinct()->where('id_usecase', $idUsecase)
            ->pluck('nama');

        return $db;
    }

    public function getIndikatorPendidikan($idUsecase){
        $db = DB::table('mart_poda_social_pendidikan_filter_indikator')
            ->distinct()->where('id_usecase', $idUsecase)
            ->pluck('nama');

        return $db;
    }

    public function getBarPendidikan($idUsecase, $params){
        $db = DB::table('mart_poda_social_pendidikan_bar_chart')
            ->select('city as chart_categories', 'data')
            ->where('tahun', $params['tahun'])
            ->where('sekolah', $params['jenjang'])
            ->where('jenis', $params['indikator'])
            ->where('id_usecase', $idUsecase)
            ->get();

        return $db;
    }

    public function getBarJenjangPendidikan($idUsecase, $tahun){
        $db = DB::table('mart_poda_social_pendidikan_jenjang_bar_chart')
            ->select('sekolah as chart_categories', 'data')
            ->where('tahun', $tahun)
            ->where('id_usecase', $idUsecase)
            ->get();

        return $db;
    }

    public function getMapPendidikan($idUsecase, $params){
        $db = DB::table('mart_poda_social_pendidikan_map_leaflet')
            ->select('city', 'jenis', 'lat', 'lon', 'value')
            ->where('tahun', $params['tahun'])
            ->where('sekolah', $params['jenjang'])
            ->where('id_usecase', $idUsecase)
            ->get();

        return $db;
    }
    // End Pendidikan

    // Start Kesehatan
    public function getTahunKesehatan($idUsecase){
        $db = DB::table('mart_poda_social_kesehatan_filter_tahun')
            ->distinct()->where('id_usecase', $idUsecase)
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return $db;
    }

    public function getIndikatorKesehatan($idUsecase){
        $db = DB::table('mart_poda_social_kesehatan_filter_faskes')
            ->distinct()->where('id_usecase', $idUsecase)
            ->orderBy('jenis', 'desc')
            ->pluck('jenis');

        return $db;
    }

    public function getPeriodeKesehatan($idUsecase){
        $db = DB::table('mart_poda_social_kesehatan_filter_periode')
            ->select('startYear', 'endYear', 'minYear', 'maxYear')
            ->where('id_usecase', $idUsecase)
            ->first();

        return $db;
    }

    public function getBarKesehatan($idUsecase, $params){
        $db = DB::table('mart_poda_social_kesehatan_bar_chart')
            ->select('chart_categories','chart_data as data')
            ->where('id_usecase', $idUsecase)
            ->where('tahun', $params['tahun'])
            ->where('widget_title', $params['filter'])
            ->get();

        return $db;
    }

    public function getBarColumnKesehatan($idUsecase, $periode){
        $years = explode('-', $periode['periode']);

        $startYear = $years[0];
        $endYear = $years[1];

        $db = DB::table('mart_poda_social_kesehatan_column_chart')
            ->select('jenis','tahun','data')
            ->where('id_usecase', $idUsecase)
            ->whereBetween('tahun', [$startYear, $endYear])
            ->get();

        return $db;
    }

    public function getMapKesehatan($idUsecase, $tahun){
        $db = DB::table('mart_poda_social_kesehatan_map_leaflet')
            ->select('city','lat','lon','jenis', 'data')
            ->where('id_usecase', $idUsecase)
            ->where('tahun', $tahun)
            ->get();

        return $db;
    }
    // End Kesehatan
}
