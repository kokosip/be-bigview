<?php

namespace App\Services\Admin;

use App\Exceptions\ErrorResponse;
use App\Repositories\Admin\MasterRepositories;
use Exception;

class MasterServices
{
    protected $masterRepositories;

    public function __construct(MasterRepositories $masterRepositories)
    {
        $this->masterRepositories = $masterRepositories;
    }

    public function getListProvinsi()
    {
        $result = $this->masterRepositories->getListProvinsi();
        return $result;
    }

    public function getListKabkota($id_prov)
    {
        $result = $this->masterRepositories->getListKabkota($id_prov);
        if (!$result) {
            throw new ErrorResponse(type: 'Not Found', message: 'Provinsi tidak ditemukan.', statusCode: 404);
        }
        return $result;
    }
}
