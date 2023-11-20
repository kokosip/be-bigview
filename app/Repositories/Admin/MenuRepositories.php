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

    public function getMenuUtama(){
        $result = DB::table('menu')->selectRaw('DISTINCT id_menu, name_menu')
            ->where('id_parent', 0)->get();

        return $result;
    }

    public function getListMenu($search, $perPage){
        $db = DB::table('menu as a')
            ->selectRaw("a.id_menu, a.name_menu, a.id_parent, IFNULL(b.name_menu, '') as parent, a.sort")
            ->leftJoin('menu as b','a.id_parent','=','b.id_menu');

        if($search){
            $db = $db->whereRaw("a.name_menu like ? OR b.name_menu like ?", ["%{$search}%", "%{$search}%"]);
        }
        $result = $db->paginate($perPage, $perPage);
        return $result;
    }

    public function getMenuById($id_menu){
        $db = DB::table('menu')
            ->select('id_menu', 'name_menu', 'icon', 'link', 'id_parent', 'sort')
            ->where('id_menu', $id_menu)
            ->first();

        return $db;
    }

    public function deleteMenu($id_menu){
        $db = DB::table('menu')
            ->where('id_menu', $id_menu)
            ->delete();

        return $db;
    }

    public function updateMenu($data, $id_menu){
        $db = DB::table('menu')
            ->where('id_menu', $id_menu)
            ->update($data);

        return $db;
    }

    public function listRoleMenu($data){
        $id_parent = 0;
        if(isset($data['id_parent'])){
            $id_parent = $data['id_parent'];
        }

        $db = DB::table('menu')
            ->select('id_menu', 'name_menu')
            ->where('id_parent', $id_parent)
            ->get();

        return $db;
    }

    public function getMenuByRole($data){
        $db = DB::table('user_menu')
            ->where('id_role', $data['id_role'])
            ->pluck('id_menu')->toArray();

        return $db;
    }

    public function addRoleMenu($data){
        $result = DB::table('user_menu')->insert($data);

        if($result){
            return $result;
        } else {
            throw new Exception('Gagal Menambahkan Role Menu Baru.');
        }
    }

    public function deleteRoleMenu($data){
        $result = DB::table('user_menu')
            ->where('id_menu', $data['id_menu'])
            ->where('id_role', $data['id_role'])
            ->delete();

        if($result){
            return $result;
        } else {
            throw new Exception('Gagal Menghapus Role Menu Baru.');
        }
    }

    public function checkRoleMenuExist($data){
        $result = DB::table('user_menu')
            ->select('id_role', 'id_menu')
            ->where('id_role', $data['id_role'])
            ->where('id_menu', $data['id_menu'])
            ->first();

        return $result ? True : False;
    }
}
