<?php

namespace App\Traits;

use App\Exceptions\ErrorResponse;
use Carbon\Traits\ToStringFormat;

trait FormatChart
{
    public function getPeriode($params)
    {
        $periode = explode('-', $params['periode']);

        $startYear = $periode[0];
        $endYear = $periode[1];

        return [$startYear, $endYear];
    }

    public function getLowerCase($str)
    {
        return str_replace(['/', ' '], '_', strtolower(($str)));
    }

    public function filterTahun($data, $is_month = false)
    {
        if (empty($data)) {
            throw new ErrorResponse(type: 'Not Found', message: 'Filter Tahun Tidak tersedia.', statusCode: 404);
        }

        if ($is_month) {
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

    public function filterPeriode($data)
    {
        if (empty($data)) {
            throw new ErrorResponse(type: 'Not Found', message: 'Filter Periode Tidak tersedia.', statusCode: 404);
        }

        $maxYear = (int)$data->maxYear;
        $minYear = (int)$data->minYear;
        $startYear = $maxYear - 2;
        $diff = $maxYear - $minYear;

        if ($diff < 2) {
            $startYear = $maxYear - $diff;
        }

        $response = [
            "startYear" => $startYear,
            "endYear" => $maxYear,
            "minYear" => $minYear,
            "maxYear" => $maxYear,
        ];

        return $response;
    }

    public function filterMonthPeriode($data)
    {
        if (empty($data)) {
            throw new ErrorResponse(type: 'Not Found', message: 'Filter Month Tidak tersedia.', statusCode: 404);
        }

        $idx = 2;
        if (count($data) < 2) {
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

    public function listNamaDaerah($data)
    {
        if (empty($data)) {
            throw new ErrorResponse(type: 'Not Found', message: 'Daftar Nama Daerah Tidak tersedia.', statusCode: 404);
        }

        $response = [
            "nama_daerah" => $data,
        ];

        return $response;
    }

    public function listIndikator($data)
    {
        if (empty($data)) {
            throw new ErrorResponse(type: 'Not Found', message: 'Daftar Indikator Tidak tersedia.', statusCode: 404);
        }

        $response = [
            "indikator" => $data,
        ];

        return $response;
    }

    public function getCard($data)
    {
        if (empty($data)) {
            throw new ErrorResponse(type: 'Not Found', message: 'Daftar Card Tidak tersedia.', statusCode: 404);
        }

        $response = [
            "total" => $data,
        ];

        return $response;
    }

    public function mapLeaflet($data)
    {
        if (empty($data)) {
            throw new ErrorResponse(type: 'Not Found', message: 'Map Leaflet tidak tersedia.', statusCode: 404);
        }

        $response = [
            'jumlah' => 0,
            'max_visitor' => 0,
            'maps' => $data,
        ];

        return $response;
    }

    public function pieChart($data, $tahun)
    {
        if (empty($data)) {
            throw new ErrorResponse(type: 'Not Found', message: 'Pie Chart tidak tersedia.', statusCode: 404);
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

    public function barChart($data, $kode_kab_kota = "", $chart_params)
    {
        if (empty($data)) {
            throw new ErrorResponse(type: 'Not Found', message: 'Detail Data bar chart tidak tersedia.', statusCode: 404);
        }

        foreach ($data as $key) {
            $chart_category[] = $key->chart_categories;
            $chart_data[] = (float)($key->data);
        }

        if ($kode_kab_kota == "") {
            $xaxis_title = $chart_params['x_axis_title'];
        } else {
            $xaxis_title = substr($kode_kab_kota, 2) != "00" ? "Kecamatan" : "Kabupaten/Kota";
        }

        $response = [
            "widget_data" => [
                "chart_categories" => $chart_category,
                "chart_data" => $chart_data,
                "xAxis_title" => $xaxis_title,
                "yAxis_title" => $chart_params['y_axis_title']
            ]
        ];

        return $response;
    }

    public function barColumnChart($data, $chart_type, $params = null)
    {
        if (empty($data)) {
            throw new ErrorResponse(type: 'Not Found', message: 'Detail Data Column Bar chart tidak tersedia.', statusCode: 404);
        }



        foreach ($data as $key) {
            if ($chart_type == 'dual-axes') {
                if (in_array($params['name_column'], ['Indeks Harga Konsumen', 'Inflasi'])) {
                    $chart_category[] = substr($key->bulan, 0, 3) . " " . $key->tahun;
                }
                if ($key->jenis == $params['name_column']) {
                    $chart_category[] = $key->tahun;
                    $chart_data[$key->jenis]["data_column"][] = $key->data;
                    $chart_data[$key->jenis]["yAxis_title"] = $params['column_title'];
                } else {
                    $chart_category[] = $key->tahun;
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

        if ($chart_type == 'dual-axes') {
            unset($response["widget_data"]["yAxis_title"]);
        }

        return $response;
    }

    public function stackedBarChart($tahun, $data)
    {
        if (empty($data)) {
            throw new ErrorResponse(type: 'Not Found', message: 'Detail Data tidak tersedia.', statusCode: 404);
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
                ],
                [
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

    public function areaLineChart($data, $params, $axis_title, $type_chart)
    {
        if (empty($data) && empty($params)) {
            throw new ErrorResponse(type: 'Not Found', message: "Data $type_chart tidak tersedia.", statusCode: 404);
        }

        if ($data->isEmpty()) {
            throw new ErrorResponse(type: 'Not Found', message: 'Area Line Tidak tersedia.', statusCode: 404);
        }
        foreach ($data as $key) {
            $chart_category[] = $key->category;
            $chart_data[] = round($key->data, 2);
        }

        if (isset($params['filter'])) {
            $indikator_name = $params['filter'];
        } else {
            $indikator_name = $axis_title['y_axis_title'];
        }

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
                "xAxis_title" => $axis_title['x_axis_title'],
                "yAxis_title" => $axis_title['y_axis_title']
            ]

        ];

        return $response;
    }

    public function multiLineChart($data, $axis_title)
    {
        if (empty($data)) {
            throw new ErrorResponse(type: 'Not Found', message: 'Detail Data Multi Line chart tidak tersedia.', statusCode: 404);
        }

        foreach ($data as $key) {
            $chart_category[] = $key->category;
            $chart_data[$key->jenis]["name"] = $key->jenis;
            $chart_data[$key->jenis]["data"][] = $key->data;
        }

        $response = [
            "widget_type" => 'multi-line',
            "widget_data" => [
                "chart_categories" => array_values(array_unique($chart_category)),
                "chart_data" => array_values($chart_data),
                "xAxis_title" => $axis_title['x_axis_title'],
                "yAxis_title" => $axis_title['y_axis_title']
            ]
        ];

        return $response;
    }

    public function detailTable($data, $kode_kab_kota, $title, $alt_title = "")
    {
        if (empty($data)) {
            throw new ErrorResponse(type: 'Not Found', message: 'Detail Data tidak tersedia.', statusCode: 404);
        }

        if ($kode_kab_kota != "") {
            $level = substr($kode_kab_kota, 2) != "00" ? "Kecamatan" : "Kabupaten/Kota";
        } else {
            $level = $alt_title;
        }

        foreach ($data as $item) {
            $output[$item->category][$this->getLowerCase($level)] = $item->category;
            if (isset($item->category2)) {
                $output[$item->category]['month'] = $item->category2;
            }
            $output[$item->category][$this->getLowerCase($item->column)] = (float) $item->data;
        }

        $output = array_values($output);

        $response = [
            "widget_type" => "datatable-server",
            "title" => $title,
            "sumber_data" => "",
            "widget_data" => [
                "t_head" => array_map('strval', array_keys($output[0])),
                "t_body" => $output
            ]
        ];

        return $response;
    }
}
