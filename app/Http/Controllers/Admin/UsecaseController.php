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

    public function listNameUsecase(){
        try{
            $data = $this->usecaseService->getListNameUsecase();

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function getUsecaseById($id_usecase){
        try{
            $data = $this->usecaseService->getUsecaseById($id_usecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function addUsecaseCustom(Request $request){
        $validator = Validator::make($request->all(), [
            'name_usecase' => 'required',
            'deskripsi' => 'required',
            'base_color1' => 'required',
            'base_color2' => 'required',
            'base_color3' => 'required',
            'base_color4' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $govern = $this->usecaseService->addUsecaseCustom($validator->validate());

            return $this->successResponse(data: $govern, message: "Usecase Custom Berhasil ditambahkan");
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function addUsecaseGovernment(Request $request){
        $validator = Validator::make($request->all(), [
            'kode_provinsi' => 'required',
            'kode_kab_kota' => 'nullable',
            'name_usecase' => 'required',
            'base_color1' => 'required',
            'base_color2' => 'required',
            'base_color3' => 'required',
            'base_color4' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $govern = $this->usecaseService->addUsecaseGovernment($validator->validate());

            return $this->successResponse(data: $govern, message: "Usecase Government Berhasil ditambahkan");
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function updateUsecaseGovern(Request $request, $id_usecase){
        $validator = Validator::make($request->all(), [
            'kode_provinsi' => 'required',
            'kode_kab_kota' => 'nullable',
            'name_usecase' => 'required',
            'base_color1' => 'required',
            'base_color2' => 'required',
            'base_color3' => 'required',
            'base_color4' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            [$govern, $usecase] = $this->usecaseService->updateUsecaseGovern($validator->validate(), $id_usecase);

            return $this->successResponse(data: $govern, metadata: $usecase, message: "Usecase Government Berhasil diperbaruhi");
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function updateUsecaseCustom(Request $request, $id_usecase){
        $validator = Validator::make($request->all(), [
            'name_usecase' => 'required',
            'deskripsi' => 'required',
            'base_color1' => 'required',
            'base_color2' => 'required',
            'base_color3' => 'required',
            'base_color4' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            [$custom, $usecase] = $this->usecaseService->updateUsecaseCustom($validator->validate(), $id_usecase);

            return $this->successResponse(data: $custom, metadata: $usecase, message: "Usecase Custom Berhasil diperbaruhi");
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function deleteUsecaseGovernment($id_usecase){
        try{
            $this->usecaseService->deleteUsecaseGovern($id_usecase);

            return $this->successResponse(message: "Data Berhasil dihapus");
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function deleteUsecaseCustom($id_usecase){
        try{
            $this->usecaseService->deleteUsecaseCustom($id_usecase);

            return $this->successResponse(message: "Data Berhasil dihapus");
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function uploadLogo(Request $request, $id_usecase){
        $validator = Validator::make($request->all(), [
            'file' => 'required|file',
        ]);

        if ($validator->fails()) {
            return $this->validationResponse($validator);
        }

        try{
            $data = $this->usecaseService->setLogo($id_usecase, $validator->validate());

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function getLogo($id_usecase){
        try{
            $data = $this->usecaseService->getLogo($id_usecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function deleteLogo($id_usecase){
        try {
            $data = $this->usecaseService->deleteLogo($id_usecase);

            return $this->successResponse(data: $data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message: $e->getMessage(), statusCode:400);
        }
    }

}