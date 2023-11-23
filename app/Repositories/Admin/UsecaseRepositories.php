<?php

namespace App\Repositories\Admin;

use Exception;
use Illuminate\Support\Facades\DB;

class UsecaseRepositories {

    public function getListUsecase($search, $perPage){
        $db = DB::table('usecase')
            ->select('id_usecase', 'name_usecase', 'base_color1', 'base_color2', 'base_color3', 'base_color4');

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
            throw new Exception('Gagal Menambahkan Role Baru.');
        }
    }

    public function addUsecaseCustom($data){
        $result = DB::table('usecase_custom')->insert($data);

        if($result){
            return $result;
        } else {
            throw new Exception('Gagal Menambahkan Role Baru.');
        }
    }

    public function addUsecase($data){
        $result = DB::table('usecase')->insertGetId($data);
        if($result){
            return $result;
        } else {
            throw new Exception('Gagal Menambahkan Role Baru.');
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
        $db = DB::table('usecase')
            ->select('id_usecase', 'name_usecase', 'type_dashboard', 'base_color1', 'base_color2', 'base_color3', 'base_color4')
            ->where('id_usecase', $id_usecase)
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
