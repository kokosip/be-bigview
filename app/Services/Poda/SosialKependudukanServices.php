<?php

namespace App\Services\Poda;

use App\Repositories\Admin\MasterRepositories;
use App\Repositories\Poda\SosialKependudukanRepositories;
use App\Traits\FormatChart;
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
        }

        return $axis_title;
    }

    // Start Kependudukan
    public function getTahunJumlahPenduduk($idUsecase){
        $rows = $this->sosialRepositories->getTahunJumlahPenduduk($idUsecase);

        $response = $this->filterTahun($rows);

        return $response;
    }

    public function getMapJumlahPenduduk($idUsecase, $tahun){
        $rows = $this->sosialRepositories->getMapJumlahPenduduk($idUsecase, $tahun);

        $response = $this->mapLeaflet($rows);

        return $response;
    }

    public function getPieJumlahPenduduk($idUsecase, $tahun){
        $rows = $this->sosialRepositories->getPieJumlahPenduduk($idUsecase, $tahun);

        $response = $this->pieChart($rows, $tahun);

        return $response;
    }

    public function getBarJumlahPenduduk($idUsecase, $tahun){
        $rows = $this->sosialRepositories->getBarJumlahPenduduk($idUsecase, $tahun);

        $kode_kabkota = $this->masterRepositories->getKodeKabkota($idUsecase);
        $axis_title = "Jumlah Penduduk";

        $response = $this->barChart($rows, $kode_kabkota->kode_kab_kota, $axis_title);

        return $response;
    }

    public function getDetailJumlahPenduduk($idUsecase, $tahun){
        $rows = $this->sosialRepositories->getDetailJumlahPenduduk($idUsecase, $tahun);

        $kode_kabkota = $this->masterRepositories->getKodeKabkota($idUsecase);

        $title = "Detail Jumlah Penduduk dan jenis Kelamin, $tahun";

        $response = $this->tableDetail($rows, $kode_kabkota->kode_kab_kota, $title);

        return $response;
    }
    // End Kependudukan

    // Start Rentang Usia
    public function getTahunRentangUsia($idUsecase){
        $rows = $this->sosialRepositories->getTahunRentangUsia($idUsecase);

        $response = $this->filterTahun($rows);

        return $response;
    }

    public function getStackedBarRentangUsia($idUsecase, $tahun){
        $rows = $this->sosialRepositories->getStackedBarRentangUsia($idUsecase, $tahun);

        $response = $this->stackedBarChart($tahun, $rows);

        return $response;
    }

    // End Rentang Usia

    // Start Rasio Jenis Kelamin
    public function getTahunRasio($idUsecase){
        $rows = $this->sosialRepositories->getTahunRasio($idUsecase);

        $response = $this->filterTahun($rows);

        return $response;
    }

    public function getMapRasio($idUsecase, $tahun){
        $rows = $this->sosialRepositories->getMapRasio($idUsecase, $tahun);

        $response = $this->mapLeaflet($rows);

        return $response;
    }

    public function getBarRasio($idUsecase, $tahun){
        $rows = $this->sosialRepositories->getBarRasio($idUsecase, $tahun);

        $kode_kabkota = $this->masterRepositories->getKodeKabkota($idUsecase);
        $axis_title = "Rasio Jenis Kelamin";

        $response = $this->barChart($rows, $kode_kabkota->kode_kab_kota, $axis_title);

        return $response;
    }
    // End Rasio Jenis Kelamin

    // Start Kepadatan Penduduk
    public function getTahunKepadatan($idUsecase){
        $rows = $this->sosialRepositories->getTahunKepadatan($idUsecase);

        $response = $this->filterTahun($rows);

        return $response;
    }

    public function getMapKepadatan($idUsecase, $tahun){
        $rows = $this->sosialRepositories->getMapKepadatan($idUsecase, $tahun);

        $response = $this->mapLeaflet($rows);

        return $response;
    }

    public function getBarKepadatan($idUsecase, $tahun){
        $rows = $this->sosialRepositories->getBarKepadatan($idUsecase, $tahun);

        $kode_kabkota = $this->masterRepositories->getKodeKabkota($idUsecase);
        $axis_title = "Kepadatan Penduduk";

        $response = $this->barChart($rows, $kode_kabkota->kode_kab_kota, $axis_title);

        return $response;
    }
    // End Kepadatan Penduduk

    // Start IPM
    public function getPeriodeIPM($idUsecase, $filter){
        $rows = $this->sosialRepositories->getPeriodeIPM($idUsecase, $filter);

        $response = $this->filterPeriode($rows);

        return $response;
    }

    public function getNamaDaerahIPM($idUsecase, $filter){
        $rows = $this->sosialRepositories->getNamaDaerahIPM($idUsecase, $filter);

        $response = $this->listNamaDaerah($rows);

        return $response;
    }

    public function getIndikatorIPM($idUsecase){
        $rows = $this->sosialRepositories->getIndikatorIPM($idUsecase);

        $response = $this->listIndikator($rows);

        return $response;
    }

    public function getAreaIPM($idUsecase, $params){
        $rows = $this->sosialRepositories->getAreaIPM($idUsecase, $params);

        $axis_title = $this->getAxisTitleByIndikator($params['filter']);

        $response = $this->areaChart($rows, $params, $axis_title);

        return $response;
    }

    public function getMapIPM($idUsecase, $params){
        $rows = $this->sosialRepositories->getMapIPM($idUsecase, $params);

        $response = $this->mapLeaflet($rows);

        return $response;
    }
    // End IPM

    // Start Kemiskinan
    public function getTahunKemiskinan($idUsecase){
        $rows = $this->sosialRepositories->getTahunKemiskinan($idUsecase);

        $response = $this->filterTahun($rows);

        return $response;
    }
    // End Kemiskinan
}
