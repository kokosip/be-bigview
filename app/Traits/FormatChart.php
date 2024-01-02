<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

trait FormatChart
{
    public function mapLeaflet($data) {
        $response = [
            'jumlah' => 0,
            'max_visitor' => 0,
            'maps' => $data,
        ];

        return $response;
    }

    public function pieChart($data, $tahun) {
        if (sizeof($data) > 0) {
            $lakilaki = $data[0]->lakilaki;
            $perempuan = $data[0]->Perempuan;
            $total = $data[0]->jumlah;
            $sumber = $data[0]->sumber_data;
        } else {
            $lakilaki = 0;
            $perempuan = 0;
            $total = 0;
            $sumber = '';
        }

        $response = [
            "year" => $tahun,
            "sumber_data" => $sumber,
            "total_pria" => $lakilaki,
            "total_wanita" => $perempuan,
            "total" => $total,
            "chart_data" => [
                [
                    "name" => "Laki - laki",
                    "y" => $lakilaki
                ],
                [
                    "name" => "Perempuan",
                    "y" => $perempuan
                ]
            ]
        ];

        return $response;
    }

    public function barChart($data) {
        foreach ($data as $key) {
			$chart_category[] = $data[$key]->city;
			$chart_data[] = intval($data[$key]->data);
		}

		if (substr($this->currentUser->usecase->kode_kab_kota, 2) != "00") {
			$xAxisTitle = "Kecamatan";
		} else {
			$xAxisTitle = "Kab/Kota";
		}

		$response = [
			"widget_data" => [
				"chart_categories" => $chart_category,
				"chart_data" => $chart_data,
				"xAxis_title" => $xAxisTitle,
				"yAxis_title" => "Jumlah Penduduk"
			]
		];

        return $response;
    }
}
