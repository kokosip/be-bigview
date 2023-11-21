<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\UsecaseServices;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsecaseController extends Controller
{
    use ApiResponse;
    protected $usecaseService;

    public function __construct(UsecaseServices $usecaseService)
    {
        $this->usecaseService = $usecaseService;
    }

    public function listUsecase(Request $request){
        $search = $request->input("search");
        $perPage = is_null($request->input('per_page')) ? 10 : $request->input('per_page');

        try{
            [$data, $metadata] = $this->usecaseService->getListUsecase($search, $perPage);

            return $this->successResponse(data: $data, metadata: $metadata);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function listProvinsi(){
        try{
            $data = $this->usecaseService->getListProvinsi();

            return $this->successResponse($data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function listKabkota(Request $request){
        $kode_provinsi = $request->input("kode_provinsi");

        try{
            $data = $this->usecaseService->getListKabkota($kode_provinsi);

            return $this->successResponse($data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function addUsecaseCustom(Request $request){
        $validator = Validator::make($request->all(), [
            'kode_provinsi' => 'required',
            'kode_kab_kota' => 'nullable',
            'nama_usecase' => 'required',
            'base_color1' => 'required',
            'base_color2' => 'required',
            'base_color3' => 'required',
            'base_color4' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            [$govern, $usecase] = $this->usecaseService->addUsecaseGovernment($validator->validate());

            return $this->successResponse(data: $govern, metadata: $usecase);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function addUsecaseGovernment(Request $request){
        $validator = Validator::make($request->all(), [
            'kode_provinsi' => 'required',
            'kode_kab_kota' => 'nullable',
            'nama_usecase' => 'required',
            'base_color1' => 'required',
            'base_color2' => 'required',
            'base_color3' => 'required',
            'base_color4' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            [$govern, $usecase] = $this->usecaseService->addUsecaseGovernment($validator->validate());

            return $this->successResponse(data: $govern, metadata: $usecase);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
}
