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

    // Start Kependudukan
    public function getTahunJumlahPenduduk($idUsecase){
        $rows = $this->sosialRepositories->getTahunJumlahPenduduk($idUsecase);

        $response = $this->filterTahun($rows);

        return $response;
    }

    public function getMapJumlahPenduduk($tahun, $idUsecase){
        $rows = $this->sosialRepositories->getMapJumlahPenduduk($tahun, $idUsecase);

        $response = $this->mapLeaflet($rows);

        return $response;
    }

    public function getPieJumlahPenduduk($tahun, $idUsecase){
        $rows = $this->sosialRepositories->getPieJumlahPenduduk($tahun, $idUsecase);

        $response = $this->pieChart($rows, $tahun);

        return $response;
    }

    public function getBarJumlahPenduduk($tahun, $idUsecase){
        $rows = $this->sosialRepositories->getBarJumlahPenduduk($tahun, $idUsecase);

        $kode_kabkota = $this->masterRepositories->getKodeKabkota($idUsecase);

        $response = $this->barChart($rows, $kode_kabkota->kode_kab_kota);

        return $response;
    }

    public function getDetailJumlahPenduduk($tahun, $idUsecase){
        $rows = $this->sosialRepositories->getDetailJumlahPenduduk($tahun, $idUsecase);

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

    public function getStackedBarRentangUsia($tahun, $idUsecase){
        $rows = $this->sosialRepositories->getStackedBarRentangUsia($tahun, $idUsecase);

        $response = $this->stackedBarChart($tahun, $rows);

        return $response;
    }

    // End Rentang Usia

    // Start Kemiskinan
    public function getTahunKemiskinan($idUsecase){
        $rows = $this->sosialRepositories->getTahunKemiskinan($idUsecase);

        $response = $this->filterTahun($rows);

        return $response;
    }
    // End Kemiskinan
}
