<?php

namespace App\Services\Content;

use App\Repositories\Content\LogoRepositories;
use App\Services\Admin\UsecaseServices;
use App\Traits\FileStorage;
use Exception;

class LogoServices {

    use FileStorage;
    protected $logoRepositories;
    protected $usecaseService;

    public function __construct(LogoRepositories $logoRepositories, UsecaseServices $usecaseService)
    {
        $this->logoRepositories = $logoRepositories;
        $this->usecaseService = $usecaseService;
    }

    public function setLogoGovernment($idUsecase, $file){
        $data = $this->usecaseService->getUsecaseById($idUsecase);

        $name_usecase = str_replace(' ', '', strtolower($data->name_usecase));

        $params = [
            'name_usecase' => $name_usecase,
            'type' => 'logo'
        ];

        $url = $this->uploadFile($idUsecase, $file, $params);

        $result = $this->usecaseService->updateLogoUsecaseGovern($url, $idUsecase);

        if($result[0] == 1){
            $message = 'Berhasil mengunggah Logo '.$data->name_usecase;
        } else if($result[0] == 0){
            $message = 'Berhasil memperbarui Logo '.$data->name_usecase;
        } else {
            throw new Exception('Gagal menambahkan Logo');
        }

        $response = [
            'message' => $message,
            'filename' => pathinfo($result[1], PATHINFO_BASENAME),
            'url' => $result[1]
        ];

        return $response;
    }

    public function getLogo($idUsecase){
        $data = $this->usecaseService->getUsecaseById($idUsecase);

        $path = pathinfo($data->logo, PATHINFO_BASENAME);

        if(empty($path)) $path = 'image_not_found.png';

        $url = $this->getFile($path);

        $response = [
            'filename' => $path,
            'url' => $url
        ];

        return $response;
    }

}
