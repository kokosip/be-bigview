<?php

namespace App\Repositories\Admin;

use Exception;
use Illuminate\Support\Facades\DB;

class MenuRepositories {

    public function insertMenu($data){
        $result = DB::table('menu')->insert($data);

        if($result){
            return $data;
        } else {
            throw new Exception('Gagal Menambahkan Menu Baru.');
        }
    }

    public function getLatestSort($data){
        $result = DB::table('menu')->selectRaw('max(sort) as sort')
            ->where('id_parent',$data['id_parent'])->first();
        $latesSort = is_null($result->sort) ? 1 : $result->sort + 1;

        return $latesSort;
    }
}
