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

        return $result;
    }

}
