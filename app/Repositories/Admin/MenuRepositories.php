<?php

namespace App\Repositories\Admin;

use Exception;
use App\Exceptions\ErrorResponse;
use Illuminate\Support\Facades\DB;

class MenuRepositories {

    public function insertMenu($data){
        try {
            DB::table('menu')->insert($data);
            return $data;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal menambahkan menu.');
        } 
    }

    public function getLatestSort($data){
        $result = DB::table('menu')->selectRaw('max(sort) as sort')
            ->where('id_parent',$data['id_parent'])->first();
        $latesSort = is_null($result->sort) ? 1 : $result->sort + 1;

        return $latesSort;
    }

    public function getMenuUtama($isSubmenu = null){
        try {
            $db = DB::table('menu as a')
                ->leftJoin('menu as b', 'b.id_parent', '=', 'a.id_menu')
                ->selectRaw('DISTINCT a.id_menu, a.name_menu')
                ->where('a.id_parent', 0);

            if(!is_null($isSubmenu)) $db = $db->whereRaw('b.id_menu is not null');
            $db = $db->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan menu utama.');
        } 
    }

    public function getListMenu($search, $perPage){
        try {
            $db = DB::table('menu as a')
                ->selectRaw("a.id_menu, a.name_menu, a.id_parent, IFNULL(b.name_menu, '') as parent, a.sort")
                ->leftJoin('menu as b','a.id_parent','=','b.id_menu');

            if($search){
                $db = $db->whereRaw("a.name_menu like ? OR b.name_menu like ?", ["%{$search}%", "%{$search}%"]);
            }
            $result = $db->paginate($perPage, $perPage);
            return $result;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan list menu.');
        } 
    }

    public function getMenuById($id_menu){
        try {
            $db = DB::table('menu')
                ->select('id_menu', 'name_menu', 'icon', 'link', 'id_parent', 'sort')
                ->where('id_menu', $id_menu)
                ->first();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mendapatkan menu.');
        } 
    }

    public function deleteMenu($id_menu){
        try {
            $db = DB::table('menu')
                ->where('id_menu', $id_menu)
                ->delete();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal menghapus menu.');
        } 
    }

    public function updateMenu($data, $id_menu){
        try {
            $db = DB::table('menu')
                ->where('id_menu', $id_menu)
                ->update($data);

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal memperbarui menu.');
        } 
    }

    public function listRoleMenu($data){
        try {
            $id_parent = 0;
            if(isset($data['id_parent'])){
                $id_parent = $data['id_parent'];
            }

            $db = DB::table('menu')
                ->select('id_menu', 'name_menu')
                ->where('id_parent', $id_parent)
                ->get();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil list role menu.');
        } 
    }

    public function getMenuByRole($data){
        try {
            $db = DB::table('user_menu')
                ->where('id_role', $data['id_role'])
                ->pluck('id_menu')->toArray();

            return $db;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil menu.');
        } 
    }

    public function addRoleMenu($data){
        try {
            $result = DB::table('user_menu')->insert($data);
            return $result;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal menambahkan role menu.');
        } 
    }

    public function deleteRoleMenu($data){
        try {
            $result = DB::table('user_menu')
                ->where('id_menu', $data['id_menu'])
                ->where('id_role', $data['id_role'])
                ->delete();
            return $result;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal menghapus role menu.');
        }  
    }

    public function checkRoleMenuExist($data){
        try {
            $result = DB::table('user_menu')
                ->select('id_role', 'id_menu')
                ->where('id_role', $data['id_role'])
                ->where('id_menu', $data['id_menu'])
                ->first();

            return $result ? True : False;
        } catch (Exception $e) {
            throw new ErrorResponse(type: 'Internal Server Error', message: 'Gagal mengambil data role menu.');
        } 
    }
}
