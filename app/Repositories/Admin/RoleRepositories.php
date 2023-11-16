<?php

namespace App\Repositories\Admin;

use Exception;
use Illuminate\Support\Facades\DB;

class RoleRepositories {

    public function insertRole($data){
        $result = DB::table('role')->insert($data);

        if($result){
            return $data;
        } else {
            throw new Exception('Gagal Menambahkan Menu Baru.');
        }
    }
}
