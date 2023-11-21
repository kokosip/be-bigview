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

    public function addUsecaseGovernment($data){
        $id_usecase = $this->usecaseRepositories->getLatestIdUsecase();

        $key_govern = ["kode_provinsi", "kode_kab_kota", "nama_usecase"];
        $key_usecase = ["nama_usecase", "base_color1", "base_color2", "base_color3", "base_color4"];

        $data_govern = array_intersect_key($data, array_flip($key_govern));
        $data_usecase = array_intersect_key($data, array_flip($key_usecase));

        $data_usecase["type_dashboard"] = 'Government';
        $data_govern["id_usecase"] = $id_usecase->id_usecase + 1;

        $result_govern = $this->usecaseRepositories->addUsecaseGovernment($data_govern);
        $result_usecase = $this->usecaseRepositories->addUsecase($data_usecase);

        return [$result_govern, $result_usecase];
    }

    public function addUsecaseCustom($data){
        $id_usecase = $this->usecaseRepositories->getLatestIdUsecase();

        $key_custom = ["nama_usecase", "deskripsi"];
        $key_usecase = ["nama_usecase", "base_color1", "base_color2", "base_color3", "base_color4"];

        $data_custom = array_intersect_key($data, array_flip($key_custom));
        $data_usecase = array_intersect_key($data, array_flip($key_usecase));

        $data_usecase["type_dashboard"] = 'Custom';
        $data_custom["id_usecase"] = $id_usecase->id_usecase + 1;

        $result_custom = $this->usecaseRepositories->addUsecaseCustom($data_custom);
        $result_usecase = $this->usecaseRepositories->addUsecase($data_usecase);

        return [$result_custom, $result_usecase];
    }
}
