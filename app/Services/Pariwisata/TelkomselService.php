<?php

namespace App\Services\Pariwisata;

use app\Repositories\Pariwisata\TelkomselRepositories;
use app\Repositories\Admin\UsecaseRepositories;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\ErrorResponse;

class TelkomselService
{
    protected $telkomselRepositories;
    protected $usecaseRepositories;
    public function __construct(TelkomselRepositories $telkomselRepositories, UsecaseRepositories $usecaseRepositories)
    {
        $this->telkomselRepositories = $telkomselRepositories;
        $this->usecaseRepositories = $usecaseRepositories;
    }

    public function getTripMap($data, $id_usecase) {
        $usecase = $this->usecaseRepositories->getUsecaseById($id_usecase);
        $kode_kab_kota = $usecase->kode_kab_kota;

        $data = $this->telkomselRepositories->getTripMap($data, $id_usecase, $kode_kab_kota);
        $response = [];
        foreach ($data as $key => $value) {
            $data_tampungan = [];

            $data_tampungan['city'] = $data[$key]->destination;
            $data_tampungan['period'] = $data[$key]->period;
            $data_tampungan['total'] = (int)$data[$key]->total;
            $data_tampungan['lat'] = $data[$key]->lat;
            $data_tampungan['lon'] = $data[$key]->lon;

            array_push($response, $data_tampungan);
        }
        $response_final = [
            "maps" => $response
        ];
        return $response_final;
    }

    public function getTopOrigin($data, $id_usecase) {
        $data = $this->telkomselRepositories->getTopOrigin($data, $id_usecase);
        foreach ($data as $key => $value) {
            $chart_category[] = $data[$key]->origin;
            $chart_data[] = intval($data[$key]->total);
        }

        $response = [
            "widget_data" => [
                "chart_categories" => $chart_category,
                "chart_data" => $chart_data,
                "xAxis_title" => "Kab/Kota",
                "yAxis_title" => "Jumlah Penduduk"
            ]
        ];

        return $response;
    }

    public function getTopDestination($data, $id_usecase) {
        $usecase = $this->usecaseRepositories->getUsecaseById($id_usecase);
        $kode_kab_kota = $usecase->kode_kab_kota;

        $data = $this->telkomselRepositories->getTripMap($data, $id_usecase, $kode_kab_kota);
        $chart_category = [];
        $chart_data = [];
        foreach ($data as $key => $value) {
            $chart_category[] = $data[$key]->destination;
            $chart_data[] = intval($data[$key]->total);
        }
        $response = [
            "widget_data" => [
                "chart_categories" => $chart_category,
                "chart_data" => $chart_data,
                "xAxis_title" => "Kab/Kota",
                "yAxis_title" => "Jumlah Penduduk"
            ]
        ];

        return $response;
    }

    public function getNumberOfTrips($data, $id_usecase) {
        $rows = $this->telkomselRepositories->getNumberOfTrips($data, $id_usecase);

        $categories = [];
        $data = [];

        foreach ($rows as $row) {
            $categories[] = date("M Y", strtotime($row->period));
            $data[] = (int)$row->jumlah;
        }

        $response = [
            "widget_data" => [
                "chart_categories" => $categories,
                "chart_data" => $data,
            ]
        ];

        return $response;
    }

    public function getNumberOfTripsOrigin($data, $id_usecase) {
        $rows = $this->telkomselRepositories->getNumberOfTripsOrigin($data, $id_usecase);

        $categories = $rows->pluck('period')->unique()->values();
        $series = $rows->mapToGroups(function ($item) {
            return [
                $item->origin => [
                    "tahun" => $item->period,
                    "jumlah" => (int)$item->jumlah
                ]
            ];
        });

        $data = [];
        foreach ($series as $key => $item) {
            $data[] = (object)[
                "name" => $key,
                "data" => $item->pluck('jumlah')->values(),
            ];
        }

        $response = [
            "widget_data" => [
                "chart_categories" => $categories,
                "chart_data" => $data,
            ]
        ];

        return $response;
    }

    public function getNumberOfTripsDestination($data, $id_usecase) {
        $usecase = $this->usecaseRepositories->getUsecaseById($id_usecase);
        $kode_kab_kota = $usecase->kode_kab_kota;
        $name_usecase = $usecase->nama_usecase;

        $rows = $this->telkomselRepositories->getNumberOfTripsDestination($id_usecase, $data, $name_usecase, $kode_kab_kota);
        $categories = $rows->pluck('period')->unique()->values();
        $series = $rows->mapToGroups(function ($item) {
            return [
                $item->destination => [
                    "tahun" => $item->period,
                    "jumlah" => (int)$item->jumlah
                ]
            ];
        });

        $data = [];
        foreach ($series as $key => $item) {
            $data[] = (object)[
                "name" => $key,
                "data" => $item->pluck('jumlah')->values(),
            ];
        }

        $response = [
            "widget_data" => [
                "chart_categories" => $categories,
                "chart_data" => $data,
            ]
        ];

        return $response;
    }

    public function getMovementOfTrips($data, $id_usecase) {
        $rows = $this->telkomselRepositories->getMovementOfTrips($id_usecase, $data);
        $category1 = 0;
        $category2 = 0;
        $category3 = 0;

        foreach ($rows as $row) {
            if ($row->trip_segment == 1) {
                $category1 += $row->jumlah;
            } elseif ($row->trip_segment >= 2 && $row->trip_segment <= 6) {
                $category2 += $row->jumlah;
            } elseif ($row->trip_segment >= 7) {
                $category3 += $row->jumlah;
            }
        }

        $response = [
            "categories" => ["1 Kali", "2 - 6 Kali", ">= 7 Kali"],
            "data" => [$category1, $category2, $category3]
        ];

        return $response;
    }

    public function getLengthOfStay($data, $id_usecase) {
        $rows = $this->telkomselRepositories->getLengthOfStay($id_usecase, $data);

        $category1 = 0;
        $category2 = 0;
        $category3 = 0;

        foreach ($rows as $row) {
            if ($row->los_segment == 1) {
                $category1 += $row->jumlah;
            } elseif ($row->los_segment >= 2 && $row->los_segment <= 6) {
                $category2 += $row->jumlah;
            } elseif ($row->los_segment >= 7) {
                $category3 += $row->jumlah;
            }
        }

        $response = [
            "categories" => ["1 Hari", "2 - 6 Hari", ">= 7 Hari"],
            "data" => [$category1, $category2, $category3]
        ];

        return $response;
    }

    public function getMovementOfGender($data, $id_usecase) {
        $rows = $this->telkomselRepositories->getMovementOfGender($id_usecase, $data);

        $total = $rows->sum('jumlah');
        $male = 0;
        $female = 0;

        foreach ($rows as $row) {
            if ($row->gender == "FEMALE") {
                $female = ($row->jumlah / $total) * 100;
            } elseif ($row->gender == "MALE") {
                $male = ($row->jumlah / $total) * 100;
            }
        }

        $response = [
            "categories" => ["male", "female"],
            "series" => [
                [
                    "name" => "male",
                    "data" => $male,
                ],
                [
                    "name" => "female",
                    "data" => $female,
                ],
            ]
        ];

        return $response;
    }

    public function getMovementOfAge($data, $id_usecase) {
        $rows = $this->telkomselRepositories->getMovementOfAge($id_usecase, $data);
        
        $categories = [];
        $data = [];

        foreach ($rows as $row) {
            $categories[] = $row->age_range;
            $data[] = (int)$row->jumlah;
        }

        $response = [
            "categories" => $categories,
            "data" => $data,
        ];

        return $response;
    }

    public function getStatusSES($data, $id_usecase) {
        $rows = $this->telkomselRepositories->getStatusSES($id_usecase, $data);

        $categories = [];
        $data = [];

        foreach ($rows as $row) {
            $categories[] = $row->device_ses;
            $data[] = (int)$row->jumlah;
        }

        $response = [
            "categories" => $categories,
            "data" => $data
        ];

        return $response;
    }
    
    public function getMatrixOrigin($data, $id_usecase) {
        $usecase = $this->usecaseRepositories->getUsecaseById($id_usecase);
        $kode_kab_kota = $usecase->kode_kab_kota;
        $name_usecase = $usecase->nama_usecase;

        $rows = $this->telkomselRepositories->getMatrixOrigin($id_usecase, $data['periode'], $name_usecase, $kode_kab_kota);

        $xCategories = $rows->pluck('destination')->unique()->values();
        $yCategories = $rows->pluck('parent_origin')->unique()->values();
        $data = [];

        $destinationTemp = "";
        $x = 0;
        $y = 0;

        foreach ($rows as $row) {
            if ($destinationTemp != $row->destination) {
                $destinationTemp = $row->destination;
                $x++;
                $y = 0;
            }

            $data[] = [$x - 1, $y, (int)$row->jumlah];
            $y++;
        }

        $response = [
            "xCategories" => $xCategories,
            "yCategories" => $yCategories,
            "data" => $data
        ];

        return $response;
    }

    public function getJenisWisatawan($data, $id_usecase) {
        $data = $this->telkomselRepositories->getJenisWisatawan($id_usecase, $data);

        $response = [];
        foreach ($data as $row) {
            $categories[] = $row->jenis_wisatawan;
            $total[] = (int)$row->total;
        }

        $response = [
            "categories" => $categories,
            "series" => $total
        ];

        return $response;
    }

    public function getFilterProvinsi($id_usecase) {
        $data = $this->telkomselRepositories->getFilterProvinsi($id_usecase);

        $idx = 0;
        $data_kota = [];

        foreach ($data as $key => $value) {
            array_push($data_kota, [
                "id" => ++$idx,
                "name" => $data[$key]->parent_origin
            ]);
        }

        return $data_kota;
    }

    public function getFilterKabKota($data, $id_usecase) {
        $data = $this->telkomselRepositories->getFilterKabKota($id_usecase, $data);

        $idx = 0;
        $data_kota = [];

        foreach ($data as $key => $value) {
            array_push($data_kota, [
                "id" => ++$idx,
                "name" => $data[$key]->origin
            ]);
        }

        return $data_kota;
    }

    public function getFilterTahun($id_usecase) {
        $data = $this->telkomselRepositories->getFilterTahun($id_usecase);

        foreach ($data as $key => $value) {
            $list_tahun[] = $data[$key]->tahun;
        }

        $response = [
            "years" => $list_tahun,
            "selected_years" => $list_tahun[0]
        ];

        return $response;
    }

    public function getFilterPeriode($id_usecase) {
        $data = $this->telkomselRepositories->getFilterPeriode($id_usecase);

        foreach ($data as $key => $value) {
            $list_tahun[] = $data[$key]->period;
        }

        $response = [
            "years" => $list_tahun,
            "max_year" => $list_tahun[0],
            "min_year" => $list_tahun[count($list_tahun) - 1]
        ];

        return $response;
    }

    public function getMovementTripMap($data, $id_usecase) {
        $data = $this->telkomselRepositories->getMovementTripMap($id_usecase, $data);

        foreach ($data as &$item) {
            $item->lat_origin = (float)$item->lat_origin;
            $item->lon_origin = (float)$item->lon_origin;
            $item->lat_destination = (float)$item->lat_destination;
            $item->lon_destination = (float)$item->lon_destination;
            $item->total_count_trip = (float)$item->total_count_trip;
        }

        return $data;
    }

    public function getMovementHeatMap($data, $id_usecase) {
        $usecase = $this->usecaseRepositories->getUsecaseById($id_usecase);

        $data = $this->telkomselRepositories->getMovementHeatMap($id_usecase, $usecase, $data);

        foreach ($data as &$item) {
            $item->lat = (float)$item->lat;
            $item->lon = (float)$item->lon;
            $item->total_count_trip = (float)$item->total_count_trip;
        }

        return $data;
    }

    public function getFilterSingleYear($id_usecase) {
        $rows = $this->telkomselRepositories->getFilterSingleYear($id_usecase);
        $years = [];
        foreach ($rows as $row) array_push($years, $row->tahun);

        $data = [
            "years" => $years,
            "selected_years" => $years[count($years) - 1]
        ];

        return $data;
    }

    public function getTrendJumlahPerjalanan($data, $id_usecase) {
        $params = [
            'tahun' => $data['tahun'],
            'parent_origin' => $data['parent_origin'],
            'origin' => $data['origin'],
        ];

        switch ($data['breakdown']) {
            case 'kota_asal':
                $data = $this->telkomselRepositories->trendJumlahPerjalananKotaAsal($id_usecase, $params);
                break;
            case 'kota_tujuan':
                $data = $this->telkomselRepositories->trendJumlahPerjalananKotaTujuan($id_usecase, $params);
                break;
            default:
                $data = $this->telkomselRepositories->trendJumlahPerjalananTotal($id_usecase, $params);
                break;
        }

        return $data;
    }

    public function getFilterMonth($data, $id_usecase) {
        $rows = $this->telkomselRepositories->getFilterMonth($id_usecase, $data);

        $months = [
            "01" => "Januari",
            "02" => "Febuari",
            "03" => "Maret",
            "04" => "April",
            "05" => "Mei",
            "06" => "Juni",
            "07" => "Juli",
            "08" => "Agustus",
            "09" => "September",
            "10" => "Oktober",
            "11" => "November",
            "12" => "Desember"
        ];
        $data = [];

        foreach ($rows as $row) {
            $key = substr($row->period, -2);
            array_push($data, [
                "name" => $months[$key],
                "value" => $key,
            ]);
        }

        return $data;
    }

    public function getTempatWisata($id_usecase) {
        $rows = $this->telkomselRepositories->getTempatWisata($id_usecase);

        foreach ($rows as &$row) {
            $row->tag = explode(",", $row->tag);
            $row->image = url('/') . '/storage/' . $row->image;
            $row->image_google = 'https://drive.google.com/thumbnail?id='. $row->image_google. '&sz=w1000';
        }

        return $rows;
    }

    public function getFilterDestination($data, $id_usecase) {
        $usecase = $this->usecaseRepositories->getUsecaseById($id_usecase);
        $kode_kab_kota = $usecase->kode_kab_kota;

        $data = $this->telkomselRepositories->getFilterDestination($id_usecase, $data, $kode_kab_kota);
        return $data;
    }

    public function getTrendWisataByLamaTinggal($data, $id_usecase) {
        $data = $this->telkomselRepositories->getTrendWisataByLamaTinggal($id_usecase, $data);
        return $data;
    }

    public function getJumlahWisatawan($data, $id_usecase) {
        $data = $this->telkomselRepositories->getJumlahWisatawan($id_usecase, $data);
        return $data;
    }

    public function getKelompokUsiaWisatawan($data, $id_usecase) {
        $data = $this->telkomselRepositories->getKelompokUsiaWisatawan($id_usecase, $data);
        return $data;
    }
}
