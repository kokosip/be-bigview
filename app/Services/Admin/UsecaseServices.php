<?php

namespace App\Services\Admin;

use App\Repositories\Admin\UsecaseRepositories;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        try{
            DB::beginTransaction();

            $key_govern = ["kode_provinsi", "kode_kab_kota", "name_usecase"];
            $key_usecase = ["name_usecase", "base_color1", "base_color2", "base_color3", "base_color4"];

            $data_govern = array_intersect_key($data, array_flip($key_govern));
            $data_usecase = array_intersect_key($data, array_flip($key_usecase));

            $data_usecase["type_dashboard"] = 'Government';
            $result_usecase = $this->usecaseRepositories->addUsecase($data_usecase);

            $data_govern["id_usecase"] = $result_usecase;
            $this->usecaseRepositories->addUsecaseGovernment($data_govern);

            DB::commit();

            return $data_govern;
        } catch (\Exception $e) {
            DB::rollback();
            throw new Exception($e->getMessage());
        }
    }

    public function addUsecaseCustom($data){
        try{
            DB::beginTransaction();

            $key_custom = ["name_usecase", "deskripsi"];
            $key_usecase = ["name_usecase", "base_color1", "base_color2", "base_color3", "base_color4"];

            $data_custom = array_intersect_key($data, array_flip($key_custom));
            $data_usecase = array_intersect_key($data, array_flip($key_usecase));

            $data_usecase["type_dashboard"] = 'Custom';
            $result_usecase = $this->usecaseRepositories->addUsecase($data_usecase);

            $data_custom["id_usecase"] = $result_usecase;
            $this->usecaseRepositories->addUsecaseCustom($data_custom);

            DB::commit();

            return $data_custom;
        } catch (\Exception $e) {
            DB::rollback();
            throw new Exception($e->getMessage());
        }
    }
}
