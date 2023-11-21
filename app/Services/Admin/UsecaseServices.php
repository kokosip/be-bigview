<?php

namespace App\Services\Admin;

use App\Repositories\Admin\UsecaseRepositories;
use Exception;

class UsecaseServices {
    protected $usecaseRepositories;

    public function __construct(UsecaseRepositories $usecaseRepositories)
    {
        $this->usecaseRepositories = $usecaseRepositories;
    }

    public function getListUsecase($search, $perPage){
        $rows = $this->usecaseRepositories->getListUsecase($search, $perPage);

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

    public function getListProvinsi(){
        $result = $this->usecaseRepositories->getListProvinsi();

        return $result;
    }

    public function getListKabkota($id_prov){
        $result = $this->usecaseRepositories->getListKabkota($id_prov);

        return $result;
    }

    public function addUsecaseGovernment($data){
        $key_govern = ["kode_provinsi", "kode_kab_kota", "nama_usecase"];
        $key_usecase = ["nama_usecase", "base_color1", "base_color2", "base_color3", "base_color4"];

        $data_govern = array_intersect_key($data, array_flip($key_govern));
        $data_usecase = array_intersect_key($data, array_flip($key_usecase));

        $data_usecase["type_dashboard"] = 'Government';

        $result_govern = $this->usecaseRepositories->addUsecaseGovernment($data_govern);
        $result_usecase = $this->usecaseRepositories->addUsecase($data_usecase);

        return [$result_govern, $result_usecase];
    }

    public function addUsecaseCustom($data){
        $key_govern = ["kode_provinsi", "kode_kab_kota", "nama_usecase"];
        $key_usecase = ["nama_usecase", "base_color1", "base_color2", "base_color3", "base_color4"];

        $data_govern = array_intersect_key($data, array_flip($key_govern));
        $data_usecase = array_intersect_key($data, array_flip($key_usecase));

        $data_usecase["type_dashboard"] = 'Government';

        $result_govern = $this->usecaseRepositories->addUsecaseGovernment($data_govern);
        $result_usecase = $this->usecaseRepositories->addUsecase($data_usecase);

        return [$result_govern, $result_usecase];
    }
}
