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

    // Start Inflasi dan IHK
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
    // End Inflasi dan IHK

    // Start PDRB
    public function getTahunPDRB($idUsecase){
        $rows = $this->ekonomiRepositories->getTahunPDRB($idUsecase);

        $response = $this->filterTahun($rows);

        return $response;
    }

    public function getKategoriPDRB($idUsecase){
        $rows = $this->ekonomiRepositories->getKategoriPDRB($idUsecase);

        $response = $this->listIndikator($rows);

        return $response;
    }

    public function getSektorPDRB($idUsecase){
        $rows = $this->ekonomiRepositories->getSektorPDRB($idUsecase);

        $data = [];
        foreach ($rows as $value) {
            $data[] = preg_replace('/^[A-Z,]+\. /', '', $value);
        }

        $response = $this->listIndikator($data);

        return $response;
    }

    public function getCardPDRB($idUsecase, $params){
        $rows = $this->ekonomiRepositories->getCardPDRB($idUsecase, $params);

        $response = $this->getCard($rows);

        return $response;
    }

    public function getBarPDRB($idUsecase, $params){
        $rows = $this->ekonomiRepositories->getBarPDRB($idUsecase, $params);

        $kode_kabkota = $this->masterRepositories->getKodeKabkota($idUsecase);

        $data = [];
        foreach ($rows as $value) {
            $value->chart_categories = preg_replace('/^[A-Z,]+\. /', '', $value->chart_categories);
            $data[] = $value;
        }

        $chart_params = [
            'y_axis_title' => 'Nominal (Rupiah)'
        ];

        $response = $this->barChart($data, $kode_kabkota->kode_kab_kota, $chart_params);

        return $response;
    }

    public function getAreaPDRB($idUsecase, $params){
        $rows = $this->ekonomiRepositories->getAreaPDRB($idUsecase, $params);

        if($rows[1] == 'Tahun'){
            $x_axis_title = 'Tahun';
        } else {
            $x_axis_title = 'Triwulan';
        }

        $axis_title = [
            'y_axis_title' => 'Nominal (Rupiah)',
            'x_axis_title' => $x_axis_title
        ];

        $response = $this->areaLineChart($rows[0], $params, $axis_title, "chart_area");

        return $response;
    }
    // End PDRB

    // Start Pariwisata
    public function getIndikatorPariwisata($idUsecase){
        $rows = $this->ekonomiRepositories->getIndikatorPariwisata($idUsecase);

        $response = $this->listIndikator($rows);

        return $response;
    }

    public function getNamaDaerahPariwisataDTW($idUsecase){
        $rows = $this->ekonomiRepositories->getNamaDaerahPariwisataDTW($idUsecase);

        $response = $this->listNamaDaerah($rows);

        return $response;
    }

    public function getPeriodePariwisataDTW($idUsecase){
        $rows = $this->ekonomiRepositories->getPeriodePariwisataDTW($idUsecase);

        $response = $this->filterPeriode($rows);

        return $response;
    }

    public function getTahunPariwisataDTW($idUsecase){
        $rows = $this->ekonomiRepositories->getTahunPariwisataDTW($idUsecase);

        $response = $this->filterTahun($rows);

        return $response;
    }

    public function getMapPariwisataDTW($idUsecase, $tahun){
        $rows = $this->ekonomiRepositories->getMapPariwisataDTW($idUsecase, $tahun);

        foreach ($rows as $item) {
            $output[$item->city]["city"] = $item->city;
            $output[$item->city]["lat"] = $item->lat;
            $output[$item->city]["lon"] = $item->lon;

            $output[$item->city]["data"][] = [
                "label" => "Daya Tarik Wisata",
                "value" => $item->data
            ];
        }

        $response = $this->mapLeaflet(array_values($output));

        return $response;
    }

    public function getLinePariwisataDTW($idUsecase, $params){
        $rows = $this->ekonomiRepositories->getLinePariwisataDTW($idUsecase, $params);

        $axis_title = [
            'filter' => 'Daya Tarik Wisata',
            'y_axis_title' => 'Jumlah',
            'x_axis_title' => 'Tahun'
        ];

        $response = $this->areaLineChart($rows, $axis_title, $axis_title, "chart_line_series");

        return $response;
    }

    public function getPeriodePariwisataHotel($idUsecase){
        $rows = $this->ekonomiRepositories->getPeriodePariwisataHotel($idUsecase);

        $response = $this->filterPeriode($rows);

        return $response;
    }

    public function getTahunPariwisataHotel($idUsecase){
        $rows = $this->ekonomiRepositories->getTahunPariwisataHotel($idUsecase);

        $response = $this->filterTahun($rows);

        return $response;
    }

    public function getMapPariwisataHotel($idUsecase, $tahun){
        $rows = $this->ekonomiRepositories->getMapPariwisataHotel($idUsecase, $tahun);

        foreach ($rows as $item) {
            $output[$item->city]["city"] = $item->city;
            $output[$item->city]["lat"] = $item->lat;
            $output[$item->city]["lon"] = $item->lon;

            $output[$item->city]["data"][] = [
                "label" => $item->jenis,
                "value" => (int) $item->data
            ];
        }

        $response = $this->mapLeaflet(array_values($output));

        return $response;
    }

    public function getBarPariwisataHotel($idUsecase, $tahun){
        $rows = $this->ekonomiRepositories->getBarPariwisataHotel($idUsecase, $tahun);

        $chart_params = [
            'x_axis_title' => 'Jenis Hotel',
            'y_axis_title' => 'Jumlah'
        ];

        $response = $this->barChart($rows, "", $chart_params);

        return $response;
    }

    public function getLinePariwisataHotel($idUsecase, $periode){
        $rows = $this->ekonomiRepositories->getLinePariwisataHotel($idUsecase, $periode);

        $axis_title = [
            'y_axis_title' => 'Jumlah',
            'x_axis_title' => 'Tahun'
        ];

        $response = $this->multiLineChart($rows, $axis_title);

        return $response;
    }

    public function getPeriodePariwisataWisatawan($idUsecase){
        $rows = $this->ekonomiRepositories->getPeriodePariwisataWisatawan($idUsecase);

        $response = $this->filterPeriode($rows);

        return $response;
    }

    public function getCardPariwisataWisatawan($idUsecase, $periode){
        $rows = $this->ekonomiRepositories->getCardPariwisataWisatawan($idUsecase, $periode);

        $total = 0;
        foreach ($rows as $item) {
            $total = $total + $item->data;
        }

        $rows[] = [
            "name"=> "Total",
            "data"=> $total
        ];

        $response = $this->getCard($rows);

        return $response;
    }

    public function getLinePariwisataWisatawan($idUsecase, $periode){
        $rows = $this->ekonomiRepositories->getLinePariwisataWisatawan($idUsecase, $periode);

        $axis_title = [
            'y_axis_title' => 'Jumlah',
            'x_axis_title' => 'Tahun'
        ];

        $response = $this->multiLineChart($rows, $axis_title);

        return $response;
    }

    public function getTahunPariwisataTPK($idUsecase){
        $rows = $this->ekonomiRepositories->getTahunPariwisataTPK($idUsecase);

        $response = $this->filterTahun($rows);

        return $response;
    }

    public function getBulanPariwisataTPK($idUsecase, $tahun){
        $rows = $this->ekonomiRepositories->getBulanPariwisataTPK($idUsecase, $tahun);

        $response = $this->filterTahun($rows);

        return $response;
    }

    public function getCardPariwisataTPK($idUsecase, $params){
        $rows = $this->ekonomiRepositories->getCardPariwisataTPK($idUsecase, $params);

        $response = $this->getCard($rows);

        return $response;
    }

    public function getLinePariwisataTPK($idUsecase, $tahun){
        $rows = $this->ekonomiRepositories->getLinePariwisataTPK($idUsecase, $tahun);

        $axis_title = [
            'y_axis_title' => 'Jumlah',
            'x_axis_title' => 'Bulan'
        ];

        $response = $this->multiLineChart($rows, $axis_title);

        return $response;
    }

    public function getPeriodePariwisataResto($idUsecase){
        $rows = $this->ekonomiRepositories->getPeriodePariwisataResto($idUsecase);

        $response = $this->filterPeriode($rows);

        return $response;
    }

    public function getTahunPariwisataResto($idUsecase){
        $rows = $this->ekonomiRepositories->getTahunPariwisataResto($idUsecase);

        $response = $this->filterTahun($rows);

        return $response;
    }

    public function getNamaDaerahPariwisataResto($idUsecase){
        $rows = $this->ekonomiRepositories->getNamaDaerahPariwisataResto($idUsecase);

        $response = $this->listNamaDaerah($rows);

        return $response;
    }

    public function getMapPariwisataResto($idUsecase, $tahun){
        $rows = $this->ekonomiRepositories->getMapPariwisataResto($idUsecase, $tahun);

        foreach ($rows as $item) {
            $output[$item->city]["city"] = $item->city;
            $output[$item->city]["lat"] = $item->lat;
            $output[$item->city]["lon"] = $item->lon;

            $output[$item->city]["data"][] = [
                "label" => 'Jumlah Restoran',
                "value" => (int)$item->data
            ];
        }

        $response = $this->mapLeaflet(array_values($output));

        return $response;
    }

    public function getLinePariwisataResto($idUsecase, $params){
        $rows = $this->ekonomiRepositories->getLinePariwisataResto($idUsecase, $params);

        $axis_title = [
            'filter' => 'Jumlah Restoran/Rumah Makan',
            'y_axis_title' => 'Jumlah',
            'x_axis_title' => 'Tahun'
        ];

        $response = $this->areaLineChart($rows, $axis_title, $axis_title, "chart_line_series");

        return $response;
    }
}
