<?php

namespace App\Services\Admin;

use App\Repositories\Admin\RoleRepositories;
use Exception;

class RoleServices {
    protected $roleRepositories;

    public function __construct(RoleRepositories $roleRepositories)
    {
        $this->roleRepositories = $roleRepositories;
    }

    public function insertRole($data) {
        $this->roleRepositories->insertRole($data);

        return $data;
    }

    public function getListRole($search, $perPage){
        $rows = $this->roleRepositories->getListRole($search, $perPage);

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

    public function getListNameRole(){
        $rows = $this->roleRepositories->getListNameRole();

        return $rows;
    }

    public function getRoleById($id_role){
        $result = $this->roleRepositories->getRoleById($id_role);

        if($result){
            return $result;
        } else {
            throw new Exception('ID Tidak Ditemukan');
        }
    }

    public function deleteRole($id_role){
        $this->getRoleById($id_role);

        $result = $this->roleRepositories->deleteRole($id_role);

        if($result){
            return $result;
        } else {
            throw new Exception('Gagal Update Role');
        }
    }

    public function updateRole($data, $id_role){
        $this->getRoleById($id_role);

        $result = $this->roleRepositories->updateRole($data, $id_role);

        if($result){
            return $result;
        } else {
            throw new Exception('Gagal Update Role');
        }
    }
}
