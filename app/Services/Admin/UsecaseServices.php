<?php

namespace App\Services\Admin;

use App\Repositories\Admin\UsecaseRepositories;
use App\Traits\FileStorage;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Exceptions\ErrorResponse;

class UsecaseServices {

    use FileStorage;
    use ApiResponse;
    protected $usecaseRepositories;

    public function __construct(UsecaseRepositories $usecaseRepositories)
    {
        $this->usecaseRepositories = $usecaseRepositories;
    }

    public function getListUsecase($search, $perPage){
        $rows = $this->usecaseRepositories->getListUsecase($search, $perPage);

        if ($rows->isEmpty()) {
            throw new ErrorResponse(type: 'Not found', message: 'Kata kunci tidak ditemukan', statusCode: 404);
        }

        $pagination = [
            "current_page" => $rows->currentPage(),
            "per_page" => $rows->perPage(),
            "total_page" => ceil($rows->total() / $rows->perPage()),
            "total_row" => $rows->total(),
        ];

        return [$rows->items(), $pagination];
    }

    public function getListNameUsecase(){
        $rows = $this->usecaseRepositories->getListNameUsecase();

        if ($rows->isEmpty()) {
          throw new ErrorResponse(type:"Not found", message:"Data tabel kosong",statusCode:404);
        }

        return $rows;
    }

    public function addUsecaseGovernment($data){
        DB::beginTransaction();

        $key_govern = ["kode_provinsi", "kode_kab_kota", "name_usecase"];
        $key_usecase = ["name_usecase", "base_color1", "base_color2", "base_color3", "base_color4"];

        $data_govern = array_intersect_key($data, array_flip($key_govern));
        $data_usecase = array_intersect_key($data, array_flip($key_usecase));

        $data_usecase["type_dashboard"] = 'Government';
        $result_usecase = $this->usecaseRepositories->addUsecase($data_usecase);

        $data_govern["id_usecase"] = $result_usecase;
        $result = $this->usecaseRepositories->addUsecaseGovernment($data_govern);

        if ($result) {
            DB::commit();
            $message = "Usecase Government Berhasil ditambahkan";
            return [$data_govern, $message];
        } else {
            DB::rollback();
        }
    }

    public function addUsecaseCustom($data){
        DB::beginTransaction();

        $key_custom = ["name_usecase", "deskripsi"];
        $key_usecase = ["name_usecase", "base_color1", "base_color2", "base_color3", "base_color4"];

        $data_custom = array_intersect_key($data, array_flip($key_custom));
        $data_usecase = array_intersect_key($data, array_flip($key_usecase));

        $data_usecase["type_dashboard"] = 'Custom';
        $result_usecase = $this->usecaseRepositories->addUsecase($data_usecase);

        $data_custom["id_usecase"] = $result_usecase;
        $result = $this->usecaseRepositories->addUsecaseCustom($data_custom);

        if ($result != null) {
            DB::commit();

            $message = "Usecase Custom Berhasil ditambahkan";
            return [$data_custom, $message];
        } else {
            DB::rollback();
        }
    }

    public function updateUsecaseGovern($data, $id_usecase){
        $key_govern = ["kode_provinsi", "kode_kab_kota", "name_usecase"];
        $key_usecase = ["name_usecase", "base_color1", "base_color2", "base_color3", "base_color4"];

        $data_govern = array_intersect_key($data, array_flip($key_govern));
        $data_usecase = array_intersect_key($data, array_flip($key_usecase));

        $result_govern = $this->usecaseRepositories->updateUsecaseGovern($data_govern, $id_usecase);
        $result_usecase = $this->usecaseRepositories->updateUsecase($data_usecase, $id_usecase);

        $message = "Usecase Government Berhasil diperbarui";
        return [$result_govern, $result_usecase, $message];
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

        if (!$result_custom) {
            throw new ErrorResponse(type: 'Not found', message: 'Usecase tidak ditemukan.', statusCode: 404);
        }
        $message = "Usecase Custom Berhasil diperbarui";
        return [$result_custom, $result_usecase, $message];
    }

    public function getUsecaseById($id_usecase){
        $result = $this->usecaseRepositories->getUsecaseById($id_usecase);

        if($result){
            return $result;
        } else {
            throw new ErrorResponse(type: 'Not found', message: 'Usecase tidak ditemukan', statusCode: 404);
        }
    }

    public function deleteUsecaseGovern($id_usecase){
        $this->getUsecaseById($id_usecase);

        $result_usecase = $this->usecaseRepositories->deleteUsecase($id_usecase);
        $result_govern = $this->usecaseRepositories->deleteUsecaseGovern($id_usecase);

        if($result_usecase || $result_govern){
            return 'Data Berhasil dihapus';
        } else {
            throw new ErrorResponse(type: 'Not found', message: 'Usecase tidak ditemukan', statusCode: 404);
        }
    }

    public function deleteUsecaseCustom($id_usecase){
        $this->getUsecaseById($id_usecase);

        $result_usecase = $this->usecaseRepositories->deleteUsecase($id_usecase);
        $result_custom = $this->usecaseRepositories->deleteUsecaseCustom($id_usecase);

        if($result_usecase || $result_custom){
            return 'Data Berhasil dihapus';
        } else {
            throw new ErrorResponse(type: 'Not found', message: 'Usecase tidak ditemukan', statusCode: 404);
        }
    }

    public function setLogo($idUsecase, $file){
        $data = $this->getUsecaseById($idUsecase);

        if (!$data){
            throw new ErrorResponse(type:'Not found', message:'Usecase tidak ditemukan.', statusCode:404);
        }
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
            $this->deleteFile($oldImg);
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
            throw new ErrorResponse(type: 'Failed', message:'Gagal menambahkan Logo', statusCode:500);
        }

        $data = [
            'filename' => pathinfo($result[1], PATHINFO_BASENAME),
            'path' => $result[1]
        ];

        return [$message, $data];
    }

    public function getLogo($idUsecase){
        $data = $this->getUsecaseById($idUsecase);

        if (!$data) {
            throw new ErrorResponse(type:'Not found', message:'Usecase tidak ditemukan.', statusCode:404);
        }

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
        if (!$data) {
            throw new ErrorResponse(type:'Not found', message:'Usecase tidak ditemukan.', statusCode:404);
        }
    
        $path = $data->pic_logo;
    
        $newData = [
            "pic_logo" => null,
        ];
    
        if ($path == null) {
            $message = "Logo " . $data->name_usecase . " sudah null";
            $returnData = [
                'path' => null,
            ];
            return [$returnData, $message];
        }
    
        $delete = $this->deleteFile($path);
    
        if ($delete) {
            $result = $this->usecaseRepositories->updateUsecase($newData, $idUsecase);
            $message = "Logo " . $data->name_usecase . " berhasil dihapus";
            $returnData = [
                'path' => null,
            ];
        } else {
            throw new ErrorResponse(type: 'Failed', message:'Gagal Menghapus Logo.', statusCode:500);
        }
    
        return [$returnData, $message];
    }
    

    public function setProfile($idUsecase, $files) {
        $data = $this->getUsecaseById($idUsecase);
        if (!$data) {
            throw new ErrorResponse(type:'Not found', message:'Usecase tidak ditemukan.', statusCode:404);
        }
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
        $dataUsecase = $this->getUsecaseById($idUsecase);
        if (!$dataUsecase) {
            throw new ErrorResponse(type:'Not found', message:'Usecase tidak ditemukan.', statusCode:404);
        }

        $data['address'] = null;
        $data['phone'] = null;
        $data['link_map'] = null;
        $this->usecaseRepositories->updateUsecaseGovern($data, $idUsecase);

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
    }

    public function addVisi($idUsecase, $data) {
        $dataUsecase = $this->getUsecaseById($idUsecase);
        if (!$dataUsecase) {
            throw new ErrorResponse(type:'Not found', message:'Usecase tidak ditemukan.', statusCode:404);
        }
        $dataVisi = [
            'id_usecase' => $idUsecase,
            'short_desc' => $data['short_desc'],
            'description' => isset($data['description']) ? $data['description'] : "",
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $result = $this->usecaseRepositories->addVisi($dataVisi);

        $message = "Visi berhasil ditambahkan";
        return [$result, $message];
    }

    public function updateVisi($idUsecase, $data) {
        $dataUsecase = $this->getUsecaseById($idUsecase);
        if (!$dataUsecase) {
            throw new ErrorResponse(type:'Not found', message:'Usecase tidak ditemukan.', statusCode:404);
        }
        $dataVisi = [
            'short_desc' => $data['short_desc'],
            'updated_at' => now(),
        ];
        if (isset($data['description'])) {
            $dataVisi['description'] = $data['description'];
        }

        $id = $data['id_visi'];
        $result = $this->usecaseRepositories->updateVisi($dataVisi, $id);
        $message = "Visi berhasil diperbarui";
        return [$result, $message];
    }

    public function deleteVisi($idUsecase, $data)
    {
        $id = $data["id_visi"];

        $visi = $this->usecaseRepositories->getVisiById($id);
        if (!$visi) {
            throw new ErrorResponse(message: 'id tidak ditemukan', statusCode: 404);
        }

        $this->usecaseRepositories->deleteVisi($id);
        return 'Visi berhasil dihapus';
    }

    public function getListVisi($idUsecase, $data) {
        $dataUsecase = $this->getUsecaseById($idUsecase);
        if (!$dataUsecase) {
            throw new ErrorResponse(type:'Not found', message:'Usecase tidak ditemukan.', statusCode:404);
        }
        $perPage = $data['perPage'];
        $rows = $this->usecaseRepositories->getListVisi($idUsecase, $perPage);

        $pagination = [
            "current_page" => $rows->currentPage(),
            "per_page" => $rows->perPage(),
            "total_page" => ceil($rows->total() / $rows->perPage()),
            "total_row" => $rows->total(),
        ];

        return [$rows->items(), $pagination];
    }

    public function addMisi($idUsecase, $data) {
        $dataUsecase = $this->getUsecaseById($idUsecase);
        if (!$dataUsecase) {
            throw new ErrorResponse(type:'Not found', message:'Usecase tidak ditemukan.', statusCode:404);
        }
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

        $message = "Misi berhasil ditambahkan";
        return [$result, $message];
    }

    public function updateMisi($idUsecase, $data) {
        $oldMisi = $this->usecaseRepositories->getMisiById($data['id_misi']);
        if (!$oldMisi) {
            throw new ErrorResponse(type:'Not found', message:'Misi tidak ditemukan.', statusCode:404);
        }

        $dataMisi = [
            'short_desc' => $data['short_desc'],
            'description' => isset($data['description']) ? $data['description'] : "",
            'urutan' => isset($data['urutan']) ? $data['urutan'] : $oldMisi['urutan'],
            'updated_at' => now(),
        ];
        $id = $data['id_misi'];
        $result = $this->usecaseRepositories->updateMisi($id, $dataMisi);

        $message = "Misi berhasil diperbarui";
        return [$result, $message];
    }

    public function deleteMisi($idUsecase, $data) {
        $id = $data["id_misi"];
        $oldMisi = $this->usecaseRepositories->getMisiById($id);
        if (!$oldMisi) {
            throw new ErrorResponse(type:'Not found', message:'Misi tidak ditemukan.', statusCode:404);
        }
        $this->usecaseRepositories->deleteMisi($id);

        $message = "Misi berhasil dihapus";
        return $message;
    }

    public function getListMisi($idUsecase, $data) {
        $dataUsecase = $this->getUsecaseById($idUsecase);
        if (!$dataUsecase) {
            throw new ErrorResponse(type:'Not found', message:'Usecase tidak ditemukan.', statusCode:404);
        }

        $perPage = $data['perPage'];
        $rows = $this->usecaseRepositories->getListMisi($idUsecase, $perPage);

        $pagination = [
            "current_page" => $rows->currentPage(),
            "per_page" => $rows->perPage(),
            "total_page" => ceil($rows->total() / $rows->perPage()),
            "total_row" => $rows->total(),
        ];

        return [$rows->items(), $pagination];
    }

    public function getListSektor($idUsecase) {
        $dataUsecase = $this->getUsecaseById($idUsecase);
        if (!$dataUsecase) {
            throw new ErrorResponse(type:'Not found', message:'Usecase tidak ditemukan.', statusCode:404);
        }

        $data = $this->usecaseRepositories->getListSektor($idUsecase);

        $formatData = [];
        foreach ($data as $row) {
            $formatData[] = [
                'label' => $row->sector,
                'value' => $row->sector,
            ];
        }

        return $formatData;
    }

    public function getListDataSektor($idUsecase, $data) {
        $dataUsecase = $this->getUsecaseById($idUsecase);
        if (!$dataUsecase) {
            throw new ErrorResponse(type:'Not found', message:'Usecase tidak ditemukan.', statusCode:404);
        }
        $sektor = $data['sektor'];
        $data = $this->usecaseRepositories->getListDataSektor($idUsecase, $sektor);
        
        return $data;
    }

    public function getListIndikator($idUsecase, $data) {
        $dataUsecase = $this->getUsecaseById($idUsecase);
        if (!$dataUsecase) {
            throw new ErrorResponse(type:'Not found', message:'Usecase tidak ditemukan.', statusCode:404);
        }
        $sektor = $data['sektor'];
        $data = $this->usecaseRepositories->getListIndikator($idUsecase, $sektor);
        
        return $data;
    }

    public function getListSatuan() {
        $data = $this->usecaseRepositories->getListSatuan();

        $formatData = [];
        foreach ($data as $row) {
            $formatData[] = [
                'label' => $row->satuan,
                'value' => $row->satuan,
            ];
        }
        return $formatData;
    }

    public function getListOpd($idUsecase, $data) {
        $dataUsecase = $this->getUsecaseById($idUsecase);
        if (!$dataUsecase) {
            throw new ErrorResponse(type:'Not found', message:'Usecase tidak ditemukan.', statusCode:404);
        }
        $sektor = $data['sektor'];
        $data = $this->usecaseRepositories->getListOpd($idUsecase, $sektor);

        $formatData = [];
        foreach ($data as $row) {
            $formatData[] = [
                'label' => $row->opd,
                'value' => $row->opd,
            ];
        }
        
        return $formatData;
    }

    public function addSektorIku($idUsecase, $data) {
        $dataUsecase = $this->getUsecaseById($idUsecase);
        if (!$dataUsecase) {
            throw new ErrorResponse(type:'Not found', message:'Usecase tidak ditemukan.', statusCode:404);
        }
        $provinsi_kota = $this->usecaseRepositories->getProvinsiKotaByIdUsecase($idUsecase);
        $dataSektorIku = [
            'id_usecase' => $idUsecase,
            'provinsi' => $provinsi_kota->nama_provinsi,
            'kategori' => $provinsi_kota->kategori,
            'kabkot' => $provinsi_kota->nama_kab_kota,
            'indikator' => $data['indikator'],
            'satuan' => $data['satuan'],
            'tahun' => $data['tahun'],
            'nilai' => $data['nilai'],
            'flag_public' => $data['public'],
            'opd' => $data['opd'],
        ];
        $decodeSektor = ucwords(urldecode($data['sektor']));
        $dataSektorIku['urusan'] = $decodeSektor;


        $result = $this->usecaseRepositories->addSektorIku($dataSektorIku);

        $message = "Sektor IKU berhasil ditambahkan";
        return [$result, $message];
    }

    public function updateSektorIku($id_usecase, $data) {
        $idSektor = $data['id_sektor'];
        $oldSektorIku = $this->usecaseRepositories->getSektorIkuById($idSektor);
        if (!$oldSektorIku) {
            throw new ErrorResponse(type:'Not found', message:'Sektor IKU tidak ditemukan.', statusCode:404);
        }
        $dataSektorIku = [
            'indikator' => $data['indikator'],
            'satuan' => $data['satuan'],
            'tahun' => $data['tahun'],
            'nilai' => $data['nilai'],
            'flag_public' => $data['public'],
            'opd' => $data['opd'],
        ];
        $result = $this->usecaseRepositories->updateSektorIku($dataSektorIku, $idSektor);

        $message = "Sektor IKU berhasil diperbarui";
        return [$result, $message];
    }

    public function deleteSektorIku($id_usecase, $data) {
        $idSektor = $data['id_sektor'];
        $oldSektorIku = $this->usecaseRepositories->getSektorIkuById($idSektor);
        if (!$oldSektorIku) {
            throw new ErrorResponse(type:'Not found', message:'Sektor IKU tidak ditemukan.', statusCode:404);
        }
        $result = $this->usecaseRepositories->deleteSektorIku($idSektor);
        $message = "Sektor IKU berhasil dihapus";
        return [$message];
    }

    public function addIndikator($data) {
        $sektor = $data['sektor'];
        $maxIkk = $this->usecaseRepositories->getMaxIkk($sektor);

        $decodeIndikator = ucwords($data['indikator']);
        $dataIndikator = [
            'indikatorkinerja' => $decodeIndikator,
            'ikk' => $maxIkk->no_urut + 1,
            'sektor' => $sektor,
            'no_urut' => $maxIkk->no_urut + 1,
        ];
        $result = $this->usecaseRepositories->addIndikator($dataIndikator);

        $message = "Indikator berhasil ditambahkan";
        return [$result, $message];
    }

    public function importSektorIku($idUsecase, $sektor, $data) {
        $dataUsecase = $this->getUsecaseById($idUsecase);
        if (!$dataUsecase) {
            throw new ErrorResponse(type:'Not found', message:'Usecase tidak ditemukan.', statusCode:404);
        }
        if (count($data) < 2) {
            throw new ErrorResponse(type:'Unsupported Media Type', message:'Format file tidak sesuai.', statusCode:415);
        }
        $provinsi_kota = $this->usecaseRepositories->getProvinsiKotaByIdUsecase($idUsecase);

        foreach ($data as $i => $val) {
            if ($i == 0) continue;
            if ($val[4] == "") continue;

            $flag_public = $val[5] == "Public" ? 2 : 1;

            $tahun = intval($val[2]);
            $nilai = floatval($val[3]);

            $dataIku = [
                'id_usecase' => $idUsecase,
                'provinsi' => $provinsi_kota->nama_provinsi,
                'kategori' => $provinsi_kota->kategori,
                'kabkot' => $provinsi_kota->nama_kab_kota,
                'urusan' => ucwords(urldecode($sektor)),
                'indikator' => $val[0],
                'satuan' => $val[1],
                'tahun' => $tahun,
                'nilai' => $nilai,
                'opd' => $val[4],
                'flag_cms' => 2,
                'flag_public' => $flag_public,
            ];
            $result[] = $this->usecaseRepositories->addSektorIku($dataIku);
        }
        $message = 'Success import data IKU';
        return [$result, $message];
    }
}
