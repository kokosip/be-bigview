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
            'type' => 'logo',
            'dir' => 'logo'
        ];

        $path = $this->uploadFile($idUsecase, $file, $params);

        $result = $this->updateLogoUsecase($path, $idUsecase);

        // Delete file lama dengan extension yang berbeda
        $key = "{$params['dir']}/{$params['type']}_{$params['name_usecase']}_{$idUsecase}";
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

        $data = [
            'filename' => pathinfo($result[1], PATHINFO_BASENAME),
            'path' => $result[1]
        ];

        return [$message, $data];
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
        // Retrieve the use case data by ID
        $data = $this->getUsecaseById($idUsecase);
    
        // Get the current logo path
        $path = $data->pic_logo;
    
        // Prepare new data to update the use case with the logo removed
        $newData = [
            "pic_logo" => null,
        ];
    
        // Check if the logo is already null
        if ($path == null) {
            $message = "Logo " . $data->name_usecase . " sudah null";
            $returnData = [
                'path' => null,
            ];
            return [$returnData, $message];
        }
    
        // Attempt to delete the logo file
        $delete = $this->deleteFile($path);
    
        if ($delete) {
            // Update the use case with the new data
            $result = $this->usecaseRepositories->updateUsecase($newData, $idUsecase);
            $message = "Logo " . $data->name_usecase . " berhasil dihapus";
            $returnData = [
                'path' => null,
            ];
        } else {
            // Throw an exception if the file deletion fails
            throw new Exception('Gagal menghapus logo');
        }
    
        // Return the data and message
        return [$returnData, $message];
    }
    

    public function setProfile($idUsecase, $files) {
        $data = $this->getUsecaseById($idUsecase);
        $name_usecase = str_replace(' ', '', strtolower($data->name_usecase));
        $uploadedFiles = [];
        $picFiles = [];
    
        foreach (['leader', 'vice'] as $role) {
            if (isset($files[$role])) {
                $picFiles[$role] = $files[$role];
            }
        }
    
        foreach ($picFiles as $type => $file) {
            $path = $this->uploadFile($idUsecase, ['file' => $file], [
                'name_usecase' => $name_usecase,
                'type' => $type,
                'dir' => 'profile',
            ]);
    
            $uploadedFiles[] = [
                'type' => $type,
                'filename' => pathinfo($path, PATHINFO_BASENAME),
                'path' => $path,
            ];
    
            $kolom = 'pic_' . strtolower($type);
            $result = $this->updateProfile($path, $idUsecase, $kolom);
            $oldImg = $data->{$kolom};
            
            if ($oldImg !== $path && $oldImg && strpos($oldImg, "profile/{$type}_{$name_usecase}_{$idUsecase}") === 0) {
                $this->deleteFile($oldImg);
            }
    
            $checkUpdate['S_' . $type] = $result[0];
        }
    
        foreach (['leader_name' => 'leader', 'vice_name' => 'vice'] as $name_key => $type) {
            if (isset($files[$name_key])) {
                $result = $this->updateProfile($files[$name_key], $idUsecase, $name_key);
                $checkUpdate['S_' . $name_key] = $result[0];
                $uploadedFiles[] = [
                    'type' => 'name',
                    'filename' => $files[$name_key],
                    'path' => 'not a file',
                ];
            }
        }
    
        $successItems = [];
        foreach (['leader', 'vice'] as $role) {
            if (isset($checkUpdate['S_' . $role]) && ($checkUpdate['S_' . $role] == 0 || $checkUpdate['S_' . $role] == 1)) {
                $successItems[] = "gambar {$role}";
            }
            if (isset($checkUpdate['S_' . $role . '_name']) && ($checkUpdate['S_' . $role . '_name'] == 0 || $checkUpdate['S_' . $role . '_name'] == 1)) {
                $successItems[] = "nama {$role}";
            }
        }
        $message = !empty($successItems) ? 'Berhasil update: ' . implode(', ', $successItems) : 'Gagal update data';
    
        $response = ['message' => $message];
        foreach ($uploadedFiles as $file) {
            $returnData[] = [
                'name' => ($file['type'] == 'name') ? $file['filename'] : pathinfo($file['path'], PATHINFO_BASENAME),
                'path' => $file['path']
            ];
        }
    
        return [$returnData, $message];
    }
    

    public function updateProfile($data, $idUsecase, $type) {
        $pic_data = [
            $type => $data,
        ];

        $result = $this->usecaseRepositories->updateUsecaseGovern($pic_data, $idUsecase);

        return [$result, $data];
    }



    public function updateContact($idUsecase, $data) {
        try {
            $result = $this->usecaseRepositories->updateUsecaseGovern($data, $idUsecase);

            $successItems = [];
            $addressKeys = ['address', 'phone', 'link_map'];
    
            foreach ($addressKeys as $key) {
                if (isset($data[$key])) {
                    switch ($key) {
                        case 'address':
                            $successItems[] = "alamat";
                            break;
                        case 'phone':
                            $successItems[] = "nomor telepon";
                            break;
                        case 'link_map':
                            $successItems[] = "link maps";
                            break;
                    }
                }
            }
            $message = !empty($successItems) ? 'Berhasil update: ' . implode(', ', $successItems) : 'Gagal update data';
    
            return [$data, $message];
        } catch (Exception $e) {
            throw new Exception('Gagal update data kontak');
        } 
    }

    public function addPeriode($idUsecase, $data) {
        try {
            DB::beginTransaction();
            $dataPeriode = [
                'start_year' => $data['start_year'],
                'end_year' => $data['end_year'],
                'id_usecase' => $idUsecase,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $this->usecaseRepositories->addPeriode($dataPeriode);
            DB::commit();
            $message = "Periode berhasil ditambahkan";
            return [$dataPeriode, $message];
        } catch (\Exception $e) {
            DB::rollback();
            throw new Exception($e->getMessage());
        }
    }

    public function addVisi($idUsecase, $data) {
        try {
            DB::beginTransaction();

            $dataVisi = [
                'id_usecase' => $idUsecase,
                'short_desc' => $data['short_desc'],
                'description' => isset($data['description']) ? $data['description'] : "",
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $result = $this->usecaseRepositories->addVisi($dataVisi);

            DB::commit();
            $message = "Visi berhasil ditambahkan";
            return [$result, $message];
        } catch (\Exception $e) {
            DB::rollback();
            throw new Exception($e->getMessage());
        }
    }

    public function updateVisi($idUsecase, $data) {
        try {
            DB::beginTransaction();

            $dataVisi = [
                'short_desc' => $data['short_desc'],
                'updated_at' => now(),
            ];
            if (isset($data['description'])) {
                $dataVisi['description'] = $data['description'];
            }

            $id = $data['id_visi'];
            $result = $this->usecaseRepositories->updateVisi($dataVisi, $id);

            DB::commit();
            $message = "Visi berhasil diperbarui";
            return [$result, $message];
        } catch (\Exception $e) {
            DB::rollback();
            throw new Exception($e->getMessage());
        }
    }

    public function deleteVisi($idUsecase, $data) {
        try {
            DB::beginTransaction();

            $id = $data["id_visi"];

            $this->usecaseRepositories->deleteVisi($id);

            DB::commit();
            $message = "Visi berhasil dihapus";
            return $message;
        } catch (\Exception $e) {
            DB::rollback();
            throw new Exception($e->getMessage());
        }
    }

    public function getListVisi($idUsecase, $data) {
        try {
            $perPage = $data['perPage'];

            $rows = $this->usecaseRepositories->getListVisi($idUsecase, $perPage);

            $pagination = [
                "current_page" => $rows->currentPage(),
                "per_page" => $rows->perPage(),
                "total_page" => ceil($rows->total() / $rows->perPage()),
                "total_row" => $rows->total(),
            ];

            return [$rows->items(), $pagination];
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function addMisi($idUsecase, $data) {
        try {
            DB::beginTransaction();

            $dataMisi = [
                'id_usecase' => $idUsecase,
                'urutan' => $data['urutan'],
                'short_desc' => $data['short_desc'],
                'description' => isset($data['description']) ? $data['description'] : "",
                'order_by' => 'urutan',
                'order_dir' => 'asc',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $result = $this->usecaseRepositories->addMisi($dataMisi);

            DB::commit();
            $message = "Misi berhasil ditambahkan";
            return [$result, $message];
        } catch (\Exception $e) {
            DB::rollback();
            throw new Exception($e->getMessage());
        }
    }

    public function updateMisi($idUsecase, $data) {
        try {
            DB::beginTransaction();

            $oldMisi = $this->usecaseRepositories->getMisiById($data['id_misi']);

            $dataMisi = [
                'short_desc' => $data['short_desc'],
                'description' => isset($data['description']) ? $data['description'] : "",
                'urutan' => isset($data['urutan']) ? $data['urutan'] : $oldMisi['urutan'],
                'updated_at' => now(),
            ];
            $id = $data['id_misi'];
            $result = $this->usecaseRepositories->updateMisi($id, $dataMisi);

            DB::commit();
            $message = "Misi berhasil diperbarui";
            return [$result, $message];
        } catch (\Exception $e) {
            DB::rollback();
            throw new Exception($e->getMessage());
        }
    }

    public function deleteMisi($idUsecase, $data) {
        try {
            DB::beginTransaction();

            $id = $data["id_misi"];
            $this->usecaseRepositories->deleteMisi($id);

            DB::commit();
            $message = "Misi berhasil dihapus";
            return $message;
        } catch (\Exception $e) {
            DB::rollback();
            throw new Exception($e->getMessage());
        }
    }

    public function getListMisi($idUsecase, $data) {
        try {
            $perPage = $data['perPage'];

            $rows = $this->usecaseRepositories->getListMisi($idUsecase, $perPage);

            $pagination = [
                "current_page" => $rows->currentPage(),
                "per_page" => $rows->perPage(),
                "total_page" => ceil($rows->total() / $rows->perPage()),
                "total_row" => $rows->total(),
            ];

            return [$rows->items(), $pagination];
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
