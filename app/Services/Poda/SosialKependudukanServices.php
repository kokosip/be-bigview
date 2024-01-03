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
}
