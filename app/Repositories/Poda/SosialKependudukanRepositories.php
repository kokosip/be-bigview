<?php

namespace App\Repositories\Poda;

use Exception;
use Illuminate\Support\Facades\DB;

class SosialKependudukanRepositories {

    public function getMapJumlahPenduduk($tahun, $idUsecase){
        $db = DB::table('mart_poda_social_kependudukan_map_leaflet')
            ->select('city', 'Lakilaki', 'Perempuan', 'lat', 'lon')
            ->where('tahun', $tahun)
            ->where('id_usecase', $idUsecase)
            ->get();

        return $db;
    }

    public function getPieJumlahPenduduk($tahun, $idUsecase){
        $db = DB::table('mart_poda_social_kependudukan_pie_chart')
            ->select('sumber_data', 'tahun', 'lakilaki', 'Perempuan', 'jumlah')
            ->where('tahun', $tahun)
            ->where('id_usecase', $idUsecase)
            ->get();

        return $db;
    }

    public function getBarJumlahPenduduk($tahun, $idUsecase){
        $db = DB::table('mart_poda_social_kependudukan_bar_chart')
            ->select('city', 'datacontent')
            ->where('tahun', $tahun)
            ->where('id_usecase', $idUsecase)
            ->get();

        return $db;
    }
}
