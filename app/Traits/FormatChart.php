<?php

namespace App\Traits;

use Exception;

trait FormatChart
{
    public function filterTahun($data) {
        if(empty($data)){
            throw new Exception('Filter Tahun Tidak tersedia.');
        }

        $response = [
            'years' => $data,
            'selected_years' => $data[0]
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
            "year" => $tahun,
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

    public function barChart($data, $kode_kab_kota) {
        if(empty($data)){
            throw new Exception('Detail Data tidak tersedia.');
        }

        foreach ($data as $key) {
            $chart_category[] = $key->city;
            $chart_data[] = intval($key->data);
        }

        $level = substr($kode_kab_kota, 2) != "00" ? "Kecamatan" : "Kabupaten/Kota";

        $response = [
            "widget_data" => [
                "chart_categories" => $chart_category,
                "chart_data" => $chart_data,
                "xAxis_title" => $level,
                "yAxis_title" => "Jumlah Penduduk"
            ]
        ];

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
            foreach ($data as $key => $value) {
                $chart_categories[] = $data[$key]->chart_categories;
                $jumlah[] = $data[$key]->data;
            }

            $chart_data = $jumlah;
            $widget_type = "chart-bar";
            $widget_title = "Jumlah Penduduk Menurut Rentang Usia";
            $xAxis_title = "Rentang Usia";
            $yAxis_title = "Jumlah";
        } else {
            foreach ($data as $key => $value) {
                if (strcasecmp($data[$key]->name, 'Laki-Laki')  == 0) {
                    $chart_categories[] = $data[$key]->chart_categories;
                    $lakilaki[] = $data[$key]->data;
                } else {
                    $perempuan[] = $data[$key]->data;
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
                "year" => $tahun,
                "widget_type" => $widget_type,
                "widget_title" => $widget_title,
                "widget_data" => $chart_data,
                "xAxis_title" => $xAxis_title,
                "yAxis_title" => $yAxis_title
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
