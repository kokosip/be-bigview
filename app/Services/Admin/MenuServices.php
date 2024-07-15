<?php

namespace App\Services\Admin;

use App\Exceptions\ErrorResponse;
use App\Repositories\Admin\MenuRepositories;
use App\Repositories\Admin\UserRepositories;
use Exception;

class MenuServices {
    protected $menuRepositories;
    protected $userRepositories;

    public function __construct(MenuRepositories $menuRepositories, UserRepositories $userRepositories)
    {
        $this->menuRepositories = $menuRepositories;
        $this->userRepositories = $userRepositories;
    }

    public function insertMenu($data) {
        if(!$data['id_parent']) $data['id_parent'] = 0;
        $latestSort = $this->menuRepositories->getLatestSort($data);

        $data['id_parent'] = $latestSort;
        $data['sort'] = $latestSort;
        $this->menuRepositories->insertMenu($data);

        return $data;
    }

    public function getMenuUtama($isSubmenu){
        $rows = $this->menuRepositories->getMenuUtama($isSubmenu);
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
        if (!$result) {
            throw new ErrorResponse(type: 'Not Found', message: 'Menu tidak ditemukan.', statusCode: 404);
        }
        return $result;
    }

    public function deleteMenu($id_menu){
        $oldMenu = $this->getMenuById($id_menu);
        if (!$oldMenu) {
            throw new ErrorResponse(type: 'Not Found', message: 'Menu tidak ditemukan.', statusCode: 404);
        }
        $result = $this->menuRepositories->deleteMenu($id_menu);
        return $result;
    }

    public function updateMenu($data, $id_menu){
        $oldMenu = $this->getMenuById($id_menu);
        if (!$oldMenu) {
            throw new ErrorResponse(type: 'Not Found', message: 'Menu tidak ditemukan.', statusCode: 404);
        }
        $result = $this->menuRepositories->updateMenu($data, $id_menu);
        return $result;
    }

    public function listRoleMenu($data){
        $list_usermenu = $this->menuRepositories->getMenuByRole($data);
        if (!$list_usermenu) {
            throw new ErrorResponse(type: 'Not Found', message: 'Menu tidak ditemukan.', statusCode: 404);
        }

        $list_menu = $this->menuRepositories->listRoleMenu($data);
        if (!$list_menu) {
            throw new ErrorResponse(type: 'Not Found', message: 'Role menu tidak ditemukan.', statusCode: 404);
        }

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
            throw new ErrorResponse(type: 'Conflict', message: 'Role menu sudah ada.', statusCode: 409);
        }
        $list_menu = $this->menuRepositories->addRoleMenu($data);
        return $list_menu;
    }

    public function deleteRoleMenu($data){
        if(!$this->menuRepositories->checkRoleMenuExist($data)){
            throw new ErrorResponse(type: 'Not Found', message: 'Role menu tidak ditemukan.', statusCode: 404);
        }
        $list_menu = $this->menuRepositories->deleteRoleMenu($data);
        return $list_menu;
    }

    public function editSubadminMenu($data, $admin_role) {
        $rows = $this->menuRepositories->editSubadminMenu($data['menu'], $data['id_subadmin'], $admin_role);
        return $rows;
    }

    public function sortMenu($data, $admin) {
        $subadmin = $this->userRepositories->getSubadmin($admin['id_usecase']);
        $subadminRole = [];
        foreach ($subadmin as $subrole) {
            $user_sub = $this->userRepositories->getUserById($subrole);
            $subadminRole[] = $user_sub->id_role;
        }

        $rows = $this->menuRepositories->sortMenu($data, $admin['id_role'], $subadminRole);
        return $rows;
    }
}