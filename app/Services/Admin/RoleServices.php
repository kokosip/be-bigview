<?php

namespace App\Services\Admin;

use App\Repositories\Admin\RoleRepositories;
use Exception;

class RoleServices {
    protected $roleRepositories;

    public function __construct(RoleRepositories $roleRepositories)
    {
        $this->$roleRepositories = $roleRepositories;
    }

    public function insertRole($data) {
        $this->roleRepositories->insertRole($data);

        return $data;
    }
}
