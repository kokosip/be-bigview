<?php

namespace App\Repositories\Admin;

use Exception;
use App\Exceptions\ErrorResponse;
use Illuminate\Support\Facades\DB;

class UserRepositories {

    public function getListUser($search, $perPage){
        try {
            $db = DB::table('user as u')
                ->leftJoin('role as r', 'u.id_role', '=', 'r.id_role')
                ->select('u.id_usecase', 'name', 'username', 'r.nama_role');

            if($search){
                $db = $db->whereRaw("name LIKE ? or username LIKE ? 
                    or name LIKE ? ", ["%{$search}%", "%{$search}%", "%{$search}%"]);
            }

            $result = $db->paginate($perPage, $perPage);
            return $result;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil list user.');
        } 
    }

    public function getUserById($id_user){
        try {
            $db = DB::table('user')
                ->select('id_user', 'id_usecase', 'id_role', 'name', 'username')
                ->where('id_user', $id_user)
                ->first();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil user.');
        } 
    }

    public function insertUser($data) {
        try {
            DB::table('user')->insert($data);
            return $data;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal menambahkan user.');
        } 
    }

    public function deleteUser($id_user) {
        try {
            $db = DB::table('user')
                ->where('id_user', $id_user)
                ->delete();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal menghapus user.');
        } 
    }

    public function updateMenu($data, $id_user) {
        try {
            $db = DB::table('user')
                ->where('id_user', $id_user)
                ->update($data);

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal memperbarui user.');
        } 
    }

    public function getUserRole($id_user) {
        try {
            $db = DB::table('user')
                ->where('id_user', $id_user)
                ->pluck('id_role')
                ->toArray();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type:'Internal Server Error', message:'Gagal mengambil role user');
        }
    }

    public function getSubadmin($id_usecase) {
        try {
            $db = DB::table('user')
                ->where('id_usecase', $id_usecase)
                ->where('level', 2)
                ->pluck('id_user')
                ->toArray();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: $e->getMessage() . 'idUsecase: ' . $id_usecase);
        } 
    }
}
