<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Services\Admin\UsecaseServices;
use App\Services\Content\LogoServices;
use App\Services\Storage\FileStorageServices;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LogoController extends Controller
{
    use ApiResponse;
    protected $logoService;
    protected $usecaseService;
    protected $idUsecase;

    public function __construct(LogoServices $logoService, UsecaseServices $usecaseService)
    {
        $this->logoService = $logoService;
        $this->usecaseService = $usecaseService;
        $this->idUsecase = Auth::user()->id_usecase;
    }

    public function uploadLogoGovern(Request $request){
        $validator = Validator::make($request->all(), [
            'file' => 'required|file',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->logoService->setLogoGovernment($this->idUsecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
}
