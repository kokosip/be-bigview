<?php

namespace App\Services\Admin;

use App\Repositories\Admin\UserRepositories;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserServices {
    protected $userRepositories;

    public function __construct(UserRepositories $userRepositories)
    {
        $this->userRepositories = $userRepositories;
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

        if($result){
            return $result;
        } else {
            throw new Exception('ID Tidak Ditemukan');
        }
    }

    public function deleteUser($id_user){
        $this->getUserById($id_user);

        $result = $this->userRepositories->deleteUser($id_user);

        return $result;
    }

    public function updateUser($data, $id_user){
        $this->getUserById($id_user);

        $result = $this->userRepositories->updateMenu($data, $id_user);

        if($result){
            return $result;
        } else {
            throw new Exception('Gagal Update Menu');
        }
    }
}
