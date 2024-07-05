<?php

namespace App\Repositories\Admin;

use Exception;
use App\Exceptions\ErrorResponse;
use Illuminate\Support\Facades\DB;

class MasterRepositories {

    public function getListProvinsi(){
        try {
            $db = DB::table('provinsi_kota')
                ->selectRaw("DISTINCT kode_provinsi, nama_provinsi")
                ->orderBy('nama_provinsi')
                ->get();
    
            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil list provinsi.');
        }
    }

    public function getListKabkota($id_prov){
        try {
            $db = DB::table('provinsi_kota')
                ->select('kode_provinsi','kode_kab_kota', 'nama_kab_kota')
                ->where('kode_provinsi', $id_prov)
                ->orderBy('nama_kab_kota')
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil list Kabupaten/Kota.');
        }
    }

    public function getKodeKabkota($id_usecase){
        try {
            $db = DB::table('usecase_government')
                ->select('kode_kab_kota')
                ->where('id_usecase', $id_usecase)
                ->first();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil kode Kabupaten/Kota.');
        }
    }
}
