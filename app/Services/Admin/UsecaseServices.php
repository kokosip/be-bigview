<?php

namespace App\Services\Admin;

use App\Repositories\Admin\UsecaseRepositories;
use App\Traits\FileStorage;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UsecaseServices {

    use FileStorage;
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

    public function getListNameUsecase(){
        $rows = $this->usecaseRepositories->getListNameUsecase();

        return $rows;
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

    public function updateUsecaseGovern($data, $id_usecase){
        $key_govern = ["kode_provinsi", "kode_kab_kota", "name_usecase"];
        $key_usecase = ["name_usecase", "base_color1", "base_color2", "base_color3", "base_color4"];

        $data_govern = array_intersect_key($data, array_flip($key_govern));
        $data_usecase = array_intersect_key($data, array_flip($key_usecase));

        $result_govern = $this->usecaseRepositories->updateUsecaseGovern($data_govern, $id_usecase);
        $result_usecase = $this->usecaseRepositories->updateUsecase($data_usecase, $id_usecase);

        if($result_govern || $result_usecase){
            return [$result_govern, $result_usecase];
        } else {
            throw new Exception('Gagal Update Usecase Government');
        }
    }

    public function updateLogoUsecase($data, $id_usecase){
        $pic_data = [
            "pic_logo" => $data,
        ];

        $result = $this->usecaseRepositories->updateUsecase($pic_data, $id_usecase);

        return [$result, $data];
    }

    public function updateUsecaseCustom($data, $id_usecase){
        $key_custom = ["name_usecase", "deskripsi"];
        $key_usecase = ["name_usecase", "base_color1", "base_color2", "base_color3", "base_color4"];

        $data_custom = array_intersect_key($data, array_flip($key_custom));
        $data_usecase = array_intersect_key($data, array_flip($key_usecase));

        $result_custom = $this->usecaseRepositories->updateUsecaseCustom($data_custom, $id_usecase);
        $result_usecase = $this->usecaseRepositories->updateUsecase($data_usecase, $id_usecase);

        if($result_custom || $result_usecase){
            return [$result_custom, $result_usecase];
        } else {
            dd($result_custom);
            throw new Exception('Gagal Update Usecase Custom');
        }
    }

    public function getUsecaseById($id_usecase){
        $result = $this->usecaseRepositories->getUsecaseById($id_usecase);

        if($result){
            return $result;
        } else {
            throw new Exception('ID Usecase Tidak Ditemukan');
        }
    }

    public function deleteUsecaseGovern($id_usecase){
        $this->getUsecaseById($id_usecase);

        $result_usecase = $this->usecaseRepositories->deleteUsecase($id_usecase);
        $result_govern = $this->usecaseRepositories->deleteUsecaseGovern($id_usecase);

        if($result_usecase || $result_govern){
            return [$result_usecase, $result_govern];
        } else {
            throw new Exception('Gagal Delete Usecase Government');
        }
    }

    public function deleteUsecaseCustom($id_usecase){
        $this->getUsecaseById($id_usecase);

        $result_usecase = $this->usecaseRepositories->deleteUsecase($id_usecase);
        $result_custom = $this->usecaseRepositories->deleteUsecaseCustom($id_usecase);

        if($result_usecase || $result_custom){
            return [$result_usecase, $result_custom];
        } else {
            throw new Exception('Gagal Delete Usecase Custom');
        }
    }

    public function setLogo($idUsecase, $file){
        $data = $this->getUsecaseById($idUsecase);

        $name_usecase = str_replace(' ', '', strtolower($data->name_usecase));

        $params = [
            'name_usecase' => $name_usecase,
            'type' => 'logo'
        ];

        $path = $this->uploadFile($idUsecase, $file, $params);

        $result = $this->updateLogoUsecase($path, $idUsecase);

        // Delete file lama dengan extension yang berbeda
        $key = "{$params['type']}/{$params['type']}_{$params['name_usecase']}_{$idUsecase}";
        $oldImg = $data->pic_logo;
        if (($oldImg != $path) && ($oldImg && strpos($oldImg, $key) === 0)) {
            $deleteOldImg = $this->deleteFile($oldImg);
        }

        if($result[0] == 1){
            if ($oldImg == $path) {
                $message = 'Berhasil mengunggah Logo '.$data->name_usecase;
            } else {
                $message = 'Berhasil memperbarui Logo '.$data->name_usecase;
            }
        } else if($result[0] == 0){
            $message = 'Berhasil memperbarui Logo '.$data->name_usecase;
        } else {
            throw new Exception('Gagal menambahkan Logo');
        }

        $response = [
            'message' => $message,
            'filename' => pathinfo($result[1], PATHINFO_BASENAME),
            'path' => $result[1]
        ];

        return $response;
    }

    public function getLogo($idUsecase){
        $data = $this->getUsecaseById($idUsecase);

        $path = $data->pic_logo;

        $url = $this->getFile($path);

        $response = [
            'filename' => pathinfo($path, PATHINFO_BASENAME),
            'url' => $url
        ];

        return $response;
    }

    public function deleteLogo($idUsecase) {
        $data = $this->getUsecaseById($idUsecase);

        $path = $data->pic_logo;

        $newData = [
            "pic_logo" => null,
        ];

        if ($path == null){
            $response = [
                "message"=> " Logo " . $data->name_usecase . " sudah null",
                'path' => null,
            ];
            return $response;
        }
        $delete = $this->deleteFile($path);

        if ($delete) {
            $result = $this->usecaseRepositories->updateUsecase($newData, $idUsecase);
            $response = [
                "message"=> "Berhasil menghapus logo " . $data->name_usecase,
                'path' => null,
            ];
        } else {
            throw new Exception('Gagal menghapus logo');
        }

        return $response;
    }
}
