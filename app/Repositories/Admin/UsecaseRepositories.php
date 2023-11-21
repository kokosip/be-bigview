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

    public function getLatestIdUsecase(){
        $db = DB::table('usecase')
            ->selectRaw('MAX(id_usecase) as id_usecase')
            ->first();

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
        $result = DB::table('usecase')->insert($data);

        if($result){
            return $data;
        } else {
            throw new Exception('Gagal Menambahkan Role Baru.');
        }
    }
}
