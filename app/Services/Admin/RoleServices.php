<?php

namespace App\Services\Admin;

use App\Repositories\Admin\RoleRepositories;
use App\Exceptions\ErrorResponse;
use App\Traits\CheckDatabase;
use Exception;

class RoleServices
{
    use CheckDatabase;
    protected $roleRepositories;

    public function __construct(RoleRepositories $roleRepositories)
    {
        $this->roleRepositories = $roleRepositories;
    }

    public function insertRole($data)
    {
        $this->roleRepositories->insertRole($data);
        return $data;
    }

    public function getListRole($search, $perPage)
    {
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

    public function getListNameRole()
    {
        $rows = $this->roleRepositories->getListNameRole();
        return $rows;
    }

    public function getRoleById($id_role)
    {
        $result = $this->roleRepositories->getRoleById($id_role);
        if (!$result) {
            throw new ErrorResponse(type: 'Not Found', message: 'Role tidak ditemukan', statusCode: 404);
        }
        return $result;
    }

    public function deleteRole($id_role)
    {
        $oldRole = $this->getRoleById($id_role);
        if (!$oldRole) {
            throw new ErrorResponse(type: 'Not Found', message: 'Role tidak ditemukan', statusCode: 404);
        }
        $result = $this->roleRepositories->deleteRole($id_role);
        return $result;
    }

    public function assignedRoleMenu($data, $id_role)
    {
        $oldRole = $this->getRoleById($id_role);
        if (!$oldRole) {
            throw new ErrorResponse(type: 'Not Found', message: 'Role tidak ditemukan', statusCode: 404);
        }
        $result = $this->roleRepositories->assignedRoleMenu($data, $id_role);
        return $result;
    }

    public function updateRole($data, $id_role)
    {
        $oldRole = $this->getRoleById($id_role);
        if (!$oldRole) {
            throw new ErrorResponse(type: 'Not Found', message: 'Role tidak ditemukan', statusCode: 404);
        }

        $this->isDuplicate('role', ['nama_role'], [$data['nama_role']]);

        $result = $this->roleRepositories->updateRole($data, $id_role);
        return $result;
    }
}
