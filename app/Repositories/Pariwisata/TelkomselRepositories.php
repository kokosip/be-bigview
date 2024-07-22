<?php

namespace App\Repositories\Pariwisata;

use App\Exceptions\ErrorResponse;
use Exception;
use Illuminate\Support\Facades\DB;

class TelkomselRepositories
{
    public function getAllPolygon() {
        try {
            $data = DB::table('geo_provinsi_kota')
                    ->select(['kode_kab_kota', 'polygon'])
                    ->whereRaw("polygon like '{\"type\":\"MultiPolygon\",\"coordinates\":[[[%'")
                    ->get();
            return $data;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil semua polygon.');
        }
    }

    public function getDataPolygon($kode_kab_kota) {
        try {
            $data = DB::table('geo_provinsi_kota')
                    ->select('polygon')
                    ->where('kode_kab_kota', $kode_kab_kota)
                    ->first();

            return $data;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message:'Gagal mengambil data polygon.');
        }
    }

    public function getLevelUsecase($id_usecase){
        try {
            /*
            * Note : Ada 3 level Daerah
            * 1. "Provinsi" -> usecase PROVINSI, parent_destination nya PROVINSI, destination nya KABUPATEN/KOTA
            * 2. "Prov-Kota" -> usecase KABUPATEN/KOTA, tetapi parent_destination nya PROVINSI, destination nya KABUPATEN/KOTA
            * 3. "Kabupaten" atau "Kota" -> usecase KABUPATEN/KOTA, tetapi parent_destination nya KABUPATEN, destination nya KECAMATAN
            */

            $data = DB::table('mart_tsel_trip')
            ->selectRaw("DISTINCT level")
            ->where('id_usecase', $id_usecase)
            ->first();

            return $data;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message:'Gagal mendapatkan level usecase.');
        }
    }


    public function GetTripMap($data, $id_usecase, $kode_kab_kota) {
        try {
            $prov_dest = str_replace("Kota ", "", str_replace("Kabupaten ", "", str_replace("Provinsi ", "", $data['destination'])));
            $prov_origin = str_replace("Kota ", "", str_replace("Kabupaten ", "", str_replace("Provinsi ", "", $data['origin'])));
            $period = $data['period'];
            $level_usecase = $this->getLevelUsecase($id_usecase);

            $query = DB::table('mart_tsel_trip')
                ->selectRaw("CASE WHEN area IS NULL THEN destination ELSE area END AS destination, period, lat, lon, SUM(count_trip) AS total")
                ->where('id_usecase', $id_usecase);

            if ($level_usecase->level != 'Prov-Kota') {
                $query = $query->where('parent_destination', 'like', "%" . $prov_dest . "%");
            } else {
                $query = $query->where('destination', 'like', "%" . $prov_dest . "%");
            }

            if (strtolower($prov_origin) != 'all' && $prov_origin != '') {
                $query = $query->where('parent_origin', 'like', "%" . $prov_origin . "%");
            }

            $data = $query->where('period', $period)
                ->groupBy(DB::raw("CASE WHEN area IS NULL THEN destination ELSE area END"), 'period', 'lat', 'lon')
                ->orderByDesc('total')
                ->get();

            return $data;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan trip map.');
        }
    }

    public function getTopOrigin($data, $id_usecase) {
        try {
            $period = $data['period'];
            $origin = $data['origin'];
            $data = [];

            $data = DB::table('mart_tsel_trip')
                    ->selectRaw("origin, period, sum(count_trip) as total")
                    ->where('id_usecase', $id_usecase)
                    ->where('period', $period);
            
            if (strtolower($origin) != 'all' && $origin != 'all') {
                $data = $data->where('parent_origin', $origin);
            }

            $data = $data->groupBy('origin', 'period')
                    ->orderByDesc('total')->get();

            return $data;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan Top Origin');
        }
    }

    public function getNumberOfTrips($data, $id_usecase) {
        try {
            $prov = explode(",", $data["provinsi"]);
            $kabkota = explode(",", $data["kab_kota"]);

            $db = DB::table("idsg.mart_tsel_trip")->selectRaw("period, sum(count_trip) jumlah")
                ->whereRaw('id_usecase = ? and tahun = ?', [$id_usecase, $data["tahun"]]);

            if ($data["provinsi"] != "all" && $data["provinsi"] != "") {
                $db = $db->whereIn('parent_origin', $prov);
            }

            if ($data["kab_kota"] != "all" && $data["kab_kota"] != "") {
                $db = $db->whereIn('origin', $kabkota);
            }

            $db = $db->groupBy('period')->get();
            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan number of trips.');
        }
    }

    public function getNumberOfTripsOrigin($data, $id_usecase) {
        try {
            $prov = explode(",", $data["provinsi"]);
            $kabkota = explode(",", $data["kab_kota"]);

            $db = DB::table("idsg.mart_tsel_trip")->selectRaw("period,origin, sum(count_trip) jumlah")
                ->whereRaw('id_usecase = ? and tahun = ?', [$id_usecase, $data["tahun"]]);

            if ($data["provinsi"] != "all" && $data["provinsi"] != "") {
                $db = $db->whereIn('parent_origin', $prov);
            }

            if ($data["kab_kota"] != "all" && $data["kab_kota"] != "") {
                $db = $db->whereIn('origin', $kabkota);
            }

            $db = $db->groupBy('period', 'origin')->orderBy('period')->get();
            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan number of trips origin.');
        }
    }

    public function getNumberOfTripsDestination($id_usecase, $data, $name_usecase, $kode_kab_kota) {
        try {
            $destination = str_replace("Kota ", "", str_replace("Kabupaten ", "", str_replace("Provinsi ", "", $name_usecase)));;
            $prov = explode(",", $data["provinsi"]);
            $kabkota = explode(",", $data["kab_kota"]);
            $level_usecase = $this->getLevelUsecase($id_usecase);

            $db = DB::table("idsg.mart_tsel_trip")->selectRaw("period, destination, sum(count_trip) jumlah");

            if ($level_usecase->level != 'Prov-Kota') {
                $db = $db->where('parent_destination', 'like', '%' . $destination . '%');
            } else {
                $db = $db->where('destination', 'like', "%" . $destination . "%");
            }

            $db = $db->whereRaw('id_usecase = ? and tahun = ?', [$id_usecase, $data["tahun"]]);

            if ($data["provinsi"] != "all" && $data["provinsi"] != "") {
                $db = $db->whereIn('parent_origin', $prov);
            }

            if ($data["kab_kota"] != "all" && $data["kab_kota"] != "") {
                $db = $db->whereIn('origin', $kabkota);
            }

            $db = $db->groupBy('period', 'destination')->orderBy('period')->get();
            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan number of trips destination');
        }
    }

    public function getMovementOfTrips($id_usecase, $data) {
        try {
            $db = DB::table("idsg.mart_tsel_profile")
                ->selectRaw("trip_segment , sum(unique_tourist) as jumlah")
                ->whereRaw('id_usecase = ? and period = ?', [$id_usecase, $data['periode']]);

            if (isset($data["origin"]) && $data["origin"] != "all" && $data["origin"] != "") {
                $db = $db->where('parent_origin', $data['origin']);
            }

            $db = $db->groupBy('trip_segment')
                ->orderBy('trip_segment')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message:'Gagal mendapatkan movement of trips.');
        }
    }

    public function getLengthOfStay($id_usecase, $data) {
        try {
            $db = DB::table("idsg.mart_tsel_profile")
                ->selectRaw("los_segment, sum(unique_tourist) as jumlah")
                ->whereRaw('id_usecase = ? and period = ?', [$id_usecase, $data['periode']]);

            if (isset($data["origin"]) && $data["origin"] != "all" && $data["origin"] != "") {
                $db = $db->where('parent_origin', $data['origin']);
            }

            $db = $db->groupBy('los_segment')
                ->orderBy('los_segment')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message:'Gagal mendapatkan length of stay.');
        }
    }

    public function getMovementOfGender($id_usecase, $data) {
        try {
            $db = DB::table("idsg.mart_tsel_profile")->selectRaw("gender, sum(unique_tourist) as jumlah")
                ->whereRaw('gender is not null and id_usecase = ? and period = ?', [$id_usecase, $data['periode']])
                ->groupBy('gender')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message:'Gagal mendapatkan movement of gender.');
        }
    }

    public function getMovementOfAge($id_usecase, $data) {
        try {
            $db = DB::table("idsg.mart_tsel_profile")->selectRaw("age, age_range, sum(unique_tourist) as jumlah")
                ->whereRaw('age is not null and id_usecase = ? and period = ?', [$id_usecase, $data['periode']])
                ->groupBy('age', 'age_range')
                ->orderBy('age')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message:'Gagal mendapatkan movement of age.');
        }
    }

    public function getStatusSES($id_usecase, $data) {
        try {
            $db = DB::table("idsg.mart_tsel_profile")->selectRaw("device_ses, sum(unique_tourist) as jumlah")
                ->whereRaw('device_ses is not null and id_usecase = ? and period = ?', [$id_usecase, $data['periode']]);

            if (isset($data["origin"]) && $data["origin"] != "all" && $data["origin"] != "") {
                $db = $db->where('parent_origin', $data['origin']);
            }

            $db = $db->groupBy('device_ses')
                ->orderBy('device_ses')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message:'Gagal mendapatkan status SES.');
        }
    }

    public function getMatrixOrigin($id_usecase, $periode, $name_usecase, $kode_kab_kota) {
        try {
            $name_usecase = str_replace("Kota ", "", str_replace("Kabupaten ", "", str_replace("Provinsi ", "", $name_usecase)));
            $level_usecase = $this->getLevelUsecase($id_usecase);

            // Hardcode for Usecase DEMO
            if ($id_usecase == 142) {
                $name_usecase = 'Gorontalo';
            }

            $db = DB::table("idsg.mart_tsel_trip")->selectRaw("CASE WHEN area is null then destination else area end as destination, parent_origin, sum(count_trip) as jumlah")
                ->whereRaw("id_usecase = ? and period = ?", [$id_usecase, $periode]);

            if ($level_usecase->level != 'Prov-Kota') {
                $db = $db->where('parent_destination', 'like', '%' . $name_usecase . '%');
            } else {
                $db = $db->where('destination', 'like', "%" . $name_usecase . "%");
            }

            $db = $db->groupBy(DB::raw("CASE WHEN area is null then destination else area end"), 'parent_origin')
                ->orderBy('destination')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message:'Gagal mendapatkan matrix origin.');
        }
    }

    public function getJenisWisatawan($id_usecase, $data) {
        try {
            $period = $data['periode'];
            $data = [];

            $data = DB::table('mart_tsel_profile_wisatawan')
                ->selectRaw("jenis_wisatawan, sum(unique_tourist) as total")
                ->where('id_usecase', $id_usecase)
                ->where('period', $period)
                ->groupBy('jenis_wisatawan')
                ->orderByDesc('total')->get();

            return $data;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message:'Gagal mendapatkan wisatawan.');
        }
    }

    public function getFilterProvinsi($id_usecase) {
        try {
            $data = [];

            $data = DB::table('mart_tsel_trip')
                ->selectRaw("DISTINCT parent_origin")
                ->where('id_usecase', $id_usecase)
                ->orderBy('parent_origin')->get();

            return $data;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message:'Gagal mendapatkan filter provinsi.');
        }
    }

    public function getFilterKabKota($id_usecase, $data) {
        try {
            $origin = $data['origin'];
            $data = [];
            $str_origin = explode(',', $origin);

            $data = DB::table('mart_tsel_trip')
                ->selectRaw("DISTINCT parent_origin, origin")
                ->where('id_usecase', $id_usecase)
                ->whereIn('parent_origin', $str_origin)
                ->orderBy('parent_origin')
                ->orderBy('origin')->get();

            return $data;

        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message: 'Gagal mendapatkan Filter Kab/Kota.');
        }
    }

    public function getFilterTahun($id_usecase) {
        try {
            $data = [];

            $data = DB::table('mart_tsel_trip')
                ->selectRaw("DISTINCT tahun")
                ->where('id_usecase', $id_usecase)
                ->orderByDesc('tahun')->get();

            return $data;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message:'Gagal Mendapatkan filter tahun.');
        }
    }

    public function getFilterPeriode($id_usecase) {
        try {
            $data = [];

            $data = DB::table('mart_tsel_trip')
                ->selectRaw("DISTINCT period")
                ->where('id_usecase', $id_usecase)
                ->orderByDesc('period')->get();

            return $data;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message:'Gagal mendapatkan filter periode');
        }
    }

    public function getMovementTripMap($id_usecase, $data) {
        try {
            $period = $data['period'];
            $origin = $data['origin'];
            $data = DB::table('mart_tsel_movement_trip_map')
                ->select(
                    'origin',
                    'destination',
                    'lat as lat_origin',
                    'lon as lon_origin',
                    'lat_destination',
                    'lon_destination',
                    'total_count_trip'
                )
                ->where('id_usecase', $id_usecase)
                ->where('period', $period);

            if (strtolower($origin) != 'all') {
                $data = $data->where('origin', $origin);
            }

            $data = $data->get();

            return $data;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message: 'Gagal mendapatkan movement trip map.');
        }
    }

    public function getMovementHeatMap($id_usecase, $data_usecase, $data) {
        try {
            $name_usecase = str_replace("Kota ", "", str_replace("Kabupaten ", "", str_replace("Provinsi ", "", $data_usecase->name_usecase)));

            $period = $data['period'];

            $data = DB::table('mart_tsel_trip')
                ->selectRaw("destination, lat, lon, sum(count_trip) as total_count_trip")
                ->where('id_usecase', $id_usecase);

            if (substr($data_usecase->kode_kab_kota, 2) == '00') {
                $data = $data->where('parent_destination', 'like', '%' . $name_usecase . '%');
            } else {
                $data = $data->where('destination', 'like', "%" . $name_usecase . "%");
            }

            $data = $data->where('period', $period)
                ->groupBy('destination', 'lat', 'lon')
                ->orderBy('destination')
                ->get();

            return $data;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message: 'Gagal mendapatkan movement heat map.');
        }
    }

    public function getFilterSingleYear($id_usecase) {
        try {
            $data = DB::table("mart_tsel_trip")->selectRaw("distinct tahun")->where('id_usecase', $id_usecase)->orderBy('tahun')->get();

            return $data;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message:'Gagal mendapatkan filter single year.');
        }
    }

    public function trendJumlahPerjalananKotaAsal($id_usecase, $params) {
        try {
            $level_usecase = $this->getLevelUsecase($id_usecase);

            $location = DB::table('usecase_government as u')
                        ->select('nama_provinsi', 'nama_kab_kota')
                        ->leftJoin('geo_provinsi_kota as gpk', function ($join) {
                            $join->on('u.kode_provinsi', '=', 'gpk.kode_provinsi')
                                ->on('u.kode_kab_kota', '=', 'gpk.kode_kab_kota');
                        })
                        ->where('id_usecase', $id_usecase)
                        ->first();

            if ($location) {
                $location = (array) $location;
            } else {
                $location = [];
            }
            
            $parent_destination = $level_usecase->level == "Kabupaten" || $level_usecase->level == "Kota" ? $location['nama_kab_kota'] : $location['nama_provinsi'];
            $destination = $location['nama_kab_kota'] ?: $location['nama_provinsi'];

            $parent_destination = str_replace("Kota ", "", str_replace("Kabupaten ", "", str_replace("Provinsi ", "", $parent_destination)));
            $destination = str_replace("Kota ", "", str_replace("Kabupaten ", "", str_replace("Provinsi ", "", $destination)));

            $query = DB::table('mart_tsel_trip')
                ->where('id_usecase', $id_usecase)
                ->where('tahun', $params['tahun']);

            if (!$params['parent_origin']) {
                $query = $query->selectRaw("period, parent_origin as location, SUM(count_trip) as total_trip")
                    ->where('parent_destination', '=', $parent_destination)
                    ->groupBy('period', 'parent_origin');
            } else {
                $query = $query->selectRaw("period, origin as location, SUM(count_trip) as total_trip")
                    ->where('destination', '=', $destination)
                    ->groupBy('period', 'origin');
            }

            if ($params['parent_origin']) $query = $query->where('parent_origin', $params['parent_origin']);
            if ($params['origin']) $query = $query->where('origin', $params['origin']);

            $rawData = $query->get();
            $groupByTahun = $rawData->groupBy('location');

            // LOGIC convertPeriodeToMonth
            $rawPeriods = $rawData->pluck('period')->unique()->values();

            $months = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
            $categories = [];

            foreach ($rawPeriods as $period) {
                $monthIndex = intval(substr($period, -2)) - 1;
                if ($monthIndex >= 0 && $monthIndex < count($months)) {
                    $categories[] = $months[$monthIndex];
                } else {
                    $categories[] = ''; 
                }
            }
            // LOGIC convertPeriodeToMonth

            $data = [];

            foreach ($groupByTahun as $key => $groups) {
                $temp = [];
                foreach ($groups as $gorup) {
                    array_push($temp, (int)$gorup->total_trip);
                }
                array_push($data, [
                    'name' => $key,
                    'data' => $temp
                ]);
            }

            return [
                "chart_categories" => $categories,
                "chart_data" => (array)$data
            ];
        } catch (Exception $e){
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil trend jumlah perjalanan kota asal.');
        }
    }

    public function trendJumlahPerjalananKotaTujuan($id_usecase, $params) {
        try {
            $level_usecase = $this->getLevelUsecase($id_usecase);

            $location = DB::table('usecase_government as u')
                        ->select('nama_provinsi', 'nama_kab_kota')
                        ->leftJoin('geo_provinsi_kota as gpk', function ($join) {
                            $join->on('u.kode_provinsi', '=', 'gpk.kode_provinsi')
                                ->on('u.kode_kab_kota', '=', 'gpk.kode_kab_kota');
                        })
                        ->where('id_usecase', $id_usecase)
                        ->first();

            if ($location) {
                $location = (array) $location;
            } else {
                $location = [];
            }

            $parent_destination = $level_usecase->level == "Kabupaten" || $level_usecase->level == "Kota" ? $location['nama_kab_kota'] : $location['nama_provinsi'];
            $destination = $location['nama_kab_kota'] ?: $location['nama_provinsi'];

            $query = DB::table('mart_tsel_trip')->selectRaw("period, CASE WHEN area is null then destination else area end as location, SUM(count_trip) as total_trip")
                ->where('id_usecase', $id_usecase)
                ->where('tahun', $params['tahun'])
                ->groupBy('period', DB::raw("CASE WHEN area is null then destination else area end"), 'destination');

            if ($level_usecase->level != "Prov-Kota") {
                $query = $query->where('parent_destination', '=', $parent_destination);
            } else {
                $query = $query->where('destination', '=', $destination);
            }

            if ($params['parent_origin']) $query = $query->where('parent_origin', $params['parent_origin']);
            if ($params['origin']) $query = $query->where('origin', $params['origin']);

            $rawData = $query->get();
            $groupByTahun = $rawData->groupBy('location');
            
            // LOGIC convertPeriodeToMonth
            $rawPeriods = $rawData->pluck('period')->unique()->values();

            $months = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
            $categories = [];

            foreach ($rawPeriods as $period) {
                $monthIndex = intval(substr($period, -2)) - 1;
                if ($monthIndex >= 0 && $monthIndex < count($months)) {
                    $categories[] = $months[$monthIndex];
                } else {
                    $categories[] = ''; 
                }
            }
            // LOGIC convertPeriodeToMonth

            $data = [];

            foreach ($groupByTahun as $key => $groups) {
                $temp = [];
                foreach ($groups as $gorup) {
                    array_push($temp, (int)$gorup->total_trip);
                }
                array_push($data, [
                    'name' => $key,
                    'data' => $temp
                ]);
            }

            return [
                "chart_categories" => $categories,
                "chart_data" => (array)$data
            ];
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message: 'Gagal mendapatkan trend jumlah perjalanan kota tujuan.');
        }
    }

    public function trendJumlahPerjalananTotal($id_usecase, $params) {
        try {
            $level_usecase = $this->getLevelUsecase($id_usecase);

            $location = DB::table('usecase_government as u')
                        ->select('nama_provinsi', 'nama_kab_kota')
                        ->leftJoin('geo_provinsi_kota as gpk', function ($join) {
                            $join->on('u.kode_provinsi', '=', 'gpk.kode_provinsi')
                                ->on('u.kode_kab_kota', '=', 'gpk.kode_kab_kota');
                        })
                        ->where('id_usecase', $id_usecase)
                        ->first();

            if ($location) {
                $location = (array) $location;
            } else {
                $location = [];
            }

            $parent_destination = $level_usecase->level == "Kabupaten" || $level_usecase->level == "Kota" ? $location['nama_kab_kota'] : $location['nama_provinsi'];
            $destination = $location['nama_kab_kota'] ?: $location['nama_provinsi'];

            $parent_destination = str_replace("Kota ", "", str_replace("Kabupaten ", "", str_replace("Provinsi ", "", $parent_destination)));
            $destination = str_replace("Kota ", "", str_replace("Kabupaten ", "", str_replace("Provinsi ", "", $destination)));

            $query1 = DB::table('mart_tsel_trip')->selectRaw("period, tahun, SUM(count_trip) as total_trip")
                ->where('id_usecase', $id_usecase)
                ->where('tahun', $params['tahun'])
                ->groupBy('period', 'tahun');

            $query2 = DB::table('mart_tsel_trip')->selectRaw("period, tahun, SUM(count_trip) as total_trip")
                ->where('id_usecase', $id_usecase)
                ->where('tahun', $params['tahun'] - 1)
                ->groupBy('period', 'tahun');

            if ($level_usecase->level != "Prov-Kota") {
                $query1 = $query1->where('parent_destination', '=', $parent_destination);
                $query2 = $query2->where('parent_destination', '=', $parent_destination);
            } else {
                $query1 = $query1->where('destination', '=', $destination);
                $query2 = $query2->where('destination', '=', $destination);
            }

            if ($params['parent_origin']) {
                $query1 = $query1->where('parent_origin', $params['parent_origin']);
                $query2 = $query2->where('parent_origin', $params['parent_origin']);
            }
            if ($params['origin']) {
                $query1 = $query1->where('origin', $params['origin']);
                $query2 = $query2->where('origin', $params['origin']);
            }

            $rawData = $query1->unionAll($query2)->get();
            $groupByTahun = $rawData->groupBy('tahun');

            // LOGIC convertPeriodeToMonth
            $rawPeriods = $rawData->pluck('period')->unique()->values();

            $months = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
            $categories = [];

            foreach ($rawPeriods as $period) {
                $monthIndex = intval(substr($period, -2)) - 1;
                if ($monthIndex >= 0 && $monthIndex < count($months)) {
                    $categories[] = $months[$monthIndex];
                } else {
                    $categories[] = ''; 
                }
            }
            // LOGIC convertPeriodeToMonth
            $data = [];

            foreach ($groupByTahun as $key => $groups) {
                $temp = [];
                foreach ($groups as $gorup) {
                    array_push($temp, (int)$gorup->total_trip);
                }
                array_push($data, [
                    'name' => $key,
                    'data' => $temp
                ]);
            }
            return [
                "chart_categories" => $categories,
                "chart_data" => (array)$data
            ];

        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message: 'Gagal mendapatkan jumlah perjalanan total');
        }
    }

    public function getFilterMonth($id_usecase, $data) {
        try {
            $data = DB::table("mart_tsel_trip")
                    ->selectRaw("distinct period, tahun")
                    ->where('id_usecase', $id_usecase)
                    ->where('tahun', $data['tahun'])
                    ->orderBy('tahun')
                    ->get();
        
            return $data;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message: 'Gagal mendapatkan filter month.');
        }
    }

    public function getTempatWisata($id_usecase) {
        try {
            $data = DB::table('mart_tsel_destination')
                    ->select('parent_destination', 'nama_lokasi', 'tag', 'image', 'image_google', 'keterangan')
                    ->leftJoin('mart_deskripsi_tujuanwisata', 'mart_tsel_destination.destination', '=', 'mart_deskripsi_tujuanwisata.nama_lokasi')
                    ->where('mart_tsel_destination.id_usecase', $id_usecase)
                    ->whereNotNull('nama_lokasi')
                    ->get();
            return $data;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message: 'Gagal mendapatkan tempat wisata.');
        }
    }

    public function getFilterDestination($id_usecase, $data, $kode_kab_kota) {
        try {
            $level = substr($kode_kab_kota, -2) == "00" ? "provinsi" : "kab_kota";
            $location = DB::table('usecase_government as u')
                        ->select('nama_provinsi', 'nama_kab_kota')
                        ->leftJoin('geo_provinsi_kota as gpk', function ($join) {
                            $join->on('u.kode_provinsi', '=', 'gpk.kode_provinsi')
                                ->on('u.kode_kab_kota', '=', 'gpk.kode_kab_kota');
                        })
                        ->where('id_usecase', $id_usecase)
                        ->first();

            if ($location) {
                $location = (array) $location;
            } else {
                $location = [];
            }

            $parent_destination = $location['nama_provinsi'];
            $destination = $location['nama_kab_kota'] ?: $location['nama_provinsi'];

            $parent_destination = str_replace("Kota ", "", str_replace("Kabupaten ", "", str_replace("Provinsi ", "", $parent_destination)));
            $destination = str_replace("Kota ", "", str_replace("Kabupaten ", "", str_replace("Provinsi ", "", $destination)));

            $db = DB::table("mart_tsel_profile")->selectRaw("distinct destination")
                ->where("id_usecase", $id_usecase)
                ->where('tahun', $data['tahun']);

            if ($level == "provinsi") {
                $db = $db->where('parent_destination', '=', $parent_destination);
            } else {
                $db = $db->where('destination', '=', $destination);
            }
        
            $rows = $db->get();
            $data = $rows->pluck('destination')->unique()->values();

            return $data;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message: 'Gagal mendapatkan filter destination');
        }
    }

    public function getTrendWisataByLamaTinggal($id_usecase, $data) {
        try {
            $level_usecase = $this->getLevelUsecase($id_usecase);

            $location = DB::table('usecase_government as u')
                        ->select('nama_provinsi', 'nama_kab_kota')
                        ->leftJoin('geo_provinsi_kota as gpk', function ($join) {
                            $join->on('u.kode_provinsi', '=', 'gpk.kode_provinsi')
                                ->on('u.kode_kab_kota', '=', 'gpk.kode_kab_kota');
                        })
                        ->where('id_usecase', $id_usecase)
                        ->first();

            if ($location) {
                $location = (array) $location;
            } else {
                $location = [];
            }

            $parent_destination = $parent_destination = $level_usecase->level == "Kabupaten" || $level_usecase->level == "Kota" ? $location['nama_kab_kota'] : $location['nama_provinsi'];
            $destination = $location['nama_kab_kota'] ?: $location['nama_provinsi'];

            $parent_destination = str_replace("Kota ", "", str_replace("Kabupaten ", "", str_replace("Provinsi ", "", $parent_destination)));
            $destination = str_replace("Kota ", "", str_replace("Kabupaten ", "", str_replace("Provinsi ", "", $destination)));

            $db = DB::table("mart_tsel_profile")->selectRaw("period, los_segment, count(los_segment) as total")
                ->where("id_usecase", $id_usecase)
                ->where('tahun', $data['tahun'])
                ->groupBy('period', 'los_segment');

            if ($level_usecase->level != "Prov-Kota") {
                $db = $db->where('parent_destination', '=', $parent_destination);
            } else {
                $db = $db->where('destination', '=', $destination);
            }

            if ($data['destination']) {
                $db = $db->where('destination', $data['destination']);
            }

            $rows = $db->get();
            $groups = $rows->groupBy('los_segment');
            
            // LOGIC convertPeriodeToMonth
            $rawPeriods = $rows->pluck('period')->unique()->values();

            $months = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
            $categories = [];

            foreach ($rawPeriods as $period) {
                $monthIndex = intval(substr($period, -2)) - 1;
                if ($monthIndex >= 0 && $monthIndex < count($months)) {
                    $categories[] = $months[$monthIndex];
                } else {
                    $categories[] = ''; 
                }
            }
            // LOGIC convertPeriodeToMonth

            $data = [];

            foreach ($groups as $key => $group) {
                $temp = [];
                foreach ($group as $g) {
                    array_push($temp, (int)$g->total);
                }
                array_push($data, [
                    'name' => ($key == 1) ? "1 Hari" : (($key == 2) ? "2-6 Hari" : ">7 Hari"),
                    'data' => $temp
                ]);
            }

            return ([
                "chart_categories" => $categories,
                "chart_data" => (array)$data
            ]);
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan trend wisata by lama tinggal.');
        }
    }

    public function getJumlahWisatawan($id_usecase, $data) {
        try{
            $level_usecase = $this->getLevelUsecase($id_usecase);

            $location = DB::table('usecase_government as u')
                        ->select('nama_provinsi', 'nama_kab_kota')
                        ->leftJoin('geo_provinsi_kota as gpk', function ($join) {
                            $join->on('u.kode_provinsi', '=', 'gpk.kode_provinsi')
                                ->on('u.kode_kab_kota', '=', 'gpk.kode_kab_kota');
                        })
                        ->where('id_usecase', $id_usecase)
                        ->first();

            if ($location) {
                $location = (array) $location;
            } else {
                $location = [];
            }

            $parent_destination = $parent_destination = $level_usecase->level == "Kabupaten" || $level_usecase->level == "Kota" ? $location['nama_kab_kota'] : $location['nama_provinsi'];
            $destination = $location['nama_kab_kota'] ?: $location['nama_provinsi'];

            $parent_destination = str_replace("Kota ", "", str_replace("Kabupaten ", "", str_replace("Provinsi ", "", $parent_destination)));
            $destination = str_replace("Kota ", "", str_replace("Kabupaten ", "", str_replace("Provinsi ", "", $destination)));

            $db = DB::table("mart_tsel_profile")->selectRaw("gender, count(gender) value")
                ->where("id_usecase", $id_usecase)
                ->where('tahun', $data['tahun'])
                ->whereNotNull('age')
                ->groupBy('gender');

            if ($level_usecase->level != "Prov-Kota") {
                $db = $db->where('parent_destination', '=', $parent_destination);
            } else {
                $db = $db->where('destination', '=', $destination);
            }

            if ($data['destination']) {
                $db = $db->where('destination', $data['destination']);
            }
    
            $rows = $db->get();

            $total = $rows->sum('value');
            $data = [];

            foreach ($rows as $row) {
                $data[] = [
                    "name" => $row->gender,
                    "value" => $row->value,
                    "percentage" => round(($row->value / $total) * 100, 2)
                ];
            }
            return $data;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message:'Gagal mendapatkan jumlah wisatawan.');
        }
    }

    public function getKelompokUsiaWisatawan($id_usecase, $data) {
        try {
            $level_usecase = $this->getLevelUsecase($id_usecase);

            $location = DB::table('usecase_government as u')
                        ->select('nama_provinsi', 'nama_kab_kota')
                        ->leftJoin('geo_provinsi_kota as gpk', function ($join) {
                            $join->on('u.kode_provinsi', '=', 'gpk.kode_provinsi')
                                ->on('u.kode_kab_kota', '=', 'gpk.kode_kab_kota');
                        })
                        ->where('id_usecase', $id_usecase)
                        ->first();

            if ($location) {
                $location = (array) $location;
            } else {
                $location = [];
            }

            $parent_destination = $parent_destination = $level_usecase->level == "Kabupaten" || $level_usecase->level == "Kota" ? $location['nama_kab_kota'] : $location['nama_provinsi'];
            $destination = $location['nama_kab_kota'] ?: $location['nama_provinsi'];

            $parent_destination = str_replace("Kota ", "", str_replace("Kabupaten ", "", str_replace("Provinsi ", "", $parent_destination)));
            $destination = str_replace("Kota ", "", str_replace("Kabupaten ", "", str_replace("Provinsi ", "", $destination)));

            $db = DB::table("mart_tsel_profile")->selectRaw("age,age_range, sum(unique_tourist) as total")
                ->where("id_usecase", $id_usecase)
                ->where('tahun', $data['tahun'])
                ->whereNotNull('age')
                ->groupBy('age', 'age_range');

            if ($level_usecase->level != "Prov-Kota") {
                $db = $db->where('parent_destination', '=', $parent_destination);
            } else {
                $db = $db->where('destination', '=', $destination);
            }

            if ($data['destination']) {
                $db = $db->where('destination', $data['destination']);
            }
    
            $rows = $db->orderBy('age')->get();
            $totalValue = $rows->sum('total');

            $categories = [];
            $data = [];

            foreach ($rows as $row) {
                array_push($categories, $row->age_range);
                array_push($data, round(($row->total / $totalValue) * 100, 2));
            }
    
            return ([
                "chart_categories" => $categories,
                "chart_data" => $data
            ]);
    
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil kelompok usia wisatawan.');
        }
    }
}