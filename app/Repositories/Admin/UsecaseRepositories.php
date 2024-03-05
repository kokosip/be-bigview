<?php

namespace App\Repositories\Admin;

use Exception;
use Illuminate\Support\Facades\DB;

class UsecaseRepositories {

    public function getListUsecase($search, $perPage){
        $db = DB::table('usecase')
            ->select('id_usecase', 'name_usecase', 'type_dashboard', 'base_color1', 'base_color2', 'base_color3', 'base_color4');

        if($search){
            $db = $db->whereRaw("name_usecase LIKE ? ", ["%{$search}%"]);
        }

        $result = $db->paginate($perPage, $perPage);
        return $result;
    }

    public function getListNameUsecase(){
        $db = DB::table('usecase')
            ->select('id_usecase', 'name_usecase')
            ->get();

        return $db;
    }

    public function addUsecaseGovernment($data){
        $result = DB::table('usecase_government')->insert($data);

        if($result){
            return $result;
        } else {
            throw new Exception('Gagal Menambahkan Usecase Pemerintah Baru.');
        }
    }

    public function addUsecaseCustom($data){
        $result = DB::table('usecase_custom')->insert($data);

        if($result){
            return $result;
        } else {
            throw new Exception('Gagal Menambahkan Usecase Custom Baru.');
        }
    }

    public function addUsecase($data){
        $result = DB::table('usecase')->insertGetId($data);
        if($result){
            return $result;
        } else {
            throw new Exception('Gagal Menambahkan Usecase Baru.');
        }
    }

    public function updateUsecase($data, $id_usecase){
        $db = DB::table('usecase')
            ->where('id_usecase', $id_usecase)
            ->update($data);

        return $db;
    }

    public function updateUsecaseGovern($data, $id_usecase){
        $db = DB::table('usecase_government')
            ->where('id_usecase', $id_usecase)
            ->update($data);

        return $db;
    }

    public function updateUsecaseCustom($data, $id_usecase){
        $db = DB::table('usecase_custom')
            ->where('id_usecase', $id_usecase)
            ->update($data);

        return $db;
    }

    public function getUsecaseById($id_usecase){
        $db = DB::table('usecase as u')
            ->leftJoin('usecase_government as ug', 'u.id_usecase', '=', 'ug.id_usecase')
            ->leftJoin('usecase_custom as uc', 'u.id_usecase', '=', 'uc.id_usecase')
            ->selectRaw("u.*, ug.kode_provinsi, ug.kode_kab_kota, uc.deskripsi, if(type_dashboard = 'Government', ug.pic_logo, uc.pic_logo) as logo")
            ->where('u.id_usecase', $id_usecase)
            ->first();

        return $db;
    }

    public function deleteUsecase($id_usecase){
        $db = DB::table('usecase')
            ->where('id_usecase', $id_usecase)
            ->delete();

        return $db;
    }

    public function deleteUsecaseGovern($id_usecase){
        $db = DB::table('usecase_government')
            ->where('id_usecase', $id_usecase)
            ->delete();

        return $db;
    }

    public function deleteUsecaseCustom($id_usecase){
        $db = DB::table('usecase_custom')
            ->where('id_usecase', $id_usecase)
            ->delete();

        return $db;
    }
}
