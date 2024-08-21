<?php

namespace App\Services\Poda;

use App\Repositories\Admin\MasterRepositories;
use App\Repositories\Poda\SosialKependudukanRepositories;
use App\Traits\FormatChart;
use App\Exceptions\ErrorResponse;
use Exception;

class SosialKependudukanServices {

    use FormatChart;
    protected $sosialRepositories;
    protected $masterRepositories;

    public function __construct(SosialKependudukanRepositories $sosialRepositories, MasterRepositories $masterRepositories)
    {
        $this->sosialRepositories = $sosialRepositories;
        $this->masterRepositories = $masterRepositories;
    }

    public function getAxisTitleByIndikator($indikator)
    {
        switch($indikator){
            // IPM
            case "Indeks Pembangunan Manusia":
                $axis_title = "Indeks";
                break;
            case "Harapan Lama Sekolah":
                $axis_title = "Lama Sekolah (Tahun)";
                break;
            case "Rata-rata Lama Sekolah":
                $axis_title = "Lama Sekolah (Tahun)";
                break;
            case "Pengeluaran per Kapita":
                $axis_title = "Pengeluaran (ribu rupiah/orang/tahun)";
                break;
            case "Usia Harapan Hidup":
                $axis_title = "Rata-rata";
                break;

            // Kemiskinan
            case "Garis Kemiskinan":
                $axis_title = "Garis Kemiskinan (Rp/Kapita/Bulan)";
                break;
            case "Jumlah Penduduk Miskin":
            case "Jumlah Angkatan Kerja":
                $axis_title = "Jumlah Orang";
                break;
            case "Persentase Penduduk Miskin":
            case "Tingkat Pengangguran Terbuka":
            case "Tingkat Partisipasi Angkatan Kerja":
                $axis_title = "Persentase (%)";
                break;
            case "Gini Ratio":
                $axis_title = "Indeks Gini Ratio";
                break;
        }

        return $axis_title;
    }

    // Start Kependudukan
    public function getTahunJumlahPenduduk($idUsecase){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getTahunJumlahPenduduk($idUsecase);

        $response = $this->filterTahun($rows);

        return $response;
    }

    public function getMapJumlahPenduduk($idUsecase, $tahun){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getMapJumlahPenduduk($idUsecase, $tahun);

        $data = [];
        foreach($rows as $item){
            $output[$item->city]["city"] = $item->city;
            $output[$item->city]["lat"] = $item->lat;
            $output[$item->city]["lon"] = $item->lon;

            $output[$item->city]["data"] = [
                [
                    "label" => "Laki-laki",
                    "value" => $item->Lakilaki,
                ],
                [
                    "label" => "Perempuan",
                    "value" => $item->Perempuan,
                ]
            ];
        }

        $response = $this->mapLeaflet(array_values($output));

        return $response;
    }

    public function getPieJumlahPenduduk($idUsecase, $tahun){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getPieJumlahPenduduk($idUsecase, $tahun);

        $response = $this->pieChart($rows, $tahun);

        return $response;
    }

    public function getBarJumlahPenduduk($idUsecase, $tahun){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getBarJumlahPenduduk($idUsecase, $tahun);

        $kode_kabkota = $this->masterRepositories->getKodeKabkota($idUsecase);

        $chart_params = [
            'y_axis_title' => 'Jumlah Penduduk',
        ];

        $response = $this->barChart($rows, $kode_kabkota->kode_kab_kota, $chart_params);

        return $response;
    }

    public function getDetailJumlahPenduduk($idUsecase, $tahun){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getDetailJumlahPenduduk($idUsecase, $tahun);

        $kode_kabkota = $this->masterRepositories->getKodeKabkota($idUsecase);

        $title = "Detail Jumlah Penduduk dan jenis Kelamin, ". $tahun['tahun'];

        $response = $this->detailTable($rows, $kode_kabkota->kode_kab_kota, $title);

        return $response;
    }
    // End Kependudukan

    // Start Rentang Usia
    public function getTahunRentangUsia($idUsecase){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getTahunRentangUsia($idUsecase);

        $response = $this->filterTahun($rows);

        return $response;
    }

    public function getStackedBarRentangUsia($idUsecase, $tahun){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getStackedBarRentangUsia($idUsecase, $tahun);

        $response = $this->stackedBarChart($tahun, $rows);

        return $response;
    }

    public function getDetailRentangUsia($idUsecase, $tahun){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getDetailRentangUsia($idUsecase, $tahun);

        $kode_kabkota = $this->masterRepositories->getKodeKabkota($idUsecase);
        if (!$kode_kabkota) {
            throw new ErrorResponse(type: 'Not Found', message: 'Kode Kabupaten/Kota tidak ditemukan.', statusCode: 404);
        }

        $title = "Detail Rentang Usia Menurut Jenis Kelamin, ". $tahun['tahun'];

        $response = $this->detailTable($rows, $kode_kabkota->kode_kab_kota, $title);

        return $response;
    }
    // End Rentang Usia

    // Start Laju Pertumbuhan
    public function getPeriodeLaju($idUsecase){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getPeriodeLaju($idUsecase);

        $response = $this->filterPeriode($rows);

        return $response;
    }

    public function getNamaDaerahLaju($idUsecase){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getNamaDaerahLaju($idUsecase);

        $response = $this->listNamaDaerah($rows);

        return $response;
    }

    public function getDualAxesLaju($idUsecase, $params){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getDualAxesLaju($idUsecase,$params);

        $chart_type = "dual-axes";

        $chart_params = [
            'name_column' => 'Jumlah',
            'column_title' => 'Jumlah Penduduk',
            'line_title' => 'Persentase (%)',
        ];

        $response = $this->barColumnChart($rows, $chart_type, $chart_params);

        return $response;
    }

    public function getDetailLaju($idUsecase, $periode){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getDetailLaju($idUsecase, $periode);

        $kode_kabkota = $this->masterRepositories->getKodeKabkota($idUsecase);
        if (!$kode_kabkota) {
            throw new ErrorResponse(type: 'Not Found', message: 'Kode Kabupaten/Kota tidak ditemukan.', statusCode: 404);
        }

        $title = "Detail Laju Pertumbuhan Penduduk (%), ". $periode['periode'];

        $response = $this->detailTable($rows, $kode_kabkota->kode_kab_kota, $title);

        return $response;
    }
    // End Laju Pertumbuhan

    // Start Rasio Jenis Kelamin
    public function getTahunRasio($idUsecase){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getTahunRasio($idUsecase);

        $response = $this->filterTahun($rows);

        return $response;
    }

    public function getMapRasio($idUsecase, $tahun){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getMapRasio($idUsecase, $tahun);

        $data = [];
        foreach($rows as $item){
            $output[$item->city]["city"] = $item->city;
            $output[$item->city]["lat"] = $item->lat;
            $output[$item->city]["lon"] = $item->lon;

            $output[$item->city]["data"][] = [
                "label" => "Rasio Jenis Kelamin",
                "value" => $item->rasio
            ];
        }

        $response = $this->mapLeaflet(array_values($output));

        return $response;
    }

    public function getBarRasio($idUsecase, $tahun){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getBarRasio($idUsecase, $tahun);

        $kode_kabkota = $this->masterRepositories->getKodeKabkota($idUsecase);
        if (!$kode_kabkota) {
            throw new ErrorResponse(type: 'Not Found', message: 'Kode Kabupaten/Kota tidak ditemukan.', statusCode: 404);
        }

        $chart_params = [
            'y_axis_title' => 'Rasio Jenis Kelamin'
        ];

        $response = $this->barChart($rows, $kode_kabkota->kode_kab_kota, $chart_params);

        return $response;
    }

    public function getDetailRasio($idUsecase, $tahun){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getDetailRasio($idUsecase, $tahun);

        $kode_kabkota = $this->masterRepositories->getKodeKabkota($idUsecase);
        if (!$kode_kabkota) {
            throw new ErrorResponse(type: 'Not Found', message: 'Kode Kabupaten/Kota tidak ditemukan.', statusCode: 404);
        }

        $title = "Detail Rasio Jenis Kelamin, ". $tahun['tahun'];

        $response = $this->detailTable($rows, $kode_kabkota->kode_kab_kota, $title);

        return $response;
    }
    // End Rasio Jenis Kelamin

    // Start Kepadatan Penduduk
    public function getTahunKepadatan($idUsecase){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getTahunKepadatan($idUsecase);

        $response = $this->filterTahun($rows);

        return $response;
    }

    public function getMapKepadatan($idUsecase, $tahun){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getMapKepadatan($idUsecase, $tahun);

        foreach($rows as $item){
            $output[$item->city]["city"] = $item->city;
            $output[$item->city]["lat"] = $item->lat;
            $output[$item->city]["lon"] = $item->lon;

            $output[$item->city]["data"][] = [
                "label" => "Jumlah Kepadatan Penduduk",
                "value" => $item->nilai
            ];
        }

        $response = $this->mapLeaflet(array_values($output));

        return $response;
    }

    public function getBarKepadatan($idUsecase, $tahun){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getBarKepadatan($idUsecase, $tahun);

        $kode_kabkota = $this->masterRepositories->getKodeKabkota($idUsecase);
        if (!$kode_kabkota) {
            throw new ErrorResponse(type: 'Not Found', message: 'Kode Kabupaten/Kota tidak ditemukan.', statusCode: 404);
        }

        $chart_params = [
            'y_axis_title' => "Kepadatan Penduduk"
        ];

        $response = $this->barChart($rows, $kode_kabkota->kode_kab_kota, $chart_params);

        return $response;
    }

    public function getDetailKepadatan($idUsecase, $tahun){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getDetailKepadatan($idUsecase, $tahun);

        $kode_kabkota = $this->masterRepositories->getKodeKabkota($idUsecase);
        if (!$kode_kabkota) {
            throw new ErrorResponse(type: 'Not Found', message: 'Kode Kabupaten/Kota tidak ditemukan.', statusCode: 404);
        }

        $title = "Detail Kepadatan Penduduk, ". $tahun['tahun'];

        $response = $this->detailTable($rows, $kode_kabkota->kode_kab_kota, $title);

        return $response;
    }
    // End Kepadatan Penduduk

    // Start IPM
    public function getPeriodeIPM($idUsecase, $filter){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getPeriodeIPM($idUsecase, $filter);

        $response = $this->filterPeriode($rows);

        return $response;
    }

    public function getNamaDaerahIPM($idUsecase, $filter){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getNamaDaerahIPM($idUsecase, $filter);

        $response = $this->listNamaDaerah($rows);

        return $response;
    }

    public function getIndikatorIPM($idUsecase){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getIndikatorIPM($idUsecase);

        $response = $this->listIndikator($rows);

        return $response;
    }

    public function getAreaIPM($idUsecase, $params){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getAreaIPM($idUsecase, $params);

        $y_axis_title = $this->getAxisTitleByIndikator($params['filter']);

        $axis_title = [
            'y_axis_title' => $y_axis_title,
            'x_axis_title' => 'Tahun'
        ];

        $response = $this->areaLineChart($rows, $params, $axis_title, "chart_area");

        return $response;
    }

    public function getMapIPM($idUsecase, $params){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getMapIPM($idUsecase, $params);

        $output = [];

        foreach ($rows as $item) {
            $output[$item->city]["city"] = $item->city;
            $output[$item->city]["lat"] = $item->lat;
            $output[$item->city]["lon"] = $item->lon;

            $output[$item->city]["data"][] = [
                "label" => "Indeks Pembangunan",
                "value" => $item->data
            ];
        }

        $response = $this->mapLeaflet(array_values($output));

        return $response;
    }

    public function getDetailIPM($idUsecase, $params){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getDetailIPM($idUsecase, $params);

        $kode_kabkota = $this->masterRepositories->getKodeKabkota($idUsecase);
        if (!$kode_kabkota) {
            throw new ErrorResponse(type: 'Not Found', message: 'Kode Kabupaten/Kota tidak ditemukan.', statusCode: 404);
        }

        $title = $params['filter'].", ". $params['periode'];

        $response = $this->detailTable($rows, $kode_kabkota->kode_kab_kota, $title);

        return $response;
    }
    // End IPM

    // Start Kemiskinan
    public function getIndikatorKemiskinan($idUsecase){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getIndikatorKemiskinan($idUsecase);

        $response = $this->listIndikator($rows);

        return $response;
    }

    public function getTahunKemiskinan($idUsecase){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getTahunKemiskinan($idUsecase);

        $response = $this->filterTahun($rows);

        return $response;
    }

    public function getDaerahKemiskinan($idUsecase){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getDaerahKemiskinan($idUsecase);

        $response = $this->listNamaDaerah($rows);

        return $response;
    }

    public function getPeriodeKemiskinan($idUsecase, $filter){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getPeriodeKemiskinan($idUsecase, $filter);

        $response = $this->filterPeriode($rows);

        return $response;
    }

    public function getMapKemiskinan($idUsecase, $params){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getMapKemiskinan($idUsecase, $params);

        $data = [];
        foreach($rows as $item){
            $output[$item->city]["city"] = $item->city;
            $output[$item->city]["lat"] = $item->lat;
            $output[$item->city]["lon"] = $item->lon;

            $output[$item->city]["data"][] = [
                "label" => "Jumlah Kemiskinan",
                "value" => $item->data
            ];
        }

        $response = $this->mapLeaflet(array_values($output));

        return $response;
    }

    public function getAreaKemiskinan($idUsecase, $params){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getAreaKemiskinan($idUsecase, $params);

        $y_axis_title = $this->getAxisTitleByIndikator($params['filter']);

        $axis_title = [
            'y_axis_title' => $y_axis_title,
            'x_axis_title' => 'Tahun'
        ];

        $response = $this->areaLineChart($rows, $params, $axis_title, "chart_area");

        return $response;
    }

    public function getDetailKemiskinan($idUsecase, $params){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getDetailKemiskinan($idUsecase, $params);

        $kode_kabkota = $this->masterRepositories->getKodeKabkota($idUsecase);
        if (!$kode_kabkota) {
            throw new ErrorResponse(type: 'Not Found', message: 'Kode Kabupaten/Kota tidak ditemukan.', statusCode: 404);
        }

        $title = "Detail Kemiskinan Penduduk, ". $params['tahun'];

        $response = $this->detailTable($rows, $kode_kabkota->kode_kab_kota, $title);

        return $response;
    }
    // End Kemiskinan

    // Start Pekerjaan dan Angkatan Kerja
    public function getIndikatorPekerjaan($idUsecase){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getIndikatorPekerjaan($idUsecase);

        $response = $this->listIndikator($rows);

        return $response;
    }

    public function getTahunPekerjaan($idUsecase){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getTahunPekerjaan($idUsecase);

        $response = $this->filterTahun($rows);

        return $response;
    }

    public function getTahunJenisPekerjaan($idUsecase){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        
        $rows = $this->sosialRepositories->getTahunJenisPekerjaan($idUsecase);

        if ($rows->isEmpty()) {
            return $rows;
        }
        $response = $this->filterTahun($rows);

        return $response;
    }

    public function getPeriodePekerjaan($idUsecase, $filter){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getPeriodePekerjaan($idUsecase, $filter);

        $response = $this->filterPeriode($rows);

        return $response;
    }

    public function getBarJenisPekerjaan($idUsecase, $tahun){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getBarJenisPekerjaan($idUsecase, $tahun);

        $chart_params = [
            'x_axis_title' => "Jenis Pekerjaan",
            'y_axis_title' => "Jumlah Jiwa"
        ];

        $response = $this->barChart($rows, "", $chart_params);

        return $response;
    }

    public function getMapPekerjaan($idUsecase, $params){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getMapPekerjaan($idUsecase, $params);

        foreach($rows as $item){
            $output[$item->city]["city"] = $item->city;
            $output[$item->city]["lat"] = $item->lat;
            $output[$item->city]["lon"] = $item->lon;

            $output[$item->city]["data"][] = [
                "label" => $params['filter'],
                "value" => $item->data
            ];
        }

        $response = $this->mapLeaflet(array_values($output));

        return $response;
    }

    public function getLinePekerjaan($idUsecase, $params){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getLinePekerjaan($idUsecase, $params);

        $y_axis_title = $this->getAxisTitleByIndikator($params['filter']);

        $axis_title = [
            'y_axis_title' => $y_axis_title,
            'x_axis_title' => 'Tahun'
        ];

        $response = $this->areaLineChart($rows, $params, $axis_title, "chart_line_series");

        return $response;
    }

    public function getDetailJenisPekerjaan($idUsecase, $params){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getDetailJenisPekerjaan($idUsecase, $params);

        $title = "Detail Pekerjaan dan Angkatan Kerja berdasarkan Jenis Pekerjaan, ". $params['tahun'];

        $response = $this->detailTable($rows, "", $title, "Jenis Pekerjaan");

        return $response;
    }

    public function getDetailPekerjaan($idUsecase, $params){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getDetailPekerjaan($idUsecase, $params);

        $kode_kabkota = $this->masterRepositories->getKodeKabkota($idUsecase);
        if (!$kode_kabkota) {
            throw new ErrorResponse(type: 'Not Found', message: 'Kode Kabupaten/Kota tidak ditemukan.', statusCode: 404);
        }

        $title = "Detail Pekerjaan dan Angkatan Kerja berdasarkan Jenis Pekerjaan, ". $params['periode'];

        $response = $this->detailTable($rows, $kode_kabkota->kode_kab_kota, $title);

        return $response;
    }
    // End Pekerjaan dan Angkatan Kerja

    // Start Pendidikan
    public function getTahunAjaranPendidikan($idUsecase){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getTahunAjaranPendidikan($idUsecase);

        $response = $this->filterTahun($rows);

        return $response;
    }

    public function getTahunPendidikan($idUsecase){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getTahunPendidikan($idUsecase);

        $response = $this->filterTahun($rows);

        return $response;
    }

    public function getJenjangPendidikan($idUsecase){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getJenjangPendidikan($idUsecase);

        $response = $this->listIndikator($rows);

        return $response;
    }

    public function getIndikatorPendidikan($idUsecase){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getIndikatorPendidikan($idUsecase);

        $response = $this->listIndikator($rows);

        return $response;
    }

    public function getBarPendidikan($idUsecase, $params){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getBarPendidikan($idUsecase, $params);

        $kode_kabkota = $this->masterRepositories->getKodeKabkota($idUsecase);
        if (!$kode_kabkota) {
            throw new ErrorResponse(type: 'Not Found', message: 'Kode Kabupaten/Kota tidak ditemukan.', statusCode: 404);
        }

        $chart_params = [
            'y_axis_title' => "Jumlah"
        ];

        $response = $this->barChart($rows, $kode_kabkota->kode_kab_kota, $chart_params);

        return $response;
    }

    public function getBarJenjangPendidikan($idUsecase, $tahun){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getBarJenjangPendidikan($idUsecase, $tahun);

        $chart_params = [
            'y_axis_title' => "Jenjang Pendidikan"
        ];

        $response = $this->barChart($rows, "", $chart_params);

        return $response;
    }

    public function getMapPendidikan($idUsecase, $params){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getMapPendidikan($idUsecase, $params);

        foreach ($rows as $item) {
            $output[$item->city]["city"] = $item->city;
            $output[$item->city]["lat"] = $item->lat;
            $output[$item->city]["lon"] = $item->lon;

            $output[$item->city]["data"][] = [
                "label" => $item->jenis,
                "value" => $item->value
            ];
        }

        $response = $this->mapLeaflet(array_values($output));

        return $response;
    }

    public function getDetailPendidikan($idUsecase, $tahun){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getDetailPendidikan($idUsecase, $tahun);

        $kode_kabkota = $this->masterRepositories->getKodeKabkota($idUsecase);
        if (!$kode_kabkota) {
            throw new ErrorResponse(type: 'Not Found', message: 'Kode Kabupaten/Kota tidak ditemukan.', statusCode: 404);
        }

        $title = "Detail Jumlah Infrastruktur Pendidikan, ". $tahun['tahun'];

        $response = $this->detailTable($rows, $kode_kabkota->kode_kab_kota, $title);

        return $response;
    }
    // End Pendidikan

    // Start Kesehatan
    public function getTahunKesehatan($idUsecase){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getTahunKesehatan($idUsecase);

        $response = $this->filterTahun($rows);

        return $response;
    }

    public function getIndikatorKesehatan($idUsecase){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getIndikatorKesehatan($idUsecase);

        $response = $this->listIndikator($rows);

        return $response;
    }

    public function getPeriodeKesehatan($idUsecase){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getPeriodeKesehatan($idUsecase);

        $response = $this->filterPeriode($rows);

        return $response;
    }

    public function getBarKesehatan($idUsecase, $params){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getBarKesehatan($idUsecase, $params);

        $kode_kabkota = $this->masterRepositories->getKodeKabkota($idUsecase);
        if (!$kode_kabkota) {
            throw new ErrorResponse(type: 'Not Found', message: 'Kode Kabupaten/Kota tidak ditemukan.', statusCode: 404);
        }

        $chart_params = [
            'y_axis_title' => 'Jumlah'
        ];

        $response = $this->barChart($rows, $kode_kabkota->kode_kab_kota, $chart_params);

        return $response;
    }

    public function getBarColumnKesehatan($idUsecase, $params){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getBarColumnKesehatan($idUsecase, $params);

        $chart_type = "chart-column-series";

        $response = $this->barColumnChart($rows, $chart_type);

        return $response;
    }

    public function getMapKesehatan($idUsecase, $tahun){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getMapKesehatan($idUsecase, $tahun);

        foreach ($rows as $item) {
            $output[$item->city]["city"] = $item->city;
            $output[$item->city]["lat"] = $item->lat;
            $output[$item->city]["lon"] = $item->lon;

            $output[$item->city]["data"][] = [
                "label" => $item->jenis,
                "value" => $item->data
            ];
        }

        $response = $this->mapLeaflet(array_values($output));

        return $response;
    }

    public function getDetailKesehatan($idUsecase, $tahun){
        if (empty($idUsecase)) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak logged in.', statusCode: 401);
        }
        $rows = $this->sosialRepositories->getDetailKesehatan($idUsecase, $tahun);

        $kode_kabkota = $this->masterRepositories->getKodeKabkota($idUsecase);
        if (!$kode_kabkota) {
            throw new ErrorResponse(type: 'Not Found', message: 'Kode Kabupaten/Kota tidak ditemukan.', statusCode: 404);
        }

        $title = "Detail Jumlah Infrastruktur Kesehatan, ". $tahun['tahun'];

        $response = $this->detailTable($rows, $kode_kabkota->kode_kab_kota, $title);

        return $response;
    }
    // End Kesehatan
}
