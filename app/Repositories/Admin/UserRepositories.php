<?php

namespace App\Repositories\Admin;

use Exception;
use Illuminate\Support\Facades\DB;

class UserRepositories {

    public function getListUser($search, $perPage){
        $db = DB::table('user as u')
            ->leftJoin('role as r', 'u.id_role', '=', 'r.id_role')
            ->select('u.id_usecase', 'name', 'username', 'r.nama_role');

        if($search){
            $db = $db->whereRaw("name LIKE ? or username LIKE ? 
                or name LIKE ? ", ["%{$search}%", "%{$search}%", "%{$search}%"]);
        }

        $result = $db->paginate($perPage, $perPage);
        return $result;
    }

    public function getUserById($id_user){
        $db = DB::table('user')
            ->select('id_user', 'id_usecase', 'id_role', 'name', 'username')
            ->where('id_user', $id_user)
            ->first();

        return $db;
    }

    public function insertUser($data) {
        $result = DB::table('user')->insert($data);

        if($result){
            return $data;
        } else {
            throw new Exception('Gagal Menambahkan Role Baru.');
        }
    }

    public function deleteUser($id_user){
        $db = DB::table('user')
            ->where('id_user', $id_user)
            ->delete();

        return $db;
    }
}
