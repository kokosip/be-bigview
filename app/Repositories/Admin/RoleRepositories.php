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
            throw new Exception('Gagal Menambahkan Role Baru.');
        }
    }

    public function getListRole($search, $perPage){
        $db = DB::table('role')
            ->select('id_role', 'nama_role', 'level');

        if($search){
            $db = $db->where('nama_role', 'like', "%{$search}%");
        }
        $result = $db->paginate($perPage, $perPage);
        return $result;
    }

    public function getListNameRole(){
        $db = DB::table('role')
            ->select('id_role', 'nama_role')
            ->get();

        return $db;
    }

    public function getRoleById($id_role){
        $db = DB::table('role')
            ->select('id_role', 'nama_role', 'level')
            ->where('id_role', $id_role)
            ->first();

        return $db;
    }

    public function deleteRole($id_role){
        $db = DB::table('role')
            ->where('id_role', $id_role)
            ->delete();

        return $db;
    }

    public function updateRole($data, $id_role){
        $db = DB::table('role')
            ->where('id_role', $id_role)
            ->update($data);

        return $db;
    }
}
