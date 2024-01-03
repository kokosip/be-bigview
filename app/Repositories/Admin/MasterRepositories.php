<?php

namespace App\Repositories\Admin;

use Exception;
use Illuminate\Support\Facades\DB;

class MasterRepositories {

    public function getListProvinsi(){
        $db = DB::table('provinsi_kota')
            ->selectRaw("DISTINCT kode_provinsi, nama_provinsi")
            ->orderBy('nama_provinsi')
            ->get();

        return $db;
    }

    public function getListKabkota($id_prov){
        $db = DB::table('provinsi_kota')
            ->select('kode_provinsi','kode_kab_kota', 'nama_kab_kota')
            ->where('kode_provinsi', $id_prov)
            ->orderBy('nama_kab_kota')
            ->get();

        return $db;
    }

    public function getKodeKabkota($id_usecase){
        $db = DB::table('usecase_government')
            ->select('kode_kab_kota')
            ->where('id_usecase', $id_usecase)
            ->first();

        return $db;
    }
}
