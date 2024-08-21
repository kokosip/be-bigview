<?php

namespace App\Services\Admin;

use App\Repositories\Admin\UsecaseRepositories;
use App\Traits\FileStorage;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Exceptions\ErrorResponse;
use Illuminate\Support\Facades\Storage;

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
            throw new ErrorResponse(type: 'Not Found', message: 'Kata kunci tidak ditemukan', statusCode: 404);
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
          throw new ErrorResponse(type:"Not Found", message:"Data tabel kosong",statusCode:404);
        }

        return $rows;
    }

    public function addUsecase($data) {
        $row_geo = $this->usecaseRepositories->getGeoData($data);
        if (is_null($row_geo)) {
            throw new ErrorResponse(type:"Not Found", message:"Data geo tidak ditemukan.",statusCode:404);
        }
        
        $data['lat'] = $row_geo->lat;
        $data['lon'] = $row_geo->lon;

        $id_usecase = $this->usecaseRepositories->addUsecase($data);
        $message = 'Berhasil menambahkan usecase.';
        
        return [$data, $message];
    }

    public function addUsecaseProfile($data, $idUsecase) {
        $usecase = $this->getUsecaseById($idUsecase);
        if (!$usecase) {
            throw new ErrorResponse(type:'Not Found', message:'Usecase tidak ditemukan.', statusCode:404);
        }

        $data['nama_usecase'] = $usecase->name_usecase;
        $data['id_usecase'] = $idUsecase;

        $this->usecaseRepositories->addUsecaseProfile($data);
        $message = 'Berhasil menambahkan profile usecase.';
        
        return [$data, $message];
    }

    public function updateUsecase($data, $idUsecase) {
        $usecase = $this->getUsecaseById($idUsecase);
        if (!$usecase) {
            throw new ErrorResponse(type:'Not Found', message:'Usecase tidak ditemukan.', statusCode:404);
        }

        $this->usecaseRepositories->updateUsecase($data, $idUsecase);
        $message = 'Berhasil memperbarui usecase.';
        return [$data, $message];
    }

    public function updateUsecaseProfile($data, $idUsecase) {
        $usecase = $this->getUsecaseById($idUsecase);
        if (!$usecase) {
            throw new ErrorResponse(type:'Not Found', message:'Usecase tidak ditemukan.', statusCode:404);
        }

        $this->usecaseRepositories->updateUsecaseProfile($data, $idUsecase);
        $message = 'Usecase profile berhasil diperbarui';
        return [$data, $message];
    }

    public function deleteUsecase($idUsecase) {
        $usecase = $this->getUsecaseById($idUsecase);
        if (!$usecase) {
            throw new ErrorResponse(type:'Not Found', message:'Usecase tidak ditemukan.', statusCode:404);
        }

        $this->usecaseRepositories->deleteUsecase($idUsecase);
        $message = 'Berhasil menghapus usecase.';

        return $message;
    }

    public function deleteUsecaseProfile($idUsecase) {
        $usecase = $this->getUsecaseById($idUsecase);
        if (!$usecase) {
            throw new ErrorResponse(type:'Not Found', message:'Usecase tidak ditemukan.', statusCode:404);
        }

        $this->usecaseRepositories->deleteUsecaseProfile($idUsecase);
        $message = 'Berhasil menghapus profile usecase.';

        return $message;
    }

    public function getAllPolygon() {
        $rows = $this->usecaseRepositories->getAllPolygon();

        foreach ($rows as $row) {
            $path = $row->src_polygon;
            $fileContent = file_get_contents($this->getFile($path));
            $data = json_decode($fileContent, true);
            $response[] = $data;
        }

        return $response;
    }

    public function uploadPolygon($data) {
        $params = [
            'nama' => strtolower(str_replace(' ', '', $data['nama'])),
            'type' => 'polygon',
            'dir' => 'polygon'
        ];

        $data['src_polygon'] = $this->uploadJson($data['polygon'], $params);

        unset($data['polygon']);

        $polygon = $this->usecaseRepositories->uploadPolygon($data);
        $message = 'Berhasil upload polygon.';

        return [$polygon, $message];
    }

    public function updateUsecasePolygon($data, $idUsecase) {
        $usecase = $this->getUsecaseById($idUsecase);
        if (!$usecase){
            throw new ErrorResponse(type:'Not Found', message:'Usecase tidak ditemukan.', statusCode:404);
        }

        $this->usecaseRepositories->updateUsecase($data, $idUsecase);
        $message = 'Berhasil memperbarui polygon usecase.';

        return $message;
    }

    public function getUsecasePolygon($idUsecase) {
        $usecase = $this->getUsecaseById($idUsecase);
        if (!$usecase){
            throw new ErrorResponse(type:'Not Found', message:'Usecase tidak ditemukan.', statusCode:404);
        }
        $id_polygon = $usecase->id_polygon;
        if (is_null($id_polygon)) {
            throw new ErrorResponse(type:'Not Found', message:'Polygon tidak ditemukan.', statusCode:404);
        }
        $data_polygon = $this->usecaseRepositories->getPolygon($id_polygon);

        $path = $data_polygon->src_polygon;
        $data = json_decode(file_get_contents($this->getFile($path)), true);

        return $data;
    }

    public function updateLogoUsecase($data, $id_usecase){
        $pic_data = [
            "pic_logo" => $data,
        ];

        $result = $this->usecaseRepositories->updateUsecaseProfile($pic_data, $id_usecase);

        return [$result, $data];
    }

    public function getUsecaseById($id_usecase){
        $result = $this->usecaseRepositories->getUsecaseById($id_usecase);

        if($result){
            return $result;
        } else {
            throw new ErrorResponse(type: 'Not Found', message: 'Usecase tidak ditemukan', statusCode: 404);
        }
    }

    public function setLogo($idUsecase, $file){
        $data = $this->getUsecaseById($idUsecase);
        if (!$data){
            throw new ErrorResponse(type:'Not Found', message:'Usecase tidak ditemukan.', statusCode:404);
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
        $key = "usecase/{$params['dir']}/{$params['type']}_{$params['name_usecase']}_{$idUsecase}";
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
            throw new ErrorResponse(type:'Not Found', message:'Usecase tidak ditemukan.', statusCode:404);
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
            throw new ErrorResponse(type:'Not Found', message:'Usecase tidak ditemukan.', statusCode:404);
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
            $result = $this->usecaseRepositories->updateUsecaseProfile($newData, $idUsecase);
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
            throw new ErrorResponse(type:'Not Found', message:'Usecase tidak ditemukan.', statusCode:404);
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

        $result = $this->usecaseRepositories->updateUsecaseProfile($pic_data, $idUsecase);

        return [$result, $data];
    }



    public function updateContact($idUsecase, $data) {
        $dataUsecase = $this->getUsecaseById($idUsecase);
        if (!$dataUsecase) {
            throw new ErrorResponse(type:'Not Found', message:'Usecase tidak ditemukan.', statusCode:404);
        }

        $data['address'] = null;
        $data['phone'] = null;
        $data['link_map'] = null;
        $this->usecaseRepositories->updateUsecaseProfile($data, $idUsecase);

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
            throw new ErrorResponse(type:'Not Found', message:'Usecase tidak ditemukan.', statusCode:404);
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
            throw new ErrorResponse(type:'Not Found', message:'Usecase tidak ditemukan.', statusCode:404);
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
            throw new ErrorResponse(type:'Not Found', message:'Usecase tidak ditemukan.', statusCode:404);
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
            throw new ErrorResponse(type:'Not Found', message:'Usecase tidak ditemukan.', statusCode:404);
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
            throw new ErrorResponse(type:'Not Found', message:'Misi tidak ditemukan.', statusCode:404);
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
            throw new ErrorResponse(type:'Not Found', message:'Misi tidak ditemukan.', statusCode:404);
        }
        $this->usecaseRepositories->deleteMisi($id);

        $message = "Misi berhasil dihapus";
        return $message;
    }

    public function getListMisi($idUsecase, $data) {
        $dataUsecase = $this->getUsecaseById($idUsecase);
        if (!$dataUsecase) {
            throw new ErrorResponse(type:'Not Found', message:'Usecase tidak ditemukan.', statusCode:404);
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
            throw new ErrorResponse(type:'Not Found', message:'Usecase tidak ditemukan.', statusCode:404);
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
            throw new ErrorResponse(type:'Not Found', message:'Usecase tidak ditemukan.', statusCode:404);
        }
        $sektor = $data['sektor'];
        $data = $this->usecaseRepositories->getListDataSektor($idUsecase, $sektor);
        
        return $data;
    }

    public function getListIndikator($idUsecase, $data) {
        $dataUsecase = $this->getUsecaseById($idUsecase);
        if (!$dataUsecase) {
            throw new ErrorResponse(type:'Not Found', message:'Usecase tidak ditemukan.', statusCode:404);
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
            throw new ErrorResponse(type:'Not Found', message:'Usecase tidak ditemukan.', statusCode:404);
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
            throw new ErrorResponse(type:'Not Found', message:'Usecase tidak ditemukan.', statusCode:404);
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
            throw new ErrorResponse(type:'Not Found', message:'Sektor IKU tidak ditemukan.', statusCode:404);
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
            throw new ErrorResponse(type:'Not Found', message:'Sektor IKU tidak ditemukan.', statusCode:404);
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
            throw new ErrorResponse(type:'Not Found', message:'Usecase tidak ditemukan.', statusCode:404);
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

    public function addSektor($data, $idUsecase) {
        $dataUsecase = $this->getUsecaseById($idUsecase);
        if (!$dataUsecase) {
            throw new ErrorResponse(type:'Not Found', message:'Usecase tidak ditemukan.', statusCode:404);
        }
        if (!isset($data['link_iku'])) {
            $data['link_iku'] = null;
        }
        if (!isset($data['state_non_iku'])) {
            $data['state_non_iku'] = null;
        }
        $data['id_usecase'] = $idUsecase;

        $result = $this->usecaseRepositories->addSektor($data);
        $message = 'Berhasil menambahkan data sektor.';
        return [$result, $message];
    }

    public function getSektorById($idSektor) {
        $result = $this->usecaseRepositories->getSektorById($idSektor);

        if ($result) {
            return $result;
        } else {
            throw new ErrorResponse(type: 'Not Found', message: 'Sektor tidak ditemukan', statusCode: 404);
        }
    }

    public function deleteSektor($idSektor) {
        $this->getSektorById($idSektor);

        $this->usecaseRepositories->deleteSektor($idSektor);
        return 'Sektor berhasil dihapus.';
    }

    public function updateSektor($data, $idSektor) {
        $this->getSektorById($idSektor);

        $data = $this->usecaseRepositories->updateSektor($data, $idSektor);
        return [$data, 'Berhasil memperbarui data sektor.'];
    }

    public function getSektorUsecase($idUsecase) {
        $this->getUsecaseById($idUsecase);

        $data = $this->usecaseRepositories->getSektorUsecase($idUsecase);
        return $data;
    }

    public function editSubadminSektor($data, $idUsecase) {
        $this->getUsecaseById($idUsecase);
        $access_sektor = $data['sektor_order'];
        $id_subadmin = $data['id_subadmin'];

        $data = $this->usecaseRepositories->editSubadminSektor($access_sektor, $id_subadmin, $idUsecase);

        return $data;
    }

    public function sortSektor($data, $idUsecase) {
        $sektorOrder = $data['sektor_order'];
        $data = $this->usecaseRepositories->sortSektor($sektorOrder, $idUsecase);
        return $data;
    }

    public function getAssignedSektor($idUser) {
        $data = $this->usecaseRepositories->getAssignedSektor($idUser);
        return $data;
    }

    public function updateAssignedSektor($data, $idSektor, $idUser) {
        $this->getSektorById($idSektor);

        $currentAssignedSektor = $this->usecaseRepositories->getAssignedSektor($idUser);
        $sectorFound = false;
        foreach ($currentAssignedSektor as $assignedSektor) {
            if ($assignedSektor->id_sektor == $idSektor) {
                $sectorFound = true;
                break;
            }
        }

        if (!$sectorFound) {
            throw new ErrorResponse(type: 'Unauthorized', message: 'User tidak memiliki akses untuk sektor ini.', statusCode:403);
        }

        $data = $this->usecaseRepositories->updateAssignedSektor($data, $idSektor);
        return [$data, 'Berhasil memperbarui data sektor.'];
    }


}
