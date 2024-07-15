<?php

namespace App\Services\Admin;

use App\Repositories\Admin\MenuRepositories;
use App\Repositories\Admin\RoleRepositories;
use App\Repositories\Admin\UserRepositories;
use App\Exceptions\ErrorResponse;
use Illuminate\Support\Facades\Hash;

class UserServices {
    protected $userRepositories;
    protected $roleRepositories;
    protected $menuRepositories;

    public function __construct(UserRepositories $userRepositories, RoleRepositories $roleRepositories, MenuRepositories $menuRepositories)
    {
        $this->userRepositories = $userRepositories;
        $this->roleRepositories = $roleRepositories;
        $this->menuRepositories = $menuRepositories;
    }

    public function getListUser($search, $perPage){
        $rows = $this->userRepositories->getListUser($search, $perPage);

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

    public function insertUser($data) {
        $data["password"] = Hash::make('user123');
        $result = $this->userRepositories->insertUser($data);
        unset($result["password"]);

        return $result;
    }

    public function getUserById($id_user){
        $result = $this->userRepositories->getUserById($id_user);

        if(!$result){
            throw new ErrorResponse(type: 'Not Found', message: 'User tidak ditemukan.', statusCode: 404);
        }
        return $result;
    }

    public function deleteUser($id_user){
        $oldUser = $this->getUserById($id_user);
        if(!$oldUser){
            throw new ErrorResponse(type: 'Not Found', message: 'User tidak ditemukan.', statusCode: 404);
        }
        $result = $this->userRepositories->deleteUser($id_user);
        return $result;
    }

    public function updateUser($data, $id_user){
        $oldUser = $this->getUserById($id_user);
        if(!$oldUser){
            throw new ErrorResponse(type: 'Not Found', message: 'User tidak ditemukan.', statusCode: 404);
        }
        $result = $this->userRepositories->updateMenu($data, $id_user);
        return $result;
    }

    public function updateIsActived($data, $id_user){
        $oldUser = $this->getUserById($id_user);
        if(!$oldUser){
            throw new ErrorResponse(type: 'Not Found', message: 'User tidak ditemukan.', statusCode: 404);
        }
        $result = $this->userRepositories->updateMenu($data, $id_user);
        return $result;
    }

    public function addSubAdmin($data) {
        $data["password"] = Hash::make('user123');

        $dataUser = array_diff_key($data, array_flip(['menu_access']));
        $dataMenu = $data['menu_access'];

        $dataRole = [];
        $dataRole['nama_role'] = $data['nama_role'];
        $dataRole['level'] = 2;
        $id_role = $this->roleRepositories->insertGetRoleId($dataRole);

        unset($dataUser['nama_role']);
        $dataUser['id_role'] = $id_role;
        $result = $this->userRepositories->insertUser($dataUser);

        foreach ($dataMenu as $id) {
            $accessMenu = [];
            $accessMenu['id_role'] = $id_role;
            $accessMenu['id_menu'] = $id;
            $this->menuRepositories->addRoleMenu($accessMenu); 
        }

        return $result;
    }
}