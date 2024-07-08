<?php

namespace App\Repositories\Poda;

use Exception;
use App\Exceptions\ErrorResponse;
use Illuminate\Support\Facades\DB;

class SosialKependudukanRepositories {

    public function getTahunJumlahPenduduk($idUsecase){
        try {
            $db = DB::table('mart_poda_social_kependudukan_filter_tahun')
                ->where('id_usecase', $idUsecase)
                ->orderBy('tahun', 'desc')
                ->pluck('tahun');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil tahun jumlah penduduk.');
        }
    }

    // Start Kependudukan
    public function getMapJumlahPenduduk($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_social_kependudukan_map_leaflet')
                ->select('city', 'Lakilaki', 'Perempuan', 'lat', 'lon')
                ->where('tahun', $tahun['tahun'])
                ->where('id_usecase', $idUsecase)
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil map jumlah penduduk.');
        }
    }

    public function getPieJumlahPenduduk($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_social_kependudukan_pie_chart')
                ->select('sumber_data', 'tahun', 'lakilaki', 'Perempuan', 'jumlah')
                ->where('tahun', $tahun['tahun'])
                ->where('id_usecase', $idUsecase)
                ->first();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil pie jumlah penduduk.');
        }
    }

    public function getBarJumlahPenduduk($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_social_kependudukan_bar_chart')
                ->select('city as chart_categories', 'datacontent as data')
                ->where('tahun', $tahun['tahun'])
                ->where('id_usecase', $idUsecase)
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil bar jumlah penduduk.');
        }
    }

    public function getDetailJumlahPenduduk($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_social_kependudukan_detail_merged')
                ->select('city as category', 'jenis as column', 'data')
                ->where('tahun', $tahun)
                ->where('id_usecase', $idUsecase)
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil detail jumlah penduduk.');
        }
    }
    // End Kependudukan

    // Start Rentang Usia
    public function getTahunRentangUsia($idUsecase){
        try {
            $db = DB::table('mart_poda_social_rentang_usia_filter_tahun')
                ->where('id_usecase', $idUsecase)
                ->orderBy('tahun', 'desc')
                ->pluck('tahun');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil tahun rentang usia.');
        }
    }

    public function getStackedBarRentangUsia($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_social_rentang_usia_bar_chart')
                ->select('name', 'chart_categories', 'data')
                ->where('id_usecase', $idUsecase)
                ->where('tahun', $tahun['tahun'])
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil stacked bar rentang usia.');
        }
    }

    public function getDetailRentangUsia($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_social_rentang_usia_detail_merged')
                ->select('kelompok_umur as category', 'jenis as column', 'data')
                ->where('tahun', $tahun)
                ->where('id_usecase', $idUsecase)
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil detail rentang usia.');
        }
    }
    // End Rentang Usia

    // Start Laju Pertumbuhan
    public function getPeriodeLaju($idUsecase){
        try {
            $db = DB::table('mart_poda_social_lpp_filter_periode')
                ->select('startYear', 'endYear', 'minYear', 'maxYear')
                ->where('id_usecase', $idUsecase)
                ->first();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil periode laju.');
        }
    }

    public function getNamaDaerahLaju($idUsecase){
        try {
            $db = DB::table('mart_poda_social_lpp_filter_kabkot')->distinct()
                ->where('id_usecase', $idUsecase)
                ->orderBy('city', 'asc')
                ->pluck('city');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil nama daerah laju.');
        }
    }

    public function getDualAxesLaju($idUsecase, $params){
        try {
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
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil dual axes laju.');
        }
    }

    public function getDetailLaju($idUsecase, $periode){
        try {
            $tahun = explode('-', $periode['periode']);

            $startYear = $tahun[0];
            $endYear = $tahun[1];

            $db = DB::table('mart_poda_social_lpp_chart_line_column')
                ->select('city as category', 'tahun as column', 'merged_data as data')
                ->whereBetween('tahun', [$startYear, $endYear])
                ->where('id_usecase', $idUsecase)
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil detail laju.');
        }
    }
    // End Laju Pertumbuhan

    // Start Rasio Jenis Kelamin
    public function getTahunRasio($idUsecase){
        try {
            $db = DB::table('mart_poda_social_rasio_jk_filter_tahun')
                ->distinct()->where('id_usecase', $idUsecase)
                ->orderBy('tahun', 'desc')
                ->pluck('tahun');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil tahun rasio.');
        }
    }

    public function getMapRasio($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_social_rasio_jk_map_leaflet')
                ->select('city', 'lat', 'lon', 'rasio')
                ->where('tahun', $tahun['tahun'])
                ->where('id_usecase', $idUsecase)
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil map rasio.');
        }
    }

    public function getBarRasio($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_social_rasio_jk_bar_chart')
                ->select('chart_categories', 'data')
                ->where('tahun', $tahun['tahun'])
                ->where('id_usecase', $idUsecase)
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil bar rasio.');
        }
    }

    public function getDetailRasio($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_social_rasio_jk_detail')
                ->select('kabupaten_kota as category', 'tahun as column', 'data')
                ->where('id_usecase', $idUsecase)
                ->where('tahun', $tahun)
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil detail rasio.');
        }
    }
    // End Rasio Jenis Kelamin

    // Start Kepadatan Penduduk
    public function getTahunKepadatan($idUsecase){
        try {
            $db = DB::table('mart_poda_social_kepadatan_penduduk_filter_tahun')
                ->distinct()->where('id_usecase', $idUsecase)
                ->orderBy('years', 'desc')
                ->pluck('years');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil tahun kepadatan.');
        }
    }

    public function getMapKepadatan($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_social_kepadatan_penduduk_map_leaflet')
                ->select('city', 'lat', 'lon', 'nilai')
                ->where('tahun', $tahun['tahun'])
                ->where('id_usecase', $idUsecase)
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil map kepadatan.');
        }
    }

    public function getBarKepadatan($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_social_kepadatan_penduduk_bar_chart')
                ->select('chart_categories', 'data')
                ->where('tahun', $tahun['tahun'])
                ->where('id_usecase', $idUsecase)
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil bar kepadatan.');
        }
    }

    public function getDetailKepadatan($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_social_kepadatan_penduduk_detail')
                ->select('kabupaten_kota as category', 'tahun as column', 'data')
                ->where('id_usecase', $idUsecase)
                ->where('tahun', $tahun)
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil detail kepadatan.');
        }
    }
    // End Kepadatan Penduduk

    // Start IPM
    public function getPeriodeIPM($idUsecase, $filter){
        try {
            $db = DB::table('mart_poda_social_ipm_year_periode')
                ->select('startYear', 'endYear', 'minYear', 'maxYear')
                ->where('id_usecase', $idUsecase)
                ->where('filter', $filter['filter'])
                ->first();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil periode IPM.');
        }
    }

    public function getNamaDaerahIPM($idUsecase, $filter){
        try {
            $db = DB::table('mart_poda_social_ipm_area_chart')->distinct()
                ->where('id_usecase', $idUsecase)
                ->where('filter', $filter['filter'])
                ->orderBy('city', 'asc')
                ->pluck('city');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil nama daerah IPM.');
        }
    }

    public function getIndikatorIPM($idUsecase){
        try {
            $db = DB::table('mart_poda_social_ipm_filter_indikator')->distinct()
                ->where('id_usecase', $idUsecase)
                ->pluck('nama');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil indikator IPM.');
        }
    }

    public function getAreaIPM($idUsecase, $params){
        try {
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
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil area IPM.');
        }
    }

    public function getMapIPM($idUsecase, $params){
        try {
            $db = DB::table('mart_poda_social_ipm_map_leaflet')
                ->select('city','lat','lon','ipm as data')
                ->where('id_usecase', $idUsecase)
                ->where('filter', $params['filter'])
                ->where('tahun', $params['tahun'])
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil map IPM.');
        }
    }

    public function getDetailIPM($idUsecase, $params){
        try {
            $periode = explode('-', $params['periode']);

            $startYear = $periode[0];
            $endYear = $periode[1];

            $db = DB::table('mart_poda_social_ipm_detail')
                ->select('city as category', 'tahun as column', 'value as data')
                ->where('id_usecase', $idUsecase)
                ->where('filter', $params['filter'])
                ->whereBetween('tahun', [$startYear, $endYear])
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil detail IPM.');
        }
    }
    // End IPM

    // Start Kemiskinan
    public function getIndikatorKemiskinan($idUsecase){
        try {
            $db = DB::table('mart_poda_social_kemiskinan_filter_indikator')
                ->distinct()->where('id_usecase', $idUsecase)
                ->pluck('nama');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil indikator kemiskinan.');
        }
    }

    public function getTahunKemiskinan($idUsecase){
        try {
            $db = DB::table('mart_poda_social_kemiskinan_filter_tahun')
                ->distinct()->where('id_usecase', $idUsecase)
                ->orderBy('tahun', 'desc')
                ->pluck('tahun');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil indikator kemiskinan.');
        }
    }

    public function getDaerahKemiskinan($idUsecase){
        try {
            $db = DB::table('mart_poda_social_kemiskinan_filterkab')
                ->distinct()->where('id_usecase', $idUsecase)
                ->pluck('city');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil daerah kemiskinan.');
        }
    }

    public function getPeriodeKemiskinan($idUsecase, $filter){
        try {
            $db = DB::table('mart_poda_social_kemiskinan_filter_periode')
                ->select('startYear', 'endYear', 'minYear', 'maxYear')
                ->where('id_usecase', $idUsecase)
                ->where('filter', $filter['filter'])
                ->first();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil periode kemiskinan.');
        }
    }

    public function getMapKemiskinan($idUsecase, $params){
        try {
            $db = DB::table('mart_poda_social_kemiskinan_map_leaflet')
                ->select('city', 'value as data', 'lat', 'lon')
                ->where('tahun', $params['tahun'])
                ->where('filter', $params['filter'])
                ->where('id_usecase', $idUsecase)
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil map kemiskinan.');
        }
    }

    public function getAreaKemiskinan($idUsecase, $params){
        try {
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
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil area kemiskinan.');
        }
    }

    public function getDetailKemiskinan($idUsecase, $params){
        try {
            $db = DB::table('mart_poda_social_kemiskinan_detail')
                ->select('kabupaten_kota as category', 'tahun as column', 'data')
                ->where('id_usecase', $idUsecase)
                ->where('tahun', $params['tahun'])
                ->where('filter', $params['filter'])
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil detail kemiskinan.');
        }
    }
    // End Kemiskinan

    // Start Pekerjaan dan Angkatan Kerja
    public function getIndikatorPekerjaan($idUsecase){
        try {
            $db = DB::table('mart_poda_social_pekerjaan_filter_indikator')
                ->distinct()->where('id_usecase', $idUsecase)
                ->pluck('indikator');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil indikator pekerjaan.');
        }
    }

    public function getTahunPekerjaan($idUsecase){
        try {
            $db = DB::table('mart_poda_social_pekerjaan_filter_year_leaflet')
                ->distinct()->where('id_usecase', $idUsecase)
                ->orderBy('tahun', 'desc')
                ->pluck('tahun');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil tahun pekerjaan.');
        }
    }

    public function getTahunJenisPekerjaan($idUsecase){
        try {
            $db = DB::table('master_poda_social_pekerjaan_type')
                ->distinct()->where('id_usecase', $idUsecase)
                ->orderBy('year', 'desc')
                ->pluck('year as tahun');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil tahun jenis pekerjaan.');
        }
    }

    public function getPeriodePekerjaan($idUsecase, $filter){
        try {
            $db = DB::table('mart_poda_social_pekerjaan_periode')
                ->select('startYear', 'endYear', 'minYear', 'maxYear')
                ->where('id_usecase', $idUsecase)
                ->where('indikator', $filter['filter'])
                ->first();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil periode pekerjaan.');
        }
    }

    public function getBarJenisPekerjaan($idUsecase, $tahun){
        try {
            $db = DB::table('master_poda_social_pekerjaan_type')
                ->selectRaw("jenis as chart_categories, sum(datacontent) as data")
                ->where('year', $tahun['tahun'])
                ->where('id_usecase', $idUsecase)
                ->groupBy('jenis')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil bar jenis pekerjaan.');
        }
    }

    public function getMapPekerjaan($idUsecase, $params){
        try {
            $db = DB::table('mart_poda_social_pekerjaan_map_leaflet')
                ->select('city', 'data', 'lat', 'lon')
                ->where('tahun', $params['tahun'])
                ->where('id_usecase', $idUsecase)
                ->where('indikator', $params['filter'])
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil map pekerjaan.');
        }
    }

    public function getLinePekerjaan($idUsecase, $params){
        try {
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
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil line pekerjaan.');
        }
    }

    public function getDetailJenisPekerjaan($idUsecase, $params){
        try {
            $db = DB::table('master_poda_social_pekerjaan_type')
                ->selectRaw("jenis as category, 'Total' as `column`, sum(datacontent) as data")
                ->where('id_usecase', $idUsecase)
                ->groupBy('jenis')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil detail jenis pekerjaan.');
        }
    }

    public function getDetailPekerjaan($idUsecase, $params){
        try {
            $tahun = explode('-', $params['periode']);

            $startYear = $tahun[0];
            $endYear = $tahun[1];

            $db = DB::table('mart_poda_social_pekerjaan_detail')
                ->select('city as category', 'tahun as column', 'data')
                ->where('id_usecase', $idUsecase)
                ->where('indikator', $params['indikator'])
                ->whereBetween('tahun', [$startYear, $endYear])
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil detail pekerjaan.');
        }
    }
    // End Pekerjaan dan Angkatan Kerja

    // Start Pendidikan
    public function getTahunAjaranPendidikan($idUsecase){
        try {
            $db = DB::table('mart_poda_social_pendidikan_filter_tahun_ajaran')
                ->distinct()->where('id_usecase', $idUsecase)
                ->orderBy('tahun', 'desc')
                ->pluck('tahun');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil tahun ajaran pendidikan.');
        }
    }

    public function getTahunPendidikan($idUsecase){
        try {
            $db = DB::table('mart_poda_social_pendidikan_filter_tahun')
                ->distinct()->where('id_usecase', $idUsecase)
                ->orderBy('years', 'desc')
                ->pluck('years');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil tahun pendidikan.');
        }
    }

    public function getJenjangPendidikan($idUsecase){
        try {
            $db = DB::table('mart_poda_social_pendidikan_filter_jenjang')
                ->distinct()->where('id_usecase', $idUsecase)
                ->pluck('nama');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil jenjang pendidikan.');
        }
    }

    public function getIndikatorPendidikan($idUsecase){
        try {
            $db = DB::table('mart_poda_social_pendidikan_filter_indikator')
                ->distinct()->where('id_usecase', $idUsecase)
                ->pluck('nama');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil indikator pendidikan.');
        }
    }

    public function getBarPendidikan($idUsecase, $params){
        try {
            $db = DB::table('mart_poda_social_pendidikan_bar_chart')
                ->select('city as chart_categories', 'data')
                ->where('tahun', $params['tahun'])
                ->where('sekolah', $params['jenjang'])
                ->where('jenis', $params['indikator'])
                ->where('id_usecase', $idUsecase)
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil bar pendidikan.');
        }
    }

    public function getBarJenjangPendidikan($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_social_pendidikan_jenjang_bar_chart')
                ->select('sekolah as chart_categories', 'data')
                ->where('tahun', $tahun)
                ->where('id_usecase', $idUsecase)
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil bar jenjang pendidikan.');
        }
    }

    public function getMapPendidikan($idUsecase, $params){
        try {
            $db = DB::table('mart_poda_social_pendidikan_map_leaflet')
                ->select('city', 'jenis', 'lat', 'lon', 'value')
                ->where('tahun', $params['tahun'])
                ->where('sekolah', $params['jenjang'])
                ->where('id_usecase', $idUsecase)
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil map pendidikan.');
        }
    }

    public function getDetailPendidikan($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_social_pendidikan_detail')
                ->select('city as category', 'sekolah as column', 'value as data')
                ->where('id_usecase', $idUsecase)
                ->where('tahun', $tahun)
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil detail pendidikan.');
        }
    }
    // End Pendidikan

    // Start Kesehatan
    public function getTahunKesehatan($idUsecase){
        try {
            $db = DB::table('mart_poda_social_kesehatan_filter_tahun')
                ->distinct()->where('id_usecase', $idUsecase)
                ->orderBy('tahun', 'desc')
                ->pluck('tahun');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil tahun kesehatan.');
        }
    }

    public function getIndikatorKesehatan($idUsecase){
        try {
            $db = DB::table('mart_poda_social_kesehatan_filter_faskes')
                ->distinct()->where('id_usecase', $idUsecase)
                ->orderBy('jenis', 'desc')
                ->pluck('jenis');

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil indikator kesehatan.');
        }
    }

    public function getPeriodeKesehatan($idUsecase){
        try {
            $db = DB::table('mart_poda_social_kesehatan_filter_periode')
                ->select('startYear', 'endYear', 'minYear', 'maxYear')
                ->where('id_usecase', $idUsecase)
                ->first();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil periode kesehatan.');
        }
    }

    public function getBarKesehatan($idUsecase, $params){
        try {
            $db = DB::table('mart_poda_social_kesehatan_bar_chart')
                ->select('chart_categories','chart_data as data')
                ->where('id_usecase', $idUsecase)
                ->where('tahun', $params['tahun'])
                ->where('widget_title', $params['filter'])
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil bar kesehatan.');
        }
    }

    public function getBarColumnKesehatan($idUsecase, $periode){
        try {
            $years = explode('-', $periode['periode']);

            $startYear = $years[0];
            $endYear = $years[1];

            $db = DB::table('mart_poda_social_kesehatan_column_chart')
                ->select('jenis','tahun','data')
                ->where('id_usecase', $idUsecase)
                ->whereBetween('tahun', [$startYear, $endYear])
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil bar column kesehatan.');
        }
    }

    public function getMapKesehatan($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_social_kesehatan_map_leaflet')
                ->select('city','lat','lon','jenis', 'data')
                ->where('id_usecase', $idUsecase)
                ->where('tahun', $tahun)
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil map kesehatan.');
        }
    }

    public function getDetailKesehatan($idUsecase, $tahun){
        try {
            $db = DB::table('mart_poda_social_kesehatan_detail')
                ->select('kabupaten_kota as category', 'jenis as column', 'data')
                ->where('id_usecase', $idUsecase)
                ->where('tahun', $tahun)
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil detail kesehatan.');
        }
    }
    // End Kesehatan
}
