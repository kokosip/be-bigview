<?php

namespace App\Services\Admin;

use App\Repositories\Admin\MenuRepositories;
use App\Repositories\Admin\RoleRepositories;
use App\Repositories\Admin\UsecaseRepositories;
use App\Repositories\Admin\UserRepositories;
use App\Exceptions\ErrorResponse;
use Illuminate\Support\Facades\Hash;

class UserServices {
    protected $userRepositories;
    protected $roleRepositories;
    protected $menuRepositories;
    protected $usecaseRepositories;

    public function __construct(UserRepositories $userRepositories, RoleRepositories $roleRepositories, MenuRepositories $menuRepositories, UsecaseRepositories $usecaseRepositories)
    {
        $this->userRepositories = $userRepositories;
        $this->roleRepositories = $roleRepositories;
        $this->menuRepositories = $menuRepositories;
        $this->usecaseRepositories = $usecaseRepositories;
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
        
        $dataRole = [];
        $dataRole['nama_role'] = $data['name'];
        $dataRole['level'] = 1;
        $id_role = $this->roleRepositories->insertGetRoleId($dataRole);

        $data['id_role'] = $id_role;
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

        $dataRole = [];
        $dataRole['nama_role'] = $data['nama_role'];
        $dataRole['level'] = 2;
        $id_role = $this->roleRepositories->insertGetRoleId($dataRole);

        unset($dataUser['nama_role']);
        $dataUser['id_role'] = $id_role;
        $result = $this->userRepositories->insertUser($dataUser);

        if (isset($data['menu_access'])) {
            $dataMenu = $data['menu_access'];
            foreach ($dataMenu as $id) {
                $accessMenu = [];
                $accessMenu['id_role'] = $id_role;
                $accessMenu['id_menu'] = $id;
                $this->menuRepositories->addRoleMenu($accessMenu); 
            }
        }
        return $result;
    }

    public function getUserDetail($id_user, $id_usecase) {
        if ($id_user == null) {
            throw new ErrorResponse(type: 'Forbidden', message:'User not logged in.', statusCode: 403);
        }
        $role_user = $this->userRepositories->getUserRole($id_user);

        $data = [];
        $data['profile'] = $this->userRepositories->getUserById($id_user);
        $data['menu'] = $this->menuRepositories->getUserMenu($role_user);
        $data['usecase'] = $this->usecaseRepositories->findUseCase($id_usecase);

        return $data;
    }
}