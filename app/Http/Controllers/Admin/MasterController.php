<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\MasterServices;
use App\Traits\ApiResponse;
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
        $data = $this->masterService->getListProvinsi();
        return $this->successResponse($data);
    }

    public function listKabkota(Request $request){
        $kode_provinsi = $request->input("kode_provinsi");
        $data = $this->masterService->getListKabkota($kode_provinsi);
        return $this->successResponse($data);
    }
}
