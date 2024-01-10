<?php

namespace App\Services\Poda;

use App\Repositories\Admin\MasterRepositories;
use App\Repositories\Poda\EkonomiPerdaganganRepositories;
use App\Traits\FormatChart;
use Exception;

class EkonomiPerdaganganServices {

    use FormatChart;
    protected $ekonomiRepositories;
    protected $masterRepositories;

    public function __construct(EkonomiPerdaganganRepositories $ekonomiRepositories, MasterRepositories $masterRepositories)
    {
        $this->ekonomiRepositories = $ekonomiRepositories;
        $this->masterRepositories = $masterRepositories;
    }

    public function getMonthPeriodeInflasi($idUsecase){
        $rows = $this->ekonomiRepositories->getMonthPeriodeInflasi($idUsecase);

        $response = $this->filterMonthPeriode($rows);

        return $response;
    }

    public function getNamaDaerahInflasi($idUsecase){
        $rows = $this->ekonomiRepositories->getNamaDaerahInflasi($idUsecase);

        $response = $this->listNamaDaerah($rows);

        return $response;
    }

    public function getTahunInflasi($idUsecase){
        $rows = $this->ekonomiRepositories->getTahunInflasi($idUsecase);

        $response = $this->filterTahun($rows);

        return $response;
    }

    public function getBulanInflasi($idUsecase, $tahun){
        $rows = $this->ekonomiRepositories->getBulanInflasi($idUsecase, $tahun);

        $response = $this->filterTahun($rows, true);

        return $response;
    }

    public function getMapInflasi($idUsecase, $params){
        $rows = $this->ekonomiRepositories->getMapInflasi($idUsecase, $params);

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

    public function getDualChartInflasi($idUsecase, $params){
        $rows = $this->ekonomiRepositories->getDualChartInflasi($idUsecase, $params);

        $chart_type = "dual-axes";

        $chart_params = [
            'name_column' => 'Indeks Harga Konsumen',
            'column_title' => 'IHK',
            'line_title' => 'Inflasi',
        ];

        $response = $this->barColumnChart($rows, $chart_type, $chart_params);

        return $response;
    }
}
