<?php

namespace App\Services\Admin;

use App\Repositories\Admin\MenuRepositories;
use Exception;

class MenuServices {
    protected $menuRepositories;

    public function __construct(MenuRepositories $menuRepositories)
    {
        $this->menuRepositories = $menuRepositories;
    }

    public function insertMenu($data) {
        if(!$data['id_parent']) $data['id_parent'] = 0;
        $latestSort = $this->menuRepositories->getLatestSort($data);

        $data['id_parent'] = $latestSort;
        $data['sort'] = $latestSort;
        $this->menuRepositories->insertMenu($data);

        return $data;
    }

    public function getMenuUtama(){
        $rows = $this->menuRepositories->getMenuUtama();

        return $rows;
    }

    public function getListMenu($search, $perPage){
        $rows = $this->menuRepositories->getListMenu($search, $perPage);

        $pagination = [
            "current_page" => $rows->currentPage(),
            "per_page" => $rows->perPage(),
            "total_page" => ceil($rows->total() / $rows->perPage()),
            "total_row" => $rows->total(),
        ];

        return [
            $rows->items(),
            $pagination
        ];
    }

    public function getMenuById($id_menu){
        $result = $this->menuRepositories->getMenuById($id_menu);

        if($result){
            return $result;
        } else {
            throw new Exception('ID Tidak Ditemukan');
        }
    }

    public function deleteMenu($id_menu){
        $this->getMenuById($id_menu);

        $result = $this->menuRepositories->deleteMenu($id_menu);

        if($result){
            return $result;
        } else {
            throw new Exception('Gagal Delete Menu');
        }
    }

    public function updateMenu($data, $id_menu){
        $this->getMenuById($id_menu);

        $result = $this->menuRepositories->updateMenu($data, $id_menu);

        if($result){
            return $result;
        } else {
            throw new Exception('Gagal Update Menu');
        }
    }

    public function listRoleMenu($data){
        $list_usermenu = $this->menuRepositories->getMenuByRole($data);

        $list_menu = $this->menuRepositories->listRoleMenu($data);

        foreach($list_menu as $list){
            if(in_array($list->id_menu, $list_usermenu)){
                $list->is_check = True;
            } else {
                $list->is_check = False;
            }
        }

        return $list_menu;
    }

    public function addRoleMenu($data){
        if($this->menuRepositories->checkRoleMenuExist($data)){
            throw new Exception('Role Menu Sudah Ada.');
        }

        $list_menu = $this->menuRepositories->addRoleMenu($data);

        return $list_menu;
    }

    public function deleteRoleMenu($data){
        if(!$this->menuRepositories->checkRoleMenuExist($data)){
            throw new Exception('Role Menu Tidak Ada.');
        }

        $list_menu = $this->menuRepositories->deleteRoleMenu($data);

        return $list_menu;
    }
}
