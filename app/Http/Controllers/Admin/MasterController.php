<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\MasterServices;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    use ApiResponse;
    protected $masterService;

    public function __construct(MasterServices $masterService)
    {
        $this->masterService = $masterService;
    }

    public function listProvinsi(){
        try{
            $data = $this->masterService->getListProvinsi();

            return $this->successResponse($data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }

    public function listKabkota(Request $request){
        $kode_provinsi = $request->input("kode_provinsi");

        try{
            $data = $this->masterService->getListKabkota($kode_provinsi);

            return $this->successResponse($data);
        } catch(Exception $e){
            return $this->errorResponse(type:"Failed", message:$e->getMessage(), statusCode:400);
        }
    }
}
