<?php

namespace App\Services\Poda;

use App\Repositories\Poda\SosialKependudukanRepositories;
use App\Traits\FormatChart;
use Exception;

class SosialKependudukanServices {

    use FormatChart;
    protected $sosialRepositories;

    public function __construct(SosialKependudukanRepositories $sosialRepositories)
    {
        $this->sosialRepositories = $sosialRepositories;
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

        $response = $this->barChart($rows);

        return $response;
    }
}
