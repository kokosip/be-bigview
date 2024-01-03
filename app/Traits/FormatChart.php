<?php

namespace App\Traits;

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

    public function barChart($data, $kode_kab_kota) {
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
}
