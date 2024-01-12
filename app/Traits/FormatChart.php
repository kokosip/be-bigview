<?php

namespace App\Traits;

use Exception;

trait FormatChart
{
    public function filterTahun($data, $is_month = false) {
        if(empty($data)){
            throw new Exception('Filter Tahun Tidak tersedia.');
        }

        if($is_month){
            $response = [
                'month' => $data,
                'selected_month' => $data[count($data) - 1]
            ];
        } else {
            $response = [
                'years' => $data,
                'selected_years' => $data[0]
            ];
        }

        return $response;
    }

    public function filterPeriode($data) {
        if(empty($data)){
            throw new Exception('Filter Periode Tidak tersedia.');
        }

        $maxYear = (int)$data->maxYear;
        $minYear = (int)$data->minYear;
        $startYear = $maxYear - 2;
        $selisih = $maxYear - $minYear;
        if ($selisih < 2) {
            $startYear = $maxYear - $selisih;
        }

        $response = [
            "startYear" => $startYear,
            "endYear" => $maxYear,
            "minYear" => $minYear,
            "maxYear" => $maxYear,
        ];

        return $response;
    }

    public function filterMonthPeriode($data){
        if(empty($data)){
            throw new Exception('Filter Periode Tidak tersedia.');
        }

        $idx = 2;
        if(count($data) < 2){
            $idx = count($data) - 1;
        }

        $startYear = date('Y', strtotime($data[$idx]->id_bulan));
        $endYear = date('Y', strtotime($data[0]->id_bulan));
        $minYear = date('Y', strtotime($data[count($data) - 1]->id_bulan));
        $startMonth = date('m', strtotime($data[$idx]->id_bulan));
        $endMonth = date('m', strtotime($data[0]->id_bulan));

        $response = [
            'startYear' => $startYear,
            'endYear' => $endYear,
            'minYear' => $minYear,
            'maxYear' => $endYear,
            'months' => true,
            'startMonth' => (int)$startMonth,
            'endMonth' => (int)$endMonth
        ];

        return $response;
    }

    public function listNamaDaerah($data) {
        if(empty($data)){
            throw new Exception('Daftar Nama Daerah Tidak tersedia.');
        }

        $response = [
            "nama_daerah" => $data,
        ];

        return $response;
    }

    public function listIndikator($data) {
        if(empty($data)){
            throw new Exception('Daftar Indikator Tidak tersedia.');
        }

        $response = [
            "indikator" => $data,
        ];

        return $response;
    }

    public function getCard($data) {
        if(empty($data)){
            throw new Exception('Daftar Indikator Tidak tersedia.');
        }

        $response = [
            "total" => $data,
        ];

        return $response;
    }

    public function mapLeaflet($data) {
        if(empty($data)){
            throw new Exception('Detail Data tidak tersedia.');
        }

        $response = [
            'jumlah' => 0,
            'max_visitor' => 0,
            'maps' => $data,
        ];

        return $response;
    }

    public function pieChart($data, $tahun) {
        if(empty($data)){
            throw new Exception('Detail Data tidak tersedia.');
        }

        $response = [
            "year" => $tahun['tahun'],
            "sumber_data" => $data->sumber_data,
            "total_pria" => $data->lakilaki,
            "total_wanita" => $data->Perempuan,
            "total" => $data->jumlah,
            "chart_data" => [
                [
                    "name" => "Laki - laki",
                    "y" => $data->lakilaki
                ],
                [
                    "name" => "Perempuan",
                    "y" => $data->Perempuan
                ]
            ]
        ];

        return $response;
    }

    public function barChart($data, $kode_kab_kota = "", $yaxis_title = "") {
        if(empty($data)){
            throw new Exception('Detail Data bar chart tidak tersedia.');
        }

        foreach ($data as $key) {
            $chart_category[] = $key->chart_categories;
            $chart_data[] = (float)($key->data);
        }

        if($kode_kab_kota == ""){
            $xaxis_title = "Jenis Pekerjaan";
        } else {
            $xaxis_title = substr($kode_kab_kota, 2) != "00" ? "Kecamatan" : "Kabupaten/Kota";
        }

        $response = [
            "widget_data" => [
                "chart_categories" => $chart_category,
                "chart_data" => $chart_data,
                "xAxis_title" => $xaxis_title,
                "yAxis_title" => $yaxis_title
            ]
        ];

        return $response;
    }

    public function barColumnChart($data, $chart_type, $params = null) {
        if(empty($data)){
            throw new Exception('Detail Data Column Bar chart tidak tersedia.');
        }

        foreach ($data as $key) {
            if($chart_type == 'dual-axes'){
                if(in_array($params['name_column'], ['Indeks Harga Konsumen', 'Inflasi'])){
                    $chart_category[] = substr($key->bulan, 0, 3)." ".$key->tahun;
                }
                if($key->jenis == $params['name_column']){
                    $chart_data[$key->jenis]["data_column"][] = $key->data;
                    $chart_data[$key->jenis]["yAxis_title"] = $params['column_title'];
                } else {
                    $chart_data[$key->jenis]["data_spline"][] = $key->data;
                    $chart_data[$key->jenis]["yAxis_title"] = $params['line_title'];
                }
            } else {
                $chart_category[] = $key->tahun;
                $chart_data[$key->jenis]['nama'] = $key->jenis;
                $chart_data[$key->jenis]["data"][] = $key->data;
            }
        }

        $response = [
            "widget_type" => $chart_type,
            "widget_data" => [
                "chart_categories" => array_unique($chart_category),
                "chart_data" => array_values($chart_data),
                "xAxis_title" => "Tahun",
                "yAxis_title" => "Jumlah"
            ]
        ];

        if($chart_type == 'dual-axes'){
            unset($response["widget_data"]["yAxis_title"]);
        }

        return $response;
    }

    public function stackedBarChart($tahun, $data) {
        if(empty($data)){
            throw new Exception('Detail Data tidak tersedia.');
        }

        $widget_type = "chart-with-negatif-bar";
        $widget_title = "Rentang Usia Menurut Jenis Kelamin";
        $xAxis_title = "Jenis Kelamin";
        $yAxis_title = "Jumlah";

        if ($data[0]->name == "Jumlah") {
            foreach ($data as $key) {
                $chart_categories[] = $key->chart_categories;
                $jumlah[] = $key->data;
            }

            $chart_data = $jumlah;
            $widget_type = "chart-bar";
            $widget_title = "Jumlah Penduduk Menurut Rentang Usia";
            $xAxis_title = "Rentang Usia";
            $yAxis_title = "Jumlah";
        } else {
            foreach ($data as $key) {
                if (strcasecmp($key->name, 'Laki-Laki')  == 0) {
                    $chart_categories[] = $key->chart_categories;
                    $lakilaki[] = $key->data;
                } else {
                    $perempuan[] = $key->data;
                }
            }

            $chart_data = [
                [
                    "name" => "Laki-laki",
                    "data" => $lakilaki
                ], [
                    "name" => "Perempuan",
                    "data" => $perempuan
                ]
            ];
        }

        $response = [
                "year" => $tahun['tahun'],
                "widget_type" => $widget_type,
                "widget_title" => $widget_title,
                "widget_data" => $chart_data,
                "xAxis_title" => $xAxis_title,
                "yAxis_title" => $yAxis_title
            ];

        return $response;
    }

    public function areaLineChart($data, $params, $yaxis_title, $type_chart){
        if(empty($data) && empty($params)){
            throw new Exception("Data $type_chart tidak tersedia.");
        }

        foreach ($data as $key) {
            $chart_category[] = $key->category;
            $chart_data[] = round($key->data, 2);
        }

        $indikator_name = $params['filter'];

        $chart_series[] = [
            'name' => $indikator_name,
            'data' => $chart_data
        ];

        $chart = $indikator_name . " Chart";
        $response = [
            'widget_type' => $type_chart,
            'widget_title' => $chart,
            'widget_subtitle' => "",
            'widget_data' => [
                "chart_categories" => $chart_category,
                "chart_series" => $chart_series,
                "xAxis_title" => "Tahun",
                "yAxis_title" => $yaxis_title
            ]

        ];

        return $response;
    }

    public function tableDetail($data, $kode_kab_kota, $title) {
        if(empty($data)){
            throw new Exception('Detail Data tidak tersedia.');
        }

        foreach ($data as $key) {
            $data_tmp = [];

            $data_tmp['daerah'] = $key->city;
            $data_tmp['laki_laki'] = $key->Lakilaki;
            $data_tmp['perempuan'] = $key->Perempuan;
            $data_tmp['total'] = $key->jumlah;

            $data_final[] = $data_tmp;
        }

        $level = substr($kode_kab_kota, 2) != "00" ? "Kecamatan" : "Kabupaten/Kota";

        $response = [
            "widget_type"=> "datatable-server",
            "title"=> $title,
            "sumber_data"=> $data[0]->sumber,
            "widget_data"=> [
                "order_col"=> [
                    "daerah",
                    "laki_laki",
                    "perempuan",
                    "total"
                ],
                "t_head"=> [
                    "daerah" => $level,
                    "laki_laki" => "Laki-laki",
                    "perempuan" => "Perempuan",
                    "total" => "Total"
                ],
                "t_body"=> $data_final
            ]
        ];

        return $response;
    }
}
